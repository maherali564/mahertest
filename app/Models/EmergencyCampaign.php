<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
class EmergencyCampaign extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $fillable = [
        'title', 'description',
        'target_amount', 'currency', 'collected_amount', 'image', 'video', 'slug',
        'is_active', 'is_featured', 'starts_at', 'ends_at',
        'target_country', 'target_country_code', 'target_flag',
        'target_latitude', 'target_longitude', 'target_location',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'collected_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'target_latitude' => 'decimal:7',
        'target_longitude' => 'decimal:7',
    ];

    public array $translatable = ['title', 'description'];

    protected static function booted(): void
    {
        static::creating(function ($campaign) {
            if (empty($campaign->slug)) {
                $campaign->slug = \Illuminate\Support\Str::slug($campaign->getTranslation('title', 'ar') ?: 'campaign-' . now()->timestamp);
            }
        });
    }

    public function donations(): HasMany
    {
        return $this->hasMany(EmergencyDonation::class);
    }

    public function getProgressPercentAttribute(): float
    {
        return $this->target_amount > 0
            ? round($this->collected_amount / $this->target_amount * 100, 1)
            : 0;
    }

    public function getDonorCountAttribute(): int
    {
        return $this->donations()->count();
    }

    public function remainingDays(): ?int
    {
        return $this->ends_at ? max(0, now()->diffInDays($this->ends_at, false)) : null;
    }

    public function isActive(): bool
    {
        return $this->is_active && ($this->ends_at === null || now()->lt($this->ends_at));
    }
}
