<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignPermissionsRequest;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class AdminController extends Controller
{

    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $search = $request->get('searchTerm', '');
        $order['by']     = $request->get('sort_by', false);
        $order['type']   = $request->get('sort_type', 'asc');
        $data = $this->userRepository->get($search, $order, 'admin');
        return response()->json(['admins' => $data]);
    }


    public function store(StoreAdminRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user =  $this->userRepository->create(Arr::except($data, ['permissions']));
        $user->assignRole('admin');
        if (isset($data['permissions'])) {
            $user->givePermissionTo($data['permissions']);
        }
        return response()->json(['message' => Lang::get('messages.admin.create')]);
    }


    public function show(User $user): JsonResponse
    {
        $user = $this->userRepository->show($user);
        return response()->json(['data' => $user]);
    }


    public function update(UpdateAdminRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();
        $this->userRepository->update($user, $data);
        return response()->json(['message' => Lang::get('messages.admin.update')]);
    }

    public function attachPermission(AssignPermissionsRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();
        $user->givePermissionTo($data['permission']);
        return response()->json(["message" => Lang::get('messages.admin.attached')]);
    }

    public function detachPermission(AssignPermissionsRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();
        $user->revokePermissionTo($data['permission']);
        return response()->json(["message" => Lang::get('messages.admin.detached')]);
    }
    public function destroy(User $user): JsonResponse
    {
        $this->userRepository->delete($user);
        return response()->json(["message" => Lang::get('messages.admin.delete')]);
    }
}
