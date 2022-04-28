<?php

namespace App\Policies;

use App\Models\Level;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LevelPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('list levels');
    }


    public function view(User $user, Level $level): bool
    {
        return $user->hasPermissionTo('list levels');
    }


    public function create(User $user): bool
    {
        return $user->hasPermissionTo('add level');
    }


    public function update(User $user, Level $level): bool
    {
        return $user->hasPermissionTo('edit level');
    }


    public function delete(User $user, Level $level): bool
    {
        return $user->hasPermissionTo('delete level');
    }


    public function forceDelete(User $user, Level $level): bool
    {
        return $user->hasPermissionTo('delete level');
    }
}
