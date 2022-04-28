<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;



    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('list courses');
    }


    public function view(User $user, Course $course): bool
    {
        return $user->hasPermissionTo('list courses');
    }


    public function create(User $user): bool
    {
        return $user->hasPermissionTo('add course');
    }


    public function update(User $user, Course $course): bool
    {
        return $user->hasPermissionTo('edit course');
    }


    public function delete(User $user, Course $course): bool
    {
        return $user->hasPermissionTo('delete course');
    }


    public function forceDelete(User $user, Course $course): bool
    {
        return $user->hasPermissionTo('delete course');
    }
}
