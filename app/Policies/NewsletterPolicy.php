<?php

namespace App\Policies;

use App\Models\Newsletter;
use App\Models\User;

class NewsletterPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_newsletter');
    }

    public function view(User $user, Newsletter $newsletter): bool
    {
        return $user->can('view_newsletter');
    }

    public function create(User $user): bool
    {
        return $user->can('create_newsletter');
    }

    public function update(User $user, Newsletter $newsletter): bool
    {
        return $user->can('update_newsletter');
    }

    public function delete(User $user, Newsletter $newsletter): bool
    {
        return $user->can('delete_newsletter');
    }
}
