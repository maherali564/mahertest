<?php

namespace App\Policies;

use App\Models\ProjectMedia;
use App\Models\User;

class ProjectMediaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_project_media');
    }

    public function view(User $user, ProjectMedia $media): bool
    {
        return $user->can('view_project_media');
    }

    public function create(User $user): bool
    {
        return $user->can('create_project_media');
    }

    public function update(User $user, ProjectMedia $media): bool
    {
        return $user->can('update_project_media');
    }

    public function delete(User $user, ProjectMedia $media): bool
    {
        return $user->can('delete_project_media');
    }
}
