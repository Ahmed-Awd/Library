<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BranchPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('list branch');
    }


    public function view(User $user, Branch $branch): bool
    {
        return $user->hasPermissionTo('list branch');
    }


    public function create(User $user): bool
    {
        return $user->hasPermissionTo('add branch');
    }


    public function update(User $user, Branch $branch): bool
    {
        return $user->hasPermissionTo('edit branch');
    }


    public function delete(User $user, Branch $branch): bool
    {
        return $user->hasPermissionTo('delete branch');
    }


    public function forceDelete(User $user, Branch $branch): bool
    {
        return $user->hasPermissionTo('delete branches');
    }
}
