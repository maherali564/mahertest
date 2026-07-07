<?php

namespace App\Policies;

use App\Models\EmergencyCampaign;
use App\Models\User;

class EmergencyCampaignPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_emergency_campaign');
    }

    public function view(User $user, EmergencyCampaign $campaign): bool
    {
        return $user->can('view_emergency_campaign');
    }

    public function create(User $user): bool
    {
        return $user->can('create_emergency_campaign');
    }

    public function update(User $user, EmergencyCampaign $campaign): bool
    {
        return $user->can('update_emergency_campaign');
    }

    public function delete(User $user, EmergencyCampaign $campaign): bool
    {
        return $user->can('delete_emergency_campaign');
    }

    public function restore(User $user, EmergencyCampaign $campaign): bool
    {
        return $user->can('delete_emergency_campaign');
    }

    public function forceDelete(User $user, EmergencyCampaign $campaign): bool
    {
        return $user->can('delete_emergency_campaign');
    }
}
