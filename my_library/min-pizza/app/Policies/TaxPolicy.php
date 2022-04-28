<?php

namespace App\Policies;

use App\Models\Tax;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaxPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('control taxes');
    }


    public function view(User $user, Tax $tax): bool
    {
        return $user->hasPermissionTo('control taxes');
    }


    public function create(User $user): bool
    {
        return $user->hasPermissionTo('control taxes');
    }


    public function update(User $user, Tax $tax): bool
    {
        return $user->hasPermissionTo('control taxes');
    }


    public function delete(User $user, Tax $tax): bool
    {
        return $user->hasPermissionTo('control taxes');
    }


    public function forceDelete(User $user, Tax $tax): bool
    {
        return $user->hasPermissionTo('control taxes');
    }
}
