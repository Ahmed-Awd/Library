<?php

namespace App\Policies;

use App\Models\OptionCategory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OptionCategoryPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user): bool
    {
        return true;
    }


    public function view(User $user, OptionCategory $optionCategory): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('control menu item options');
    }


    public function update(User $user, OptionCategory $optionCategory): bool
    {
        return $user->hasPermissionTo('control menu item options');
    }


    public function delete(User $user, OptionCategory $optionCategory): bool
    {
        return $user->hasPermissionTo('control menu item options');
    }


    public function forceDelete(User $user, OptionCategory $optionCategory): bool
    {
        return $user->hasPermissionTo('control menu item options');
    }
}
