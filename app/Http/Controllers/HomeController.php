<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\SiteSetting;
use App\Models\Slider;
use App\Models\Statistic;
use App\Models\Story;
use Illuminate\View\View;

class HomeController extends Controller
{
    /** Show the homepage with sliders, stats, projects, stories, etc. */
    public function index(): View
    {
        return view('home', [
            'settings' => SiteSetting::current(),
            'sliders' => Slider::active()->get(),
            'achievementStats' => Statistic::active()->ofType(Statistic::TYPE_ACHIEVEMENT)->get(),
            'humanitarianStats' => Statistic::active()->ofType(Statistic::TYPE_HUMANITARIAN)->get(),
            'projects' => Project::active()->get(),
            'stories' => Story::active()->get(),
        ]);
    }
}
