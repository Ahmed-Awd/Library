<?php

namespace App\Policies;

use App\Models\Program;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProgramPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('list programs');
    }


    public function view(User $user, Program $program): bool
    {
        return $user->hasPermissionTo('list programs');
    }


    public function create(User $user): bool
    {
        return $user->hasPermissionTo('add program');
    }


    public function update(User $user, Program $program): bool
    {
        return $user->hasPermissionTo('edit program');
    }


    public function delete(User $user, Program $program): bool
    {
        return $user->hasPermissionTo('delete program');
    }


    public function forceDelete(User $user, Program $program): bool
    {
        return $user->hasPermissionTo('delete program');
    }
}
