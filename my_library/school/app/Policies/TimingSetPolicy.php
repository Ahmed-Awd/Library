<?php

namespace App\Policies;

use App\Models\TimingSet;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TimingSetPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list timing sets');
    }


    public function view(User $user, TimingSet $timingSet)
    {
        return $user->hasPermissionTo('list timing sets');
    }


    public function create(User $user)
    {
        return $user->hasPermissionTo('add timing set');
    }


    public function update(User $user, TimingSet $timingSet)
    {
        return $user->hasPermissionTo('edit timing set');
    }


    public function delete(User $user, TimingSet $timingSet)
    {
        return $user->hasPermissionTo('delete timing set');
    }

    public function forceDelete(User $user, TimingSet $timingSet)
    {
        return $user->hasPermissionTo('delete timing set');
    }
}
