<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'role',
        'is_active',
        'avatar',
        'preferred_locale',
        'phone',
        'is_online',
        'last_seen_at',
        'can_chat',
    ];

    protected $attributes = [
        'role' => 'user',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
            'is_online' => 'boolean',
            'can_chat' => 'boolean',
            'last_seen_at' => 'datetime',
        ];
    }

    /** Check whether the user can access the Filament admin panel. */
    public function canAccessPanel(Panel $panel): bool
    {
        if (!$this->is_active) return false;
        return $this->is_admin || $this->roles()->exists();
    }

    /** Check if user has super_admin role or flag. */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin' || $this->is_admin || $this->hasRole('super_admin');
    }

    /** Check if user has admin-level access (super_admin or admin). */
    public function isAdmin(): bool
    {
        return $this->isSuperAdmin() || $this->hasRole('admin');
    }

    /** Check if user has editor-level access. */
    public function isEditor(): bool
    {
        return $this->isAdmin() || $this->hasRole('editor');
    }

    /** Check if user has supporter-level access. */
    public function isSupporter(): bool
    {
        return $this->isAdmin() || $this->hasRole('supporter');
    }
}
