<?php

namespace App\Http\Controllers;

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

        return view('home', [
            'settings' => $settings,
            'sliders' => $sliders,
            'achievementStats' => $achievementStats,
            'humanitarianStats' => $humanitarianStats,
            'projects' => Project::active()->take(6)->get(),
            'stories' => Story::active()->take(6)->get(),
            'latestPosts' => $latestPosts,
        ]);
    }
}
