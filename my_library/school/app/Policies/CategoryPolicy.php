<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list categories');
    }
    public function view(User $user, Category  $category)
    {
        return $user->hasPermissionTo('list categories');

    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('add category');
    }

    public function update(User $user, Category $category)
    {
        return $user->hasPermissionTo('edit category');
    }

    public function delete(User $user, Category $category)
    {
        return $user->hasPermissionTo('delete category');

    }

}
