<?php

namespace App\Policies;

use App\Models\EmergencyDonation;
use App\Models\User;

class EmergencyDonationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_emergency_donation');
    }

    public function view(User $user, EmergencyDonation $donation): bool
    {
        return $user->can('view_emergency_donation');
    }

    public function create(User $user): bool
    {
        return $user->can('create_emergency_donation');
    }

    public function update(User $user, EmergencyDonation $donation): bool
    {
        return $user->can('update_emergency_donation');
    }

    public function delete(User $user, EmergencyDonation $donation): bool
    {
        return $user->can('delete_emergency_donation');
    }
}
