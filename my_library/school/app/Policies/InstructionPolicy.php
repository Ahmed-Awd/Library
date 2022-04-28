<?php

namespace App\Policies;

use App\Models\Instruction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InstructionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('list instructions');
    }


    public function view(User $user, Instruction $instruction): bool
    {
        return $user->hasPermissionTo('view instructions');
    }


    public function create(User $user): bool
    {
        return $user->hasPermissionTo('add instruction');
    }


    public function update(User $user, Instruction $instruction): bool
    {
        return $user->hasPermissionTo('edit instruction');
    }


    public function delete(User $user, Instruction $instruction): bool
    {
        return $user->hasPermissionTo('delete instruction');
    }

    public function forceDelete(User $user, Instruction $instruction): bool
    {
        return $user->hasPermissionTo('delete instruction');
    }
}
