<?php

namespace App\Policies;

use App\Models\Feedback;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FeedbackPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('list feedbacks');
    }


    public function view(User $user, Feedback $feedback): bool
    {
        return ($user->hasPermissionTo('view feedbacks') or $user->id === $feedback->user_id);
    }


    public function create(User $user): bool
    {
        return $user->hasPermissionTo('add feedback');
    }


    public function update(User $user, Feedback $feedback): bool
    {
        return ($user->hasPermissionTo('edit feedback') and $user->id === $feedback->user_id);
    }


    public function delete(User $user, Feedback $feedback): bool
    {
        return ($user->hasPermissionTo('delete feedback') or $user->id === $feedback->user_id);
    }


    public function forceDelete(User $user, Feedback $feedback): bool
    {
        return ($user->hasPermissionTo('delete feedback') or $user->id === $feedback->user_id);
    }
}
