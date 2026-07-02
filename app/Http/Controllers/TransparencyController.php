<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Project;
use Illuminate\View\View;

class TransparencyController extends Controller
{
    /** Show the transparency page with financial breakdown. */
    public function index(string $locale): View
    {
        $totalRaised = Donation::completed()->sum('amount');
        $totalDonations = Donation::completed()->count();
        $totalDonors = Donation::completed()->distinct('email')->count('email');

        $projectBreakdown = Project::active()->get()->map(fn ($p) => [
            'title' => $p->title,
            'raised' => $p->raised_amount,
            'goal' => $p->goal_amount,
            'percent' => $p->progressPercent(),
        ]);

        $adminCostRate = 5; // 5% administrative costs

        return view('pages.transparency', compact(
            'totalRaised', 'totalDonations', 'totalDonors',
            'projectBreakdown', 'adminCostRate'
        ));
    }
}
