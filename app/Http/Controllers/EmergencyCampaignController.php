<?php

namespace App\Http\Controllers;

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
        $campaigns = \App\Models\EmergencyCampaign::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
            })
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('emergency-campaigns.index', compact('campaigns'));
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

        $donation = $this->donationService->donate($campaign, $validated, $ip);

        $campaign->refresh();

        return response()->json([
            'success' => true,
            'new_total' => $campaign->collected_amount,
            'progress_percent' => $campaign->progressPercent,
            'donor_count' => $campaign->donorCount,
            'donation' => [
                'id' => $donation->id,
                'donor_name' => $donation->donorDisplayName(),
                'amount' => $donation->amount,
                'currency' => $donation->currency,
                'message' => $donation->message,
                'created_at' => $donation->created_at->diffForHumans(),
                'country' => $donation->donor_country,
                'city' => $donation->donor_city,
                'latitude' => (float) $donation->donor_latitude,
                'longitude' => (float) $donation->donor_longitude,
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
                'message' => $d->message,
                'created_at' => $d->created_at->diffForHumans(),
                'donor_country' => $d->donor_country,
                'donor_city' => $d->donor_city,
                'donor_latitude' => (float) $d->donor_latitude,
                'donor_longitude' => (float) $d->donor_longitude,
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
