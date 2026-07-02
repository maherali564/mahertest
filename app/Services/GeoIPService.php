<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GeoIPService
{
    public function getLocation(string $ip): array
    {
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost'])) {
            return $this->defaultLocation();
        }

        return Cache::remember("geoip_{$ip}", 3600, function () use ($ip) {
            try {
                $response = Http::timeout(5)->get("https://ipapi.co/{$ip}/json/");
                if ($response->successful() && !isset($response['error'])) {
                    return [
                        'country' => $response['country_name'] ?? 'غير معروف',
                        'city' => $response['city'] ?? 'غير معروف',
                        'latitude' => $response['latitude'] ?? 0,
                        'longitude' => $response['longitude'] ?? 0,
                    ];
                }
            } catch (\Exception $e) {
                \Log::error('GeoIP Error: ' . $e->getMessage());
            }
            return $this->defaultLocation();
        });
    }

    private function defaultLocation(): array
    {
        return ['country' => 'مصر', 'city' => 'القاهرة', 'latitude' => 30.0444, 'longitude' => 31.2357];
    }
}
