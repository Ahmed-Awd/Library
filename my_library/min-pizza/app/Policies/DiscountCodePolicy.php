<?php

namespace App\Policies;

use App\Models\DiscountCode;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DiscountCodePolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('control codes');
    }

    public function view(User $user, DiscountCode $discountCode): bool
    {
        return ($user->hasPermissionTo('control codes') or $discountCode->user_id == $user->id);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('control codes');
    }


    public function update(User $user, DiscountCode $discountCode): bool
    {
        return $user->hasPermissionTo('control codes');
    }


    public function delete(User $user, DiscountCode $discountCode): bool
    {
        return $user->hasPermissionTo('control codes');
    }


    public function restore(User $user, DiscountCode $discountCode): bool
    {
        return $user->hasPermissionTo('control codes');
    }


    public function forceDelete(User $user, DiscountCode $discountCode): bool
    {
        return $user->hasPermissionTo('control codes');
    }
}
