<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        Schema::defaultStringLength(191);

        RateLimiter::for('donations', fn (Request $request) => Limit::perMinute(10)->by($request->ip()));
        RateLimiter::for('contact', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));
        RateLimiter::for('login', fn (Request $request) => Limit::perMinute(5)->by($request->input('email').'|'.$request->ip()));
        RateLimiter::for('volunteer', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));

        $settings = null;
        try {
            $settings = \App\Models\SiteSetting::current();
            if ($settings && $locales = $settings->supported_locales) {
                config(['app.supported_locales' => $locales]);
            }
        } catch (\Throwable) {}

        view()->composer('*', fn ($view) => $view->with('settings', $settings));
    }
}
