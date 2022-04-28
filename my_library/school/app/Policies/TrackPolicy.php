<?php

namespace App\Policies;

use App\Models\Track;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrackPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('list tracks');
    }


    public function view(User $user, Track $track): bool
    {
        return $user->hasPermissionTo('list tracks');
    }


    public function create(User $user): bool
    {
        return $user->hasPermissionTo('add track');
    }


    public function update(User $user, Track $track): bool
    {
        return $user->hasPermissionTo('edit track');
    }


    public function delete(User $user, Track $track): bool
    {
        return $user->hasPermissionTo('delete track');
    }

    public function forceDelete(User $user, Track $track): bool
    {
        return $user->hasPermissionTo('delete track');
    }
}
