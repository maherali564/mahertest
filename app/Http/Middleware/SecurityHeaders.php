<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /** Add security headers (CSP, X-Frame-Options, HSTS, etc.) to every response. */
    public function handle(Request $request, Closure $next): Response
    {
        $nonce = base64_encode(random_bytes(18));

        $request->attributes->set('csp_nonce', $nonce);
        View::share('cspNonce', $nonce);

        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=(), payment=()');

        $reverbHost = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';
        $csp = "default-src 'self'; script-src 'self' 'nonce-{$nonce}' https://cdn.jsdelivr.net https://www.googletagmanager.com https://www.google-analytics.com https://cdnjs.cloudflare.com https://unpkg.com; script-src-attr 'unsafe-inline'; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com https://cdnjs.cloudflare.com https://unpkg.com; img-src 'self' data: https:; font-src 'self' data: https://fonts.gstatic.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; connect-src 'self' https://*.{$reverbHost} wss://*.{$reverbHost} ws://127.0.0.1 wss://127.0.0.1 ws://127.0.0.1:8080 wss://127.0.0.1:8080 https://cdn.jsdelivr.net https://unpkg.com https://cdnjs.cloudflare.com; frame-src 'self' https://www.youtube.com https://player.vimeo.com; form-action 'self';";
        $response->headers->set('Content-Security-Policy', $csp);

        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        $response->headers->remove('X-Powered-By');

        return $response;
    }
}
