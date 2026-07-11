<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class Volunteer extends Model
{
    use Notifiable;

    protected $fillable = [
        'access_token', 'name', 'email', 'phone', 'national_id', 'date_of_birth', 'address',
        'emergency_contact_name', 'emergency_contact_phone', 'id_photo',
        'skills', 'availability', 'message', 'notes',
        'status', 'locale', 'approved_at', 'rejected_at', 'reviewed_by',
        'volunteer_opportunity_id',
    ];

    protected $hidden = ['access_token'];

    protected static function booted(): void
    {
        static::creating(function (self $volunteer) {
            if (empty($volunteer->access_token)) {
                do {
                    $volunteer->access_token = hash_hmac('sha256', random_bytes(32), config('app.key'));
                } while (static::where('access_token', $volunteer->access_token)->exists());
            }
        });
    }

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
        ];
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(VolunteerTask::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(VolunteerOpportunity::class, 'volunteer_opportunity_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function totalHours(): float
    {
        return (float) $this->tasks()->sum('hours_logged');
    }
}
