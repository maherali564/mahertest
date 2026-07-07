<?php

namespace App\Policies;

use App\Models\Partner;
use App\Models\User;

class PartnerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_partner');
    }

    public function view(User $user, Partner $partner): bool
    {
        return $user->can('view_partner');
    }

    public function create(User $user): bool
    {
        return $user->can('create_partner');
    }

    public function update(User $user, Partner $partner): bool
    {
        return $user->can('update_partner');
    }

    public function delete(User $user, Partner $partner): bool
    {
        return $user->can('delete_partner');
    }
}
