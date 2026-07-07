<?php

namespace App\Policies;

use App\Models\Slider;
use App\Models\User;

class SliderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_slider');
    }

    public function view(User $user, Slider $slider): bool
    {
        return $user->can('view_slider');
    }

    public function create(User $user): bool
    {
        return $user->can('create_slider');
    }

    public function update(User $user, Slider $slider): bool
    {
        return $user->can('update_slider');
    }

    public function delete(User $user, Slider $slider): bool
    {
        return $user->can('delete_slider');
    }
}
