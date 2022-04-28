<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('control customers');
    }


    public function view(User $user, User $model): bool
    {
        return ($user->hasPermissionTo('control customers') or $user->id === $model->id);
    }


    public function create(User $user): bool
    {
        return $user->hasPermissionTo('control customers');
    }


    public function update(User $user, User $model): bool
    {
        return ($user->hasPermissionTo('control customers') or $user->id === $model->id);
    }

    public function delete(User $user, User $model): bool
    {
        return ($user->hasPermissionTo('control customers')  or $user->id === $model->id);
    }
}
