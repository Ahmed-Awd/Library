<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list orders');
    }
    public function view(User $user, Order $Order)
    {
        return $user->hasPermissionTo('list orders');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('add order');
    }

    public function update(User $user, Order $Order)
    {
        return $user->hasPermissionTo('edit order');
    }

    public function delete(User $user, Order $Order)
    {
        return $user->hasPermissionTo('delete order');
    }
}
