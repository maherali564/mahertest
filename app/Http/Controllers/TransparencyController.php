<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Project;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class TransparencyController extends Controller
{
    /** Show the transparency page with financial breakdown. */
    public function index(string $locale): View
    {
        $totalRaised = Cache::remember('transparency_total_raised', 600, fn() =>
            Donation::completed()->sum('amount')
        );
        $totalDonations = Cache::remember('transparency_total_donations', 600, fn() =>
            Donation::completed()->count()
        );
        $totalDonors = Cache::remember('transparency_total_donors', 600, fn() =>
            Donation::completed()->distinct('email')->count('email')
        );

        $projectBreakdown = Cache::remember('transparency_project_breakdown', 600, fn() =>
            Project::active()->get()->map(fn ($p) => [
                'title' => $p->title,
                'raised' => $p->raised_amount,
                'goal' => $p->goal_amount,
                'percent' => $p->progressPercent(),
            ])
        );

        $adminCostRate = 5;

        return view('pages.transparency', compact(
            'totalRaised', 'totalDonations', 'totalDonors',
            'projectBreakdown', 'adminCostRate'
        ));
    }
}
