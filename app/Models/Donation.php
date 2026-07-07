<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Donation extends Model
{
    protected $fillable = [
        'donor_name', 'email', 'phone', 'amount', 'currency',
        'payment_method_id', 'transaction_id', 'status',
        'is_anonymous', 'is_recurring', 'recurring_interval',
        'project_id', 'post_id', 'story_id',
        'donated_at',
        'notes', 'locale',
        'stripe_subscription_id', 'paypal_billing_agreement_id',
    ];

    /**
     * Sensitive attributes that must never be leaked via serialization.
     *
     * access_token is the authorization secret for the public payment
     * endpoints — exposing it would re-open the IDOR vulnerability.
     */
    protected $hidden = [
        'access_token',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_anonymous' => 'boolean',
        'is_recurring' => 'boolean',
        'donated_at' => 'datetime',
        'confirmation_details' => 'array',
    ];

    /**
     * Auto-generate a cryptographically-strong access token on creation so the
     * donor can reach their payment pages without an enumerable ID being the
     * only thing protecting their data.
     */
    protected static function booted(): void
    {
        static::creating(function (self $donation) {
            if (empty($donation->access_token)) {
                do {
                    $donation->access_token = Str::random(64);
                } while (static::where('access_token', $donation->access_token)->exists());
            }
        });
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /** Get the payment gateway through the payment method. */
    public function gateway()
    {
        return $this->hasOneThrough(PaymentGateway::class, PaymentMethod::class, 'id', 'id', 'payment_method_id', 'gateway_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    /** Scope: only completed donations. */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /** Scope: only pending donations. */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /** Scope: latest donations first. */
    public function scopeLatest($query)
    {
        return $query->orderByDesc('created_at');
    }

    /** Scope: filter donations by payment gateway driver (stripe, paypal, etc.). */
    public function scopeByGateway($query, $driver)
    {
        return $query->whereHas('paymentMethod.gateway', fn ($q) => $q->where('driver', $driver));
    }


}
