<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeoIPService
{
    /**
     * Resolve location from an IP address.
     *
     * Uses ipapi.co as primary with ip-api.com as fallback.
     * Results are cached for 24 hours to stay within free-tier rate limits.
     */
    public function getLocation(string $ip): array
    {
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost'])) {
            return $this->defaultLocation();
        }

        return Cache::remember("geoip_{$ip}", 86400, function () use ($ip) {
            // Primary: ipapi.co (1000 free requests/day)
            $location = $this->fetchIpApiCo($ip);
            if ($location !== null) return $location;

            // Fallback: ip-api.com (45 requests/minute, free for non-commercial)
            $location = $this->fetchIpApiCom($ip);
            if ($location !== null) return $location;

            Log::warning('GeoIP all providers failed', ['ip' => $ip]);
            return $this->defaultLocation();
        });
    }

    private function fetchIpApiCo(string $ip): ?array
    {
        try {
            $response = Http::timeout(5)->get("https://ipapi.co/{$ip}/json/");
            if ($response->successful() && !isset($response['error'])) {
                return [
                    'country' => $response['country_name'] ?? null,
                    'city' => $response['city'] ?? null,
                    'latitude' => $response['latitude'] ?? null,
                    'longitude' => $response['longitude'] ?? null,
                ];
            }
        } catch (\Exception $e) {
            Log::warning('GeoIP ipapi.co failed', ['ip' => $ip, 'error' => $e->getMessage()]);
        }
        return null;
    }

    private function fetchIpApiCom(string $ip): ?array
    {
        try {
            $response = Http::timeout(5)->get("https://ip-api.com/json/{$ip}", [
                'fields' => 'country,city,lat,lon,status',
            ]);
            if ($response->successful() && ($response['status'] ?? '') === 'success') {
                return [
                    'country' => $response['country'] ?? null,
                    'city' => $response['city'] ?? null,
                    'latitude' => $response['lat'] ?? null,
                    'longitude' => $response['lon'] ?? null,
                ];
            }
        } catch (\Exception $e) {
            Log::warning('GeoIP ip-api.com failed', ['ip' => $ip, 'error' => $e->getMessage()]);
        }
        return null;
    }

    private function defaultLocation(): array
    {
        return [
            'country' => null,
            'city' => null,
            'latitude' => null,
            'longitude' => null,
        ];
    }
}
