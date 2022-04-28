<?php

namespace App\Policies;

use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClassPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('list classes');
    }


    public function view(User $user, ClassRoom $classRoom): bool
    {
        return $user->hasPermissionTo('list classes');
    }


    public function create(User $user): bool
    {
        return $user->hasPermissionTo('add class');
    }


    public function update(User $user, ClassRoom $classRoom): bool
    {
        return $user->hasPermissionTo('edit class');
    }


    public function delete(User $user, ClassRoom $classRoom): bool
    {
        return $user->hasPermissionTo('delete class');
    }


    public function forceDelete(User $user, ClassRoom $classRoom): bool
    {
        return $user->hasPermissionTo('delete class');
    }
}
