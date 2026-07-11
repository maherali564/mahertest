<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmergencyDonation extends Model
{
    protected $fillable = [
        'emergency_campaign_id', 'donor_name', 'donor_email',
        'amount', 'converted_amount', 'currency', 'payment_method', 'payment_status',
        'message', 'ip_address', 'user_agent',
        'donor_country', 'donor_city', 'donor_latitude', 'donor_longitude',
        'stripe_session_id', 'stripe_payment_intent_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'converted_amount' => 'decimal:2',
        'is_anonymous' => 'boolean',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(EmergencyCampaign::class, 'emergency_campaign_id');
    }

    public function donorDisplayName(): string
    {
        return $this->is_anonymous ? __('campaigns.anonymous_donor') : $this->donor_name;
    }

    public function getAvatarColorAttribute(): string
    {
        $colors = ['#C62828','#1565C0','#2E7D32','#6A1B9A','#E65100','#00838F','#AD1457','#4527A0'];
        $name = $this->donorDisplayName();
        $hash = crc32($name);
        return $colors[abs($hash) % count($colors)];
    }

    protected static function booted(): void
    {
        static::saved(function ($donation) {
            if ($donation->wasChanged('payment_status') && $donation->payment_status === 'completed') {
                $donation->campaign->update([
                    'collected_amount' => $donation->campaign->donations()
                        ->where('payment_status', 'completed')
                        ->sum('converted_amount'),
                ]);
            }
        });
    }
}
