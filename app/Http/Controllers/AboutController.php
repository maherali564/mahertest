<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Partner;
use App\Models\Statistic;
use Illuminate\View\View;

class AboutController extends Controller
{
    /** Show the about page with stats, achievements, and partners. */
    public function index(string $locale): View
    {
        $totalDonations = Donation::completed()->count();
        $totalRaised = Donation::completed()->sum('amount');
        $totalDonors = Donation::completed()->distinct('email')->count('email');
        $achievementStats = Statistic::active()->ofType(Statistic::TYPE_ACHIEVEMENT)->get();
        $partners = Partner::active()->get();

        return view('pages.about', compact(
            'totalDonations', 'totalRaised', 'totalDonors', 'achievementStats', 'partners'
        ));
    }
}
