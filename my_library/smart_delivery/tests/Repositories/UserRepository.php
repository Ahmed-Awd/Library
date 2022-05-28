<?php

namespace Tests\Repositories;

use App\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function change($user)
    {
    }

    public function reserveBalance($user, $value)
    {
        return 30;
    }
    public function createCode($user)
    {

        return 4444;
    }
}
