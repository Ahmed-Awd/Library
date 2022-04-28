<?php

namespace App\Policies;

use App\Models\Content;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContentPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('list contents');
    }


    public function view(User $user, Content $content): bool
    {
        return $user->hasPermissionTo('view contents');
    }


    public function create(User $user): bool
    {
        return $user->hasPermissionTo('add content');
    }


    public function update(User $user, Content $content): bool
    {
        return $user->hasPermissionTo('edit content');
    }


    public function delete(User $user, Content $content): bool
    {
        return $user->hasPermissionTo('delete content');
    }

    public function forceDelete(User $user, Content $content): bool
    {
        return $user->hasPermissionTo('delete content');
    }
}
