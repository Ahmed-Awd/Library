<?php

namespace App\Policies;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TopicPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('list topics');
    }


    public function view(User $user, Topic $topic): bool
    {
        return $user->hasPermissionTo('view topics');
    }


    public function create(User $user): bool
    {
        return $user->hasPermissionTo('add topic');
    }


    public function update(User $user, Topic $topic): bool
    {
        return $user->hasPermissionTo('edit topic');
    }


    public function delete(User $user, Topic $topic): bool
    {
        return $user->hasPermissionTo('delete topic');
    }

    public function forceDelete(User $user, Topic $topic): bool
    {
        return $user->hasPermissionTo('delete topic');
    }
}
