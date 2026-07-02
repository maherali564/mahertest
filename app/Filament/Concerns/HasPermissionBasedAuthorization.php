<?php

namespace App\Filament\Concerns;

use Illuminate\Support\Str;

trait HasPermissionBasedAuthorization
{
    /** Derive the permission slug from the resource class name. */
    public static function getPermissionSlug(): string
    {
        $class = class_basename(static::class);
        return Str::snake(Str::before($class, 'Resource'));
    }

    /** Check permission: view_any_{resource}. */
    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        $permission = 'view_any_' . static::getPermissionSlug();
        return $user->can($permission);
    }

    /** Check permission: view_{resource}. */
    public static function canView($record): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        $permission = 'view_' . static::getPermissionSlug();
        return $user->can($permission);
    }

    /** Check permission: create_{resource}. */
    public static function canCreate(): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        $permission = 'create_' . static::getPermissionSlug();
        return $user->can($permission);
    }

    /** Check permission: update_{resource}. */
    public static function canEdit($record): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        $permission = 'update_' . static::getPermissionSlug();
        return $user->can($permission);
    }

    /** Check permission: delete_{resource}. */
    public static function canDelete($record): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        $permission = 'delete_' . static::getPermissionSlug();
        return $user->can($permission);
    }
}
