<?php

namespace App\Http\Controllers;

use App\Models\EmergencyCampaign;
use App\Models\Post;
use App\Models\Project;
use App\Models\SiteSetting;
use App\Models\Slider;
use App\Models\Statistic;
use App\Models\Story;
use App\Services\BlogService;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(protected BlogService $blogService) {}

    public function index(): View
    {
        $settings = Cache::remember('home_settings', 3600, fn() => SiteSetting::current());
        $sliders = Cache::remember('home_sliders', 3600, fn() => Slider::active()->get());
        $achievementStats = Cache::remember('home_achievement_stats', 3600, fn() =>
            Statistic::active()->ofType(Statistic::TYPE_ACHIEVEMENT)->get()
        );
        $humanitarianStats = Cache::remember('home_humanitarian_stats', 3600, fn() =>
            Statistic::active()->ofType(Statistic::TYPE_HUMANITARIAN)->get()
        );
        $latestPosts = $this->blogService->getLatestPosts(3);

        $emergencyCampaigns = Cache::remember('home_emergency_campaigns', 600, fn() =>
            EmergencyCampaign::where('is_active', true)
                ->where(fn($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>', now()))
                ->orderBy('is_featured', 'desc')
                ->orderBy('created_at', 'desc')
                ->get()
        );

        $projects = Cache::remember('home_projects', 3600, fn() =>
            Project::active()->take(6)->get()
        );

        $stories = Cache::remember('home_stories', 3600, fn() =>
            Story::active()->take(6)->get()
        );

        return view('home', [
            'settings' => $settings,
            'sliders' => $sliders,
            'achievementStats' => $achievementStats,
            'humanitarianStats' => $humanitarianStats,
            'emergencyCampaigns' => $emergencyCampaigns,
            'projects' => $projects,
            'stories' => $stories,
            'latestPosts' => $latestPosts,
        ]);
    }
}
