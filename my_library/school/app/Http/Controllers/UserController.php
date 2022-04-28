<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{

    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->authorizeResource(User::class, 'user');
        $this->userRepository = $userRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $role = $request->get('role', 'all');
        $users = $this->userRepository->get($role);
        return response()->json($users);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->userRepository->store($validated);
        return response()->json(["message" => "user created successfully"]);
    }


    public function show(User $user): JsonResponse
    {
        $user = $this->userRepository->show($user);
        return response()->json($user);
    }


    public function update(UpdateUserRequest $request,User $user): JsonResponse
    {
        $validated = $request->validated();
        $this->userRepository->update($user,$validated);
        return response()->json(["message" => "user updated successfully"]);
    }


    public function destroy(User $user): JsonResponse
    {
        $this->userRepository->delete($user);
        return response()->json(["message" => "user deleted successfully"]);
    }

    public function suspend(User $user): JsonResponse
    {
        $this->userRepository->suspend($user);
        return response()->json(["message" => "user suspended successfully"]);
    }

    public function unsuspend(User $user): JsonResponse
    {
        $this->userRepository->unsuspend($user);
        return response()->json(["message" => "user un suspended successfully"]);
    }
}
