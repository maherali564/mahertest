<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Partner;
use App\Models\Statistic;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class AboutController extends Controller
{
    /** Show the about page with stats, achievements, and partners. */
    public function index(string $locale): View
    {
        $totalDonations = Cache::remember('about_total_donations', 600, fn() =>
            Donation::completed()->count()
        );
        $totalRaised = Cache::remember('about_total_raised', 600, fn() =>
            Donation::completed()->sum('amount')
        );
        $totalDonors = Cache::remember('about_total_donors', 600, fn() =>
            Donation::completed()->distinct('email')->count('email')
        );
        $achievementStats = Cache::remember('about_achievement_stats', 3600, fn() =>
            Statistic::active()->ofType(Statistic::TYPE_ACHIEVEMENT)->get()
        );
        $partners = Cache::remember('about_partners', 3600, fn() =>
            Partner::active()->get()
        );

        return view('pages.about', compact(
            'totalDonations', 'totalRaised', 'totalDonors', 'achievementStats', 'partners'
        ));
    }
}
