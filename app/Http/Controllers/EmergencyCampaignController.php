<?php

namespace App\Http\Controllers;

use App\Models\EmergencyDonation;
use App\Services\EmergencyDonationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmergencyCampaignController extends Controller
{
    public function __construct(
        protected EmergencyDonationService $donationService
    ) {}

    public function index(?string $locale = null): View
    {
        $activeCampaigns = \App\Models\EmergencyCampaign::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
            })
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $completedCampaigns = \App\Models\EmergencyCampaign::where(function ($q) {
                $q->where('is_active', false)
                  ->orWhere(function ($q2) {
                      $q2->whereNotNull('ends_at')->where('ends_at', '<=', now());
                  });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('emergency-campaigns.index', compact('activeCampaigns', 'completedCampaigns'));
    }

    public function show(?string $locale = null, string $slug): View
    {
        $campaign = \App\Models\EmergencyCampaign::where('slug', $slug)
            ->with('donations')
            ->firstOrFail();

        $recentDonations = $campaign->donations()
            ->where('payment_status', 'completed')
            ->latest()
            ->take(50)
            ->get();

        return view('emergency-campaigns.show', compact('campaign', 'recentDonations'));
    }

    public function donate(?string $locale = null, Request $request, \App\Models\EmergencyCampaign $campaign)
    {
        $validated = $request->validate([
            'donor_name' => 'required|string|max:100',
            'donor_email' => 'required|email|max:255',
            'amount' => 'required|numeric|min:1|max:100000',
            'currency' => 'required|in:USD,EUR',
            'message' => 'nullable|string|max:500',
        ]);

        $ip = $request->header('CF-Connecting-IP')
            ?? $request->header('X-Forwarded-For')
            ?? $request->ip();

        $result = $this->donationService->donate($campaign, $validated, $ip);

        $donation = $result['donation'];
        $checkoutUrl = $result['checkout_url'];

        if ($checkoutUrl) {
            return response()->json([
                'success' => true,
                'checkout_url' => $checkoutUrl,
                'donation_id' => $donation->id,
                'message' => __('campaigns.redirecting_to_payment'),
            ]);
        }

        $campaign->refresh();
        $newTotal = EmergencyDonation::where('emergency_campaign_id', $campaign->id)
            ->where('payment_status', 'completed')
            ->sum('converted_amount');
        $donorCount = EmergencyDonation::where('emergency_campaign_id', $campaign->id)
            ->where('payment_status', 'completed')
            ->distinct('donor_email')
            ->count('donor_email');

        broadcast(new \App\Events\EmergencyDonationReceived($donation, $newTotal, $donorCount));

        return response()->json([
            'success' => true,
            'new_total' => $newTotal,
            'progress_percent' => $campaign->progressPercent,
            'donor_count' => $donorCount,
            'donation' => [
                'id' => $donation->id,
                'donor_name' => $donation->donorDisplayName(),
                'amount' => $donation->amount,
                'currency' => $donation->currency,
                'message' => $donation->is_anonymous ? null : $donation->message,
                'created_at' => $donation->created_at->diffForHumans(),
                'country' => $donation->donor_country,
                'city' => $donation->donor_city,
            ],
        ]);
    }

    public function donations(?string $locale = null, \App\Models\EmergencyCampaign $campaign)
    {
        $donations = $campaign->donations()
            ->where('payment_status', 'completed')
            ->latest()
            ->take(50)
            ->get()
            ->map(fn ($d) => [
                'id' => $d->id,
                'donor_name' => $d->donorDisplayName(),
                'amount' => $d->amount,
                'currency' => $d->currency,
                'message' => $d->is_anonymous ? null : $d->message,
                'created_at' => $d->created_at->diffForHumans(),
                'donor_country' => $d->donor_country,
                'donor_city' => $d->donor_city,
            ]);

        return response()->json($donations);
    }

    public function stats(?string $locale = null, \App\Models\EmergencyCampaign $campaign)
    {
        return response()->json([
            'collected_amount' => $campaign->collected_amount,
            'target_amount' => $campaign->target_amount,
            'currency' => $campaign->currency,
            'progress_percent' => $campaign->progressPercent,
            'donor_count' => $campaign->donorCount,
            'remaining_days' => $campaign->remainingDays(),
        ]);
    }
}
