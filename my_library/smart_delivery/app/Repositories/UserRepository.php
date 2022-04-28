<?php

namespace App\Repositories;

use App\Exceptions\NotEnoughBalanceException;
use App\Models\User;
use App\Models\UserAuthCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserRepository implements UserRepositoryInterface
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function change($user)
    {
        $status = $user->is_disabled;
        $user->is_disabled = !$status;
        $user->save();
    }

    public function enable(User $user)
    {
        $user->update(["is_disabled" => 0]);
    }

    public function disable(User $user)
    {
        $user->update(["is_disabled" => 1]);
    }

    public function reserveBalance($user, $value)
    {
        $user = User::findOrFail($user);
        $user->balance = $user->balance - ($value);
        throw_if($user->balance < 0, NotEnoughBalanceException::class);
        $user->reserved_balance = $user->reserved_balance + ($value);
        $user->save();
    }

    public function createCode($user)
    {
        $new_code = rand(1000, 9999);
        if (config('app.env') == 'local') {
            $new_code = "0000";
        }
        $user = User::findOrFail($user);
        if ($user->code) {
            $user->code->update([
            "code"   => $new_code,
            "number_of_tries" => 0,
            ]);
        } else {
            $code = new UserAuthCode();
            $code->code = $new_code;
            $code->user_id = $user->id;
            $code->save();
        }
        return $new_code;
    }

    public function createNewPhoneCode($user)
    {
        $new_code = rand(1000, 9999);
        if (config('app.env') == 'local') {
            $new_code = "0000";
        }
        $user = User::findOrFail($user);
        $user->update(["new_phone_code" => $new_code,"number_of_tries" => 0]);
        return $new_code;
    }

    public function updateMyInfo($data)
    {
        Auth::user()->update($data);
    }
}
