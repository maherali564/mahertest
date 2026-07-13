<?php

namespace App\Providers;

use App\Models\Program;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer(['partials.header', 'partials.footer', 'partials.top-bar'], function ($view) {
            $settings = Cache::remember('site_settings_current', 3600, fn() =>
                SiteSetting::current()
            );

            $navPrograms = Cache::remember('nav_programs', 3600, fn() =>
                Program::with(['projects' => fn($q) => $q->active()->orderBy('sort_order')])
                    ->active()
                    ->orderBy('sort_order')
                    ->get()
            );

            $view->with('settings', $settings);
            $view->with('navPrograms', $navPrograms);
        });
    }

    public function register(): void {}
}
