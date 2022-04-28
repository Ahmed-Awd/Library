<?php

namespace App\Http\Controllers\API;

use App\Events\NewMovement;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\LoginDriverRequest;
use App\Http\Requests\CheckCodeRequest;
use App\Http\Requests\RegisterAsOwnerRequest;
use App\Http\Requests\RegisterDriverRequest;
use App\Http\Requests\ResendVerifyRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UpdateMyLocationRequest;
use App\Http\Requests\VerifyOwnerRequest;
use App\Mail\ResetPasswordMail;
use App\Models\Order;
use App\Models\User;
use App\Repositories\AttachmentRepositoryInterface;
use App\Repositories\DriverRepositoryInterface;
use App\Repositories\StoreRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Services\SmsServices;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Jobs\SendSms;

class AuthController extends Controller
{

    private AttachmentRepositoryInterface $attachmentRepository;
    private UserRepositoryInterface $userRepository;
    private StoreRepositoryInterface $storeRepository;
    private DriverRepositoryInterface $driverRepository;

    public function __construct(
        AttachmentRepositoryInterface $attachmentRepository,
        UserRepositoryInterface $userRepository,
        StoreRepositoryInterface $storeRepository,
        DriverRepositoryInterface $driverRepository
    ) {
        $this->attachmentRepository = $attachmentRepository;
        $this->userRepository = $userRepository;
        $this->storeRepository = $storeRepository;
        $this->driverRepository = $driverRepository;
    }

