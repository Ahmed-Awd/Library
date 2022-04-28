<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Auth\Access\HandlesAuthorization;

class RestaurantPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Restaurant $restaurant)
    {
        return ($user->hasPermissionTo('control restaurants') or $user->id === $restaurant->owner_id);
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('control restaurants');
    }

    public function update(User $user, Restaurant $restaurant)
    {
        return ($user->hasPermissionTo('control restaurants') or $user->id == $restaurant->owner_id);
    }

    public function delete(User $user, Restaurant $restaurant)
    {
        return $user->hasPermissionTo('control restaurants');
    }
}
