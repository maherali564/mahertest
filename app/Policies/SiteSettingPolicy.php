<?php

namespace App\Policies;

use App\Models\SiteSetting;
use App\Models\User;

class SiteSettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_site_setting');
    }

    public function view(User $user, SiteSetting $setting): bool
    {
        return $user->can('view_site_setting');
    }

    /**
     * Restrict access to sensitive fields (phone, email, whatsapp).
     * Only super_admin or users with manage_settings permission.
     */
    public function viewSensitiveData(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('manage_settings');
    }

    public function create(User $user): bool
    {
        return $user->can('create_site_setting');
    }

    public function update(User $user, SiteSetting $setting): bool
    {
        return $user->can('update_site_setting');
    }

    public function delete(User $user, SiteSetting $setting): bool
    {
        return $user->can('delete_site_setting');
    }
}
