<?php

namespace App\Policies;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DriverPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('control drivers');
    }


    public function view(User $user, Driver $driver): bool
    {
        return ($user->hasPermissionTo('control drivers') or  $user->id === $driver->user_id);
    }


    public function create(User $user): bool
    {
        return $user->hasPermissionTo('control drivers');
    }

    public function update(User $user, Driver $driver): bool
    {
        return ($user->hasPermissionTo('control drivers'));
    }


    public function delete(User $user, Driver $driver): bool
    {
        return ($user->hasPermissionTo('control drivers'));
    }
}
