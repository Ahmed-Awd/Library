<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function change($user);

    public function reserveBalance($user, $value);

    public function createCode($user);

    public function updateMyInfo($data);

    public function createNewPhoneCode($user);

    public function enable(User $user);
    public function disable(User $user);
}
