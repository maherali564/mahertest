<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(60)->by($request->ip()));

        $settings = null;
        try {
            $settings = \App\Models\SiteSetting::current();
            if ($settings && $locales = $settings->supported_locales) {
                config(['app.supported_locales' => $locales]);
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to load site settings', ['error' => $e->getMessage()]);
        }

        view()->composer('*', function ($view) use ($settings) {
            $view->with('settings', $settings);
            $locale = App::getLocale();
            if (!$view->offsetExists('currentLocale')) {
                $view->with('currentLocale', $locale);
            }
            if (!$view->offsetExists('isRtl')) {
                $view->with('isRtl', $locale === 'ar');
            }
            if (!$view->offsetExists('cspNonce')) {
                $request = request();
                $nonce = $request ? $request->attributes->get('csp_nonce') : null;
                $view->with('cspNonce', $nonce ?? Str::random(32));
            }
            if (!$view->offsetExists('supportedLocales')) {
                $view->with('supportedLocales', config('app.supported_locales', ['ar', 'en']));
            }
            if (!$view->offsetExists('localeLabels')) {
                $allLabels = [
                    'ar' => 'العربية',
                    'en' => 'English',
                    'es' => 'Español',
                    'id' => 'Bahasa Indonesia',
                    'tr' => 'Türkçe',
                    'sv' => 'Svenska',
                ];
                $supported = config('app.supported_locales', ['ar', 'en']);
                $view->with('localeLabels', array_intersect_key($allLabels, array_flip($supported)));
            }
        });

        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_START,
            fn () => '<style>.fi-section-header-heading { text-align: center; }</style>',
        );
    }
}
