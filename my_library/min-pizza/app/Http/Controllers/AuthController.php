<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeLangRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SocialLoginRequest;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = User::create([
            "name" => $data['name'],
            "email" => $data['email'],
            "password" => Hash::make($data['password']),
            "city_id" => $data['city_id'],
            "country_code" => $data['country_code'],
            "phone" => $data['phone'],
        ]);
        $user->assignRole('customer');
        try{
            $user->sendEmailVerificationNotification();

        } catch(Exception $ex)
        {
            return response()->json(["message" => Lang::get('messages.auth.there_was_problem')],401);
        }
        return response()->json(["message" => Lang::get('messages.auth.verification')]);
    }


    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        $restaurant = null;
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            $user = User::where('email', $data['email'])->first();
            if ($user->is_disabled == true) {
                return response()->json(['message' => Lang::get('messages.auth.disabled')], 401);
            }
            if ($user->email_verified_at == null) {
                return response()->json(['message' => Lang::get('messages.auth.verify')], 401);
            }
            if (!$user->hasRole($data['role'])) {
                return response()->json(['message' => Lang::get('messages.auth.invalid_login')], 401);
            }
            if ($user->hasRole('owner')) {
                $user->restaurant()->update(["mode" => "online"]);
                $restaurant = $user->restaurant;
            }
            $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
            $user->update(['default_lang' => $data['default_lang']]);
            if ($user->hasRole('admin')) {
                $user=$this->userRepository->show($user)[0];
            }

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' =>$user ,
                'role' =>$data['role'] ,
                'restaurant' => $restaurant ,
            ]);
        }
        return response()->json(['message' => Lang::get('messages.auth.invalid_login')], 401);
    }

    public function socialLogin(SocialLoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        if ($data['social_type'] == "facebook") {
            $user = User::where('facebook_id', $data['social_id'])->first();
        }
        if ($data['social_type'] == "google") {
            $user = User::where('google_id', $data['social_id'])->first();
        }
        if ($data['social_type'] == "apple") {
            $user = User::where('apple_id', $data['social_id'])->first();
        }
        if ($user) {
            $user->update(['default_lang' => $data['default_lang']]);
            $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' =>$user ,
            ]);
        }
        if (isset($data['email'])) {
            $exist = User::where('email', $data['email'])->first();
            if ($exist) {
                return response()->json(['message' => Lang::get('messages.auth.email_exist')], 401);
            }
        }

        $result = $this->createSocialUser($data);
        $token = $result->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' =>$result ,
        ]);
    }

    public function createSocialUser($data)
    {
        $str = rand();
        $str = md5($str);
        $data['social_type'] == "facebook" ? $facebook = $data['social_id'] : $facebook = null;
        $data['social_type'] == "google" ? $google = $data['social_id'] : $google = null;
        $data['social_type'] == "apple" ? $apple = $data['social_id'] : $apple = null;
        isset($data['email']) ? $email = $data['email'] : $email = $str."@{$data['social_type']}.com";
        $user = User::create([
            "name" => $data['name'],
            "email" => $email,
            "password" => Hash::make($data['social_id']),
            "account_type" => "social",
            "facebook_id" => $facebook,
            "google_id" => $google,
            "apple_id" => $apple,
            "email_verified_at" => Carbon::now(),
        ]);
        $user->assignRole('customer');
        $user->update(['default_lang' => $data['default_lang']]);
        return $user;
    }

    public function getUserToken(User $user): JsonResponse
    {
        if ($user->hasRole('owner')) {
            $token = $user->createToken('auth_token', ['*'], null)->plainTextToken;
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ]);
        }
        return response()->json(['message' => Lang::get('messages.auth.invalid_login')], 401);
    }

    public function logout(): JsonResponse
    {
        $user = Auth::user();
        auth()->user()->tokens()->delete();
        if ($user->hasRole('owner')) {
            $user->restaurant()->update(["mode" => "offline"]);
        }
        return response()->json(['message' => Lang::get('messages.auth.logout')]);
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            return response()->json(['message' => Lang::get('messages.auth.invalid_mail')], 404);
        }
        if (!$user->hasRole($data['role'])) {
            return response()->json(['message' => Lang::get('messages.auth.invalid_login')], 401);
        }
        $code = strtoupper(Str::random('8'));
        $user->update(["reset_code" => $code]);
        try {
        $this->sendResetMail($user, $code);
        }
        catch(Exception $ex)
        {
            return response()->json(["message" => Lang::get('messages.auth.there_was_problem')],401);
        }
        return response()->json(['message' => Lang::get('messages.auth.reset_send', ['mail' => $user->email])]);
    }

    public function sendResetMail($user, $code)
    {
        $details = Lang::get('messages.auth.reset_title', ['name' => $user->name]);
        Mail::to($user->email)->send(new ResetPasswordMail($details, $code));
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            return response()->json(['message' => Lang::get('messages.auth.invalid_mail')], 404);
        }
        if ($user->reset_code != $data['code']) {
            return response()->json(['message' => Lang::get('messages.auth.invalid_code')], 403);
        }
        $user->update([
            "password" => bcrypt($data['password']),
            "reset_code" => null
        ]);
        return response()->json(['message' => Lang::get('messages.auth.password_change')]);
    }

    public function changeLanguage(ChangeLangRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = Auth::user();
        $user->update(['default_lang' => $data['language']]);
        return response()->json(['message' => Lang::get('messages.update')]);
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = Auth::user();
        if (Hash::check($data['old_password'], $user->password)) {
            $user->update([
                "password" => bcrypt($data['password']),
            ]);
            return response()->json(['message' => Lang::get('messages.auth.password_change')]);
        }
        return response()->json(['message' => Lang::get('messages.auth.invalid_old_password')], 403);
    }

    public function deleteMyAccount(Request $request): JsonResponse
    {
        $user = Auth::user();
        $user->tokens()->delete();
        $user->delete();
        return response()->json(['message' => Lang::get('messages.auth.account_deleted')]);
    }

    public function switchNotification(Request $request): JsonResponse
    {
        $user = Auth::user();
        $user->notifications = !$user->notifications;
        $user->save();
        return response()->json(['message' => Lang::get('messages.auth.notifications')]);
    }
}