    public function storeLogin(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        if (
            Auth::attempt(['username' => $data['email'], 'password' => $data['password']]) ||
            Auth::attempt(['email' => $data['email'], 'password' => $data['password']])
        ) {
            $user = User::where('email', $data['email'])->orWhere('username', $data['email'])->first();

            if (!$user->hasRole('owner')) {
                return response()->json(['message' => Lang::get('messages.auth.invalid')], 401);
            }
            $user->language = $data['language'];
            $user->save();

            if ($user->mobile_verified_at == null) {
                $user->is_verified = false;
                $user->is_verify = false;
                return response()->json(
                    ['message' => Lang::get('messages.auth.verify')
                        ,'user' => $user->makeHidden('activation_code')],
                );
            }


            App::setLocale($user->language);

            $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
            $user['is_verified'] = $user->mobile_verified_at == null ? false : true;
            $user['is_verify'] = $user->mobile_verified_at == null ? false : true;
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'store' => $user->store,
                'user' => $user,
            ]);
        }
        return response()->json(['message' => Lang::get('messages.auth.invalid')], 401);
    }

    public function companyLogin(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        if (
            Auth::attempt(['username' => $data['email'], 'password' => $data['password']]) ||
            Auth::attempt(['email' => $data['email'], 'password' => $data['password']])
        ) {
            $user = User::where('email', $data['email'])->orWhere('username', $data['email'])->first();
            if (!$user->hasRole('outsource')) {
                return response()->json(['message' => Lang::get('messages.auth.invalid')], 401);
            }

            $user->language = $data['language'];
            $user->save();
            App::setLocale($user->language);

            $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ]);
        }
        return response()->json(['message' => Lang::get('messages.auth.invalid')], 401);
    }

    public function driverLogin(LoginDriverRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (
            Auth::attempt(['phone' => $data['phone'], 'country_code' => $data['country_code'],
            'password' => $data['password']])
        ) {
            $user = User::where('phone', $data['phone'])->Where('country_code', $data['country_code'])->first();
            if (!$user->hasRole('driver')) {
                return response()->json(['message' => Lang::get('messages.auth.invalid')], 401);
            }
            if ($user->is_disabled == true) {
                return response()->json(['message' => Lang::get('messages.auth.disabled')], 401);
            }
            $user->language = $data['language'] ?? "en";
            $user->is_available = true;
            $user->save();


            App::setLocale($user->language);

            $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
            $user['personal_photo'] = $this->getUrlPhoto($user, 'personal_photo');
            $user['license_photo'] = $this->getUrlPhoto($user, 'license_photo');
            $user['vehicle_photo'] = $this->getUrlPhoto($user, 'vehicle_photo');
            $user['vehicle_license_photo'] = $this->getUrlPhoto($user, 'vehicle_license_photo');
            $user['is_verified'] = $user->mobile_verified_at == null ? false : true;
            $user['is_verify'] = $user->mobile_verified_at == null ? false : true;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ]);
        }
        return response()->json(['message' => Lang::get('messages.auth.invalid')], 401);
    }


    public function logout(): JsonResponse
    {
        auth()->user()->update(['is_available' => false]);
        auth()->user()->tokens()->delete();
        return response()->json(['message' => Lang::get('messages.auth.logout')]);
    }

    public function register(RegisterDriverRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $data['is_disabled'] = true;
        $current = $this->createUser($data);
        $current->assignRole('driver');
        $this->uploadFiles($data, $current);

        $code = $this->userRepository->createCode($current->id);
        // resolve(SmsServices::class)->SendSms($current->country_code . $current->phone, $code, 2, 0);
        $result = sendingSms($current, $code);
        $token = $current->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $current,
            'message' => Lang::get('messages.auth.created'),
            'sms' => $result
        ]);
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = Auth::user();
        if (Hash::check($data['old_password'], $user->password)) {
            $user->update([
                "password" => bcrypt($data['password']),
            ]);
            return response()->json(['message' => Lang::get('messages.auth.password_changed')]);
        }
        return response()->json(['message' => Lang::get('messages.auth.invalid_old_password')], 403);
    }

    public function ownerRegister(RegisterAsOwnerRequest $request): JsonResponse
    {
        $data = $request->validated();
        $owner = $this->storeRepository->store($data);
        $sms = sendingSms($owner, $owner->activation_code);
        $owner->is_verify = false;
        return response()->json(['message' => Lang::get('messages.auth.reg')
            ,'user' => $owner->makeHidden('activation_code'),'sms' => $sms]);
    }

    public function createUser($data)
    {
        return User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'password' => $data['password'],
            'is_disabled' => $data['is_disabled'],
            'phone' => $data['phone'],
            'country_code' => $data['country_code'],
            'town_id' => $data['town'],
        ]);
    }

    public function uploadFiles($data, $user)
    {
        $this->attachmentRepository->linkAttachment($user, $data['personal_photo'], "personal_photo");
        $this->attachmentRepository->linkAttachment($user, $data['license_photo'], "license_photo");
        $this->attachmentRepository->linkAttachment($user, $data['vehicle_photo'], "vehicle_photo");
        $this->attachmentRepository->linkAttachment($user, $data['vehicle_license_photo'], "vehicle_license_photo");
    }

    public function getUrlPhoto($user, $type)
    {
        $photo = $this->attachmentRepository->get([
            ['description', '=', $type],
            ['fileable_id', '=', $user->id],
        ]);
        return $photo ? url('/storage/' . $photo->path) : "";
    }

    public function generateCode(): JsonResponse
    {
        $user = auth()->user();
        $code = $this->userRepository->createCode($user->id);
        // resolve(SmsServices::class)->SendSms($user->country_code . $user->phone, $code, 2, 0);
        $result = sendingSms($user, $code);
        return response()->json(['message' => Lang::get('messages.auth.generate_code')], 200);
    }

    public function checkCode(CheckCodeRequest $request): JsonResponse
    {
        $user = auth()->user();
        $code = auth()->user()->code;
        if ($request->code == auth()->user()->code->code) {
            $user->mobile_verified_at = now();
            $user->save();
            auth()->user()->code->delete();
            return response()->json(['message' => Lang::get('messages.auth.driver_verify')], 200);
        } else {
            $code->number_of_tries += 1;
        }
        $code->save();
        return response()->json(['message' => Lang::get('messages.auth.try')], 402);
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = User::where('username', $data['email'])->orWhere('email', $data['email'])->first();
        if ($data['reset_by'] == "phone" && !$user) {
            $user = User::where('phone', $data['email'])->Where('country_code', $data['country_code'])->first();
        }
        if (!$user) {
            return response()->json(['message' => Lang::get('messages.auth.invalid')], 404);
        }
        $code = rand(1111, 9999);
        $user->update(["reset_code" => $code]);
        if ($data['reset_by'] == "email") {
            $this->sendResetMail($user, $code);
            return response()->json(['message' => Lang::get('messages.auth.mail_code')]);
        }

        if ($data['reset_by'] == "phone") {
            $result = sendingSms($user, $code);
            return response()->json(['message' => Lang::get('messages.auth.phone_code'),'sms' => $result]);
        }

        return response()->json(['message' => Lang::get('messages.auth.wrong')]);
    }

    public function sendResetMail($user, $code)
    {
        $details = [
            'title' => "hello mr {$user->name} this is the your reset code",
            'body' => "the code : {$code}"
        ];
        Mail::to($user->email)->send(new ResetPasswordMail($details));
    }

    public function sendResetPhone($user, $code): string
    {
        resolve(SmsServices::class)->SendSms($user->country_code . $user->phone, $code, 2, 0);
        return $user->country_code . "-" . substr_replace($user->phone, "xxxxxx", 0, 6);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $data = $request->validated();
        $user = User::where('username', $data['email'])->orWhere('email', $data['email'])->first();
        if (!$user && isset($data['country_code'])) {
            $user = User::where('phone', $data['email'])->Where('country_code', $data['country_code'])->first();
        }
        if (!$user) {
            return response()->json(['message' => Lang::get('messages.auth.invalid')], 404);
        }
        if ($user->reset_code != $data['code']) {
            return response()->json(['message' => Lang::get('messages.auth.code')], 403);
        }

        $user->update([
            "password" => bcrypt($data['password']),
            "reset_code" => null
        ]);
        return response()->json(['message' => Lang::get('messages.auth.password_changed')]);
    }

    public function ownerVerify(VerifyOwnerRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = User::where('phone', $data['phone'])->Where('country_code', $data['country_code'])->first();
        if (!$user) {
            return response()->json(['message' => Lang::get('messages.auth.invalid')], 404);
        }

        if ($user->mobile_verified_at != null) {
            return response()->json(['message' => Lang::get('messages.auth.already')], 401);
        }

        if ($user->activation_code != $data['activation_code']) {
            return response()->json(['message' => Lang::get('messages.auth.code')], 403);
        }
        if ($user->activation_code_expire->lt(Carbon::now())) {
            return response()->json(['message' => Lang::get('messages.auth.code_expired')], 403);
        }
        $user->update([
            "mobile_verified_at" => Carbon::now(),
            "activation_code" => null,
            "activation_code_expire" => null,
        ]);

        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
            'message' => Lang::get('messages.auth.driver_verify'),
        ]);
    }

    public function resendVerify(ResendVerifyRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = User::where('phone', $data['phone'])->Where('country_code', $data['country_code'])->first();
        $code = rand(1111, 9999);
        $expire_time = Carbon::now()->addMinutes(settings('activation_code_expire'));
        $user->update([
            'activation_code' => $code,
            'activation_code_expire' => $expire_time
        ]);
        $result = sendingSms($user, $code);
        return response()->json(['message' => Lang::get('messages.auth.phone_code'),"result" => $result]);
    }

    public function updateMyLocation(UpdateMyLocationRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->driverRepository->updateLocation($data);
        $user = $this->driverRepository->show(Auth::user());
        $order = $user->currentOrder->first();
        if ($order) {
            event(new NewMovement($order, $data));
            return response()->json(['order' => $order]);
        }
        return response()->json(['message' => "location updated"]);
    }
}
