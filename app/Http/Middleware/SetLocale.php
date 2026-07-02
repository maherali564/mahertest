<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /** Detect locale from route or session, set app locale, share locale data with all views. */
    public function handle(Request $request, Closure $next): Response
    {
        $supported = config('app.supported_locales', ['ar', 'en']);
        $locale = $request->route('locale') ?? session('locale', config('app.locale'));

        if (! in_array($locale, $supported, true)) {
            $locale = config('app.fallback_locale', 'en');
        }

        App::setLocale($locale);
        session(['locale' => $locale]);

        $allLabels = [
            'ar' => 'العربية',
            'en' => 'English',
            'es' => 'Español',
            'id' => 'Bahasa Indonesia',
            'tr' => 'Türkçe',
            'sv' => 'Svenska',
        ];
      

        View::share('currentLocale', $locale);
        View::share('supportedLocales', $supported);
        View::share('localeLabels', array_intersect_key($allLabels, array_flip($supported)));
        View::share('isRtl', $locale === 'ar');

        return $next($request);
    }
}
