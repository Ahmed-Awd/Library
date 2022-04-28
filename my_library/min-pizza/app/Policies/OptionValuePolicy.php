<?php

namespace App\Policies;

use App\Models\OptionValue;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OptionValuePolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user): bool
    {
        return true;
    }


    public function view(User $user, OptionValue $optionValue): bool
    {
        return true;
    }


    public function create(User $user): bool
    {
        return $user->hasPermissionTo('control menu item options');
    }


    public function update(User $user, OptionValue $optionValue): bool
    {
        return $user->hasPermissionTo('control menu item options');
    }


    public function delete(User $user, OptionValue $optionValue): bool
    {
        return $user->hasPermissionTo('control menu item options');
    }


    public function forceDelete(User $user, OptionValue $optionValue): bool
    {
        return $user->hasPermissionTo('control menu item options');
    }
}
