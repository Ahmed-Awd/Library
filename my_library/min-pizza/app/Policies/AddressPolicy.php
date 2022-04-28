<?php

namespace App\Policies;

use App\Models\Address;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddressPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user): bool
    {
        return true;
    }


    public function view(User $user, Address $address): bool
    {
        return ($user->hasPermissionTo('list addresses') or $user->id === $address->user_id);
    }


    public function create(User $user): bool
    {
        return $user->hasPermissionTo('add address');
    }


    public function update(User $user, Address $address): bool
    {
        return ($user->hasPermissionTo('edit address') or $user->id === $address->user_id);
    }


    public function delete(User $user, Address $address): bool
    {
        return ($user->hasPermissionTo('delete address') or $user->id === $address->user_id);
    }



    public function forceDelete(User $user, Address $address): bool
    {
        return ($user->hasPermissionTo('delete address') or $user->id === $address->user_id);
    }
}
