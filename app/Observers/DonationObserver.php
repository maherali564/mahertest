<?php

namespace App\Observers;

use App\Models\Donation;
use App\Models\Project;
use App\Models\Story;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class DonationObserver
{
    public function creating(Donation $donation): void
    {
        if (empty($donation->transaction_id)) {
            $donation->transaction_id = 'TXN-'.Str::uuid();
        }
    }

    public function created(Donation $donation): void
    {
        if ($donation->status === 'completed') {
            try {
                static::updateRaisedAmount($donation);
            } catch (\Exception $e) {
                Log::error('Failed to update raised amount after donation created', [
                    'donation_id' => $donation->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    public function updated(Donation $donation): void
    {
        if ($donation->wasChanged('status') && $donation->status === 'completed') {
            try {
                static::updateRaisedAmount($donation);
            } catch (\Exception $e) {
                Log::error('Failed to update raised amount after donation updated', [
                    'donation_id' => $donation->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    public static function updateRaisedAmount(Donation $donation): void
    {
        if ($donation->project_id && Project::where('id', $donation->project_id)->exists()) {
            Project::where('id', $donation->project_id)->increment('raised_amount', $donation->amount);
        }

        if ($donation->story_id && Story::where('id', $donation->story_id)->exists()) {
            Story::where('id', $donation->story_id)->increment('raised_amount', $donation->amount);
        }
    }
}
