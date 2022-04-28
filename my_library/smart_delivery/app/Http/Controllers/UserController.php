<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function switchStatus(User $user): \Illuminate\Http\JsonResponse
    {
        $this->userRepository->change($user);
        $user->tokens()->delete();
        return response()->json(['message' => Lang::get('messages.record.update')]);
    }

    public function enableUser(User $user): \Illuminate\Http\JsonResponse
    {
        $this->userRepository->enable($user);
        $user = User::findOrFail($user->id);
        return response()->json(['message' => Lang::get('messages.record.update'),'user' => $user]);
    }

    public function disableUser(User $user): \Illuminate\Http\JsonResponse
    {
        $this->userRepository->disable($user);
        $user = User::findOrFail($user->id);
        return response()->json(['message' => Lang::get('messages.record.update'),'user' => $user]);
    }

    public function switchLang($lang)
    {
        Session::put('appLocale', $lang);
        return Redirect::back();
    }
}
