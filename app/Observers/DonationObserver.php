<?php

namespace App\Observers;

use App\Models\Donation;
use App\Models\Project;
use App\Models\Story;

class DonationObserver
{
    public function creating(Donation $donation): void
    {
        if (empty($donation->transaction_id)) {
            $donation->transaction_id = 'TXN-'.strtoupper(uniqid());
        }
    }

    public function created(Donation $donation): void
    {
        if ($donation->status === 'completed') {
            static::updateRaisedAmount($donation);
        }
    }

    public function updated(Donation $donation): void
    {
        if ($donation->wasChanged('status') && $donation->status === 'completed') {
            static::updateRaisedAmount($donation);
        }
    }

    public static function updateRaisedAmount(Donation $donation): void
    {
        if ($donation->project_id) {
            Project::where('id', $donation->project_id)->increment('raised_amount', $donation->amount);
        }
        if ($donation->story_id) {
            Story::where('id', $donation->story_id)->increment('raised_amount', $donation->amount);
        }
    }
}
