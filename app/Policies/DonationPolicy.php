<?php

namespace App\Policies;

use App\Models\Donation;
use App\Models\User;

class DonationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_donation');
    }

    public function view(User $user, Donation $donation): bool
    {
        return $user->can('view_donation') && ($user->hasRole('super_admin') || $user->id === $donation->user_id || $user->email === $donation->email);
    }

    public function create(User $user): bool
    {
        return $user->can('create_donation');
    }

    public function update(User $user, Donation $donation): bool
    {
        return $user->can('update_donation');
    }

    public function delete(User $user, Donation $donation): bool
    {
        return $user->can('delete_donation');
    }

    public function restore(User $user, Donation $donation): bool
    {
        return $user->can('delete_donation');
    }

    public function forceDelete(User $user, Donation $donation): bool
    {
        return $user->can('delete_donation');
    }
}
