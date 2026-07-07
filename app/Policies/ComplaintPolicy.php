<?php

namespace App\Policies;

use App\Models\Complaint;
use App\Models\User;

class ComplaintPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_complaint');
    }

    public function view(User $user, Complaint $complaint): bool
    {
        return $user->can('view_complaint');
    }

    public function create(User $user): bool
    {
        return $user->can('create_complaint');
    }

    public function update(User $user, Complaint $complaint): bool
    {
        return $user->can('update_complaint');
    }

    public function delete(User $user, Complaint $complaint): bool
    {
        return $user->can('delete_complaint');
    }

    public function restore(User $user, Complaint $complaint): bool
    {
        return $user->can('delete_complaint');
    }

    public function forceDelete(User $user, Complaint $complaint): bool
    {
        return $user->can('delete_complaint');
    }
}
