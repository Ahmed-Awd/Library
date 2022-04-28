<?php

namespace App\Policies;

use App\Models\Academic;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AcademicYearPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list academic years');
    }

    public function view(User $user, Academic $academic)
    {
        return $user->can('list academic years');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('add academic year');
    }

    public function update(User $user, Academic $academicYear)
    {
        return $user->hasPermissionTo('edit academic year');
    }
}
