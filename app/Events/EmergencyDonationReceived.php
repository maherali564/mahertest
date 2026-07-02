<?php

namespace App\Events;

use App\Models\EmergencyDonation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmergencyDonationReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public EmergencyDonation $donation,
        public float $newTotal,
        public int $donorCount
    ) {}

    public function broadcastOn(): Channel
    {
        return new Channel('emergency-campaign.' . $this->donation->emergency_campaign_id);
    }

    public function broadcastWith(): array
    {
        $campaign = $this->donation->campaign;

        return [
            'donation' => [
                'id' => $this->donation->id,
                'donor_name' => $this->donation->donorDisplayName(),
                'amount' => number_format($this->donation->amount, 2),
                'currency' => $this->donation->currency,
                'message' => $this->donation->message,
                'created_at' => $this->donation->created_at->diffForHumans(),
                'country' => $this->donation->donor_country,
                'city' => $this->donation->donor_city,
                'latitude' => (float) $this->donation->donor_latitude,
                'longitude' => (float) $this->donation->donor_longitude,
            ],
            'new_total' => number_format($this->newTotal, 2),
            'currency' => $this->donation->currency,
            'donor_count' => $this->donorCount,
            'progress_percent' => $campaign ? round($this->newTotal / max($campaign->target_amount, 1) * 100, 1) : 0,
        ];
    }
}
