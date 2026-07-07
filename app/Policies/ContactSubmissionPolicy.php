<?php

namespace App\Policies;

use App\Models\ContactSubmission;
use App\Models\User;

class ContactSubmissionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_contact_submission');
    }

    public function view(User $user, ContactSubmission $submission): bool
    {
        return $user->can('view_contact_submission');
    }

    public function create(User $user): bool
    {
        return $user->can('create_contact_submission');
    }

    public function update(User $user, ContactSubmission $submission): bool
    {
        return $user->can('update_contact_submission');
    }

    public function delete(User $user, ContactSubmission $submission): bool
    {
        return $user->can('delete_contact_submission');
    }

    public function restore(User $user, ContactSubmission $submission): bool
    {
        return $user->can('delete_contact_submission');
    }

    public function forceDelete(User $user, ContactSubmission $submission): bool
    {
        return $user->can('delete_contact_submission');
    }
}
