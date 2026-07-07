<?php

namespace App\Policies;

use App\Models\Story;
use App\Models\User;

class StoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_story');
    }

    public function view(User $user, Story $story): bool
    {
        return $user->can('view_story');
    }

    public function create(User $user): bool
    {
        return $user->can('create_story');
    }

    public function update(User $user, Story $story): bool
    {
        return $user->can('update_story');
    }

    public function delete(User $user, Story $story): bool
    {
        return $user->can('delete_story');
    }

    public function restore(User $user, Story $story): bool
    {
        return $user->can('delete_story');
    }

    public function forceDelete(User $user, Story $story): bool
    {
        return $user->can('delete_story');
    }
}
