<?php

namespace App\Policies;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubjectPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list subjects');
    }

    public function view(User $user, Subject $subject)
    {
        return $user->hasPermissionTo('list subjects');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('add subject');
    }

    public function update(User $user, Subject $subject)
    {
        return $user->hasPermissionTo('edit subject');

    }

    public function delete(User $user, Subject $subject)
    {
        return $user->hasPermissionTo('delete subject');

    }

    public function forceDelete(User $user, Subject $subject)
    {
        return $user->hasPermissionTo('delete subject');

    }
}
