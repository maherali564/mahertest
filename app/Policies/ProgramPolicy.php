<?php

namespace App\Policies;

use App\Models\Program;
use App\Models\User;

class ProgramPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_program');
    }

    public function view(User $user, Program $program): bool
    {
        return $user->can('view_program');
    }

    public function create(User $user): bool
    {
        return $user->can('create_program');
    }

    public function update(User $user, Program $program): bool
    {
        return $user->can('update_program');
    }

    public function delete(User $user, Program $program): bool
    {
        return $user->can('delete_program');
    }
}
