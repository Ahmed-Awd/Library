<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeLangRequest;
use App\Http\Requests\ChangeMyPhoneRequest;
use App\Http\Requests\ConfirmPhoneRequest;
use App\Http\Requests\UpdateDriverInfo;
use App\Http\Requests\UpdateDriverPapersRequest;
use App\Http\Requests\UpdateOwnerInfo;
use App\Models\User;
use App\Repositories\DriverRepositoryInterface;
use App\Repositories\StoreRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use phpDocumentor\Reflection\Types\ContextFactory;

class UserController extends Controller
{
    private DriverRepositoryInterface $driverRepository;
    private UserRepositoryInterface $userRepository;
    private StoreRepositoryInterface $storeRepository;

    public function __construct(
        DriverRepositoryInterface $driverRepository,
        UserRepositoryInterface $userRepository,
        StoreRepositoryInterface $storeRepository
    ) {
        $this->driverRepository = $driverRepository;
        $this->userRepository = $userRepository;
        $this->storeRepository = $storeRepository;
    }

    public function changeAvailability(Request $request): JsonResponse
    {
        $result = $this->driverRepository->changeAvailability();
        $result == true ? $status = "available" : $status = "un available";
        return response()->json(['message' => Lang::get('messages.auth.your_account', ['statue' => $status])]);
    }

    public function changeLang(ChangeLangRequest $request): JsonResponse
    {
        $validated = $request->validated();
        Auth::user()->update($validated);
        return response()->json(['message' => Lang::get('messages.lang.change')]);
    }

    public function changeLangLocale(ChangeLangRequest $request): JsonResponse
    {
        $validated = $request->validated();
        App::setLocale($validated["language"]);
        return response()->json(['message' => Lang::get('messages.lang.change')]);
    }

    public function updateOwnerInfo(UpdateOwnerInfo $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['name'] = $validated['owner_name'];
        $this->userRepository->updateMyInfo(Arr::only($validated, ['name','town_id']));
        $validated['name'] = $validated['store_name'];
        $this->storeRepository->updateMyStore(Arr::only(
            $validated,
            ['name','address','town_id','lat','lng','type_id','default_delivery_time']
        ));
        return response()->json(['message' => Lang::get('messages.success')]);
    }

    public function updateDriverInfo(UpdateDriverInfo $request): JsonResponse
    {
        $validated = $request->validated();
        $user = User::find(Auth::id());
        $this->driverRepository->updateMyInfo($user, $validated);
        return response()->json(['message' => Lang::get('messages.success')]);
    }

    public function changeMyPhone(ChangeMyPhoneRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $user = User::find(Auth::id());
        $code = $this->userRepository->createNewPhoneCode($user->id);
        $data['new_phone'] = $validated['phone'] ?? null;
        $data['new_country_code'] = $validated['country_code'] ?? null;
        $this->driverRepository->updateMyInfo($user, $data);
        $result = sendingNewPhoneSms($user, $code);
        return response()->json(['message' => Lang::get('messages.please_verify_phone')]);
    }

    public function resendNewPhoneCode(Request $request): JsonResponse
    {
        $user = User::find(Auth::id());
        $code = $this->userRepository->createNewPhoneCode($user->id);
        return response()->json(['message' => Lang::get('messages.please_verify_phone')]);
    }

    public function confirmNewPhone(ConfirmPhoneRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $code = $validated['new_phone_code'];
        $user = User::find(Auth::id());
        if ($user->number_of_tries == 5) {
            return response()->json(['message' => Lang::get('messages.max_code_usage')]);
        }
        if ($code != $user->new_phone_code) {
            $user->increment('number_of_tries');
            return response()->json(['message' => Lang::get('messages.auth.code')], 403);
        }
        $data = $this->prepareNewPhone($user);
        $this->driverRepository->updateMyInfo($user, $data);
        return response()->json(['message' => Lang::get('messages.success')]);
    }

    public function prepareNewPhone($user): array
    {
        $data = array();
        $data['phone'] = $user->new_phone;
        $data['country_code'] = $user->new_country_code;
        $data['new_country_code'] = null;
        $data['new_phone'] = null;
        $data['number_of_tries'] = 0;
        $data['new_phone_code'] = null;
        return $data;
    }

    public function updateDriverPapers(UpdateDriverPapersRequest $request): JsonResponse
    {
        $res = $request->validated();
        $user = User::find(Auth::id());
        $this->driverRepository->updatePapers($user, $res);
        return response()->json(['message' => Lang::get('messages.success')
            ,'message_2' => Lang::get('messages.wait_for_admin')]);
    }

    public function updateOnlineTime(Request $request): JsonResponse
    {
        $user = User::where('id', Auth::id())->first();
        $user->last_time_online == null ? $time = 6 : $time = Carbon::now()->diffInMinutes($user->last_time_online);
        if ($time > 0) {
            $user->last_time_online = Carbon::now();
            $time > 5 ? $time = 5 : 1 == 1;
            $user->increment('online_time_today', $time);
            $user->save();
        }
        return response()->json(['message' => Lang::get('messages.success'),'time' => $time]);
    }
}
