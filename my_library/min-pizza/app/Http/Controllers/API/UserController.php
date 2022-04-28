<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeStatusRequest;
use App\Http\Requests\SetDefaultAddressRequest;
use App\Http\Requests\UpdateMyInfoRequest;
use App\Models\Address;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Repositories\AddressRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class UserController extends Controller
{
    private UserRepositoryInterface $userRepository;
    private AddressRepositoryInterface $addressRepository;

    public function __construct(UserRepositoryInterface $userRepository, AddressRepositoryInterface $addressRepository)
    {
        $this->userRepository = $userRepository;
        $this->addressRepository = $addressRepository;
    }

    public function changeStatus(ChangeStatusRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();
        $hour = 0;
        if (isset($data['hour'])) {
            $hour = $data['hour'];
        }
        $status = $this->userRepository->changeStatus($user, $hour);

        $status == true ? $res = Lang::get('messages.driver.in_active') : $res = Lang::get('messages.driver.active');
        return response()->json(['message' => Lang::get('messages.driver.status_changed', ["status" => $res])]);
    }

    public function updateMyInfo(UpdateMyInfoRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->userRepository->update(Auth::user(), $data);
        return response()->json(['message' => Lang::get('messages.user.self_update')]);
    }

    public function showMyInfo(): JsonResponse
    {
        $user = $this->userRepository->show(Auth::user());
        if (Auth::user()->hasRole('admin')) {
            $user = $user[0];
        }
        return response()->json(['user' => $user]);
    }

    public function myFollows(Request $request): JsonResponse
    {
        $location['lat'] = $request->get('lat', false);
        $location['lng'] = $request->get('lng', false);

        $myFollows = $this->userRepository->showFollows(Auth::user(),$location);
        return response()->json(['myFollows' => $myFollows]);
    }

    public function myFavourites(): JsonResponse
    {
        $Favourites = $this->userRepository->showFavourites(Auth::user());
        return response()->json(['favoriteItems' => $Favourites]);
    }

    public function roles(): JsonResponse
    {
        $roles = \Spatie\Permission\Models\Role::select('id', 'name')->get();
        return response()->json(['roles' => $roles]);
    }

    public function permissions(): JsonResponse
    {
        $not = [
            "delete order",
            "edit order",
            "add restaurant rating",
            "list addresses",
            "add address",
            "edit address",
            "delete address",
            "edit restaurant rating",
            "list restaurant settings",
            "control menu item options",
            "list settings",
            "show settings",
            "update restaurant status",
            "change status",
            "see notification",
            "update worktime",
            "list worktimes",
            "change menu",
            "update restaurant setting",
            "list orders"
        ];
        $permissions = \Spatie\Permission\Models\Permission::select('id', 'name')->whereNotIn('name', $not)->get();
        return response()->json(['permissions' => $permissions]);
    }

    public function method(): JsonResponse
    {
        $methods = PaymentMethod::select('id', 'name', 'photo')->get();
        return response()->json(['methods' => $methods]);
    }

    public function list($type = "all"): JsonResponse
    {
        $users = $this->userRepository->list($type);
        return response()->json(['users' => $users]);
    }

    public function setDefaultAddress(Request $request, Address $address): JsonResponse
    {
        if ($address->user_id != Auth::user()->id) {
            return response()->json(["message" => "not found", 404]);
        }
        $user = Auth::user();
        $this->addressRepository->setDefault($address, $user);
        return response()->json(["message" => Lang::get('messages.update')]);
    }
}
