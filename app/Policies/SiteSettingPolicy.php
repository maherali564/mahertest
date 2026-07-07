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

    public function update(User $user, SiteSetting $setting): bool
    {
        return $user->can('update_site_setting');
    }

    public function delete(User $user, SiteSetting $setting): bool
    {
        return $user->can('delete_site_setting');
    }

    public function create(User $user): bool
    {
        return $user->can('create_site_setting');
    }
}
