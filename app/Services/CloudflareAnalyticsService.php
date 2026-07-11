<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CloudflareAnalyticsService
{
    protected string $apiToken;
    protected string $baseUrl = 'https://api.cloudflare.com/client/v4';

    public function __construct()
    {
        $this->apiToken = config('services.cloudflare.api_token');
    }

    protected function fetch(string $endpoint, array $query = []): array
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders(['Authorization' => "Bearer {$this->apiToken}"])
                ->get($this->baseUrl . $endpoint, $query);

            if ($response->successful()) {
                return $response->json('result', []);
            }

            Log::error('Cloudflare API error', [
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Exception $e) {
            Log::error('Cloudflare API exception', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
            ]);
            Cache::forget('cloudflare_analytics_summary');
        }

        return [];
    }

    protected function getZoneId(): ?string
    {
        return Cache::remember('cloudflare_zone_id', 3600, function () {
            $zones = $this->fetch('/zones', ['per_page' => 1, 'status' => 'active']);

            return $zones[0]['id'] ?? null;
        });
    }

    public function getSummary(): array
    {
        return Cache::remember('cloudflare_analytics_summary', 300, function () {
            $zoneId = $this->getZoneId();
            if (!$zoneId) {
                return $this->emptySummary();
            }

            $since = Carbon::now()->subDays(7)->toIso8601String();
            $until = Carbon::now()->toIso8601String();
            $data = $this->fetch("/zones/{$zoneId}/analytics/dashboard", [
                'since' => $since,
                'until' => $until,
                'continuous' => false,
            ]);

            $totals = $data['totals'] ?? [];

            return [
                'requests' => $totals['requests']['all'] ?? 0,
                'visits' => $totals['uniques']['all'] ?? 0,
                'bandwidth' => $totals['bandwidth']['all'] ?? 0,
                'cache' => $totals['requests']['cached'] ?? 0,
                'countries' => $this->parseCountries($totals),
                'paths' => [],
                'status_codes' => $this->parseStatusCodes($totals),
                'devices' => [],
            ];
        });
    }

    public function getTopCountries(int $limit = 5): array
    {
        return $this->getSummary()['countries'] ?? [];
    }

    public function getTopPaths(int $limit = 5): array
    {
        return [];
    }

    public function getStatusCodes(): array
    {
        return $this->getSummary()['status_codes'] ?? [
            '2xx' => 0, '3xx' => 0, '4xx' => 0, '5xx' => 0,
        ];
    }

    public function getDevices(): array
    {
        return [];
    }

    protected function parseCountries(array $totals): array
    {
        $countries = $totals['requests']['country'] ?? [];

        arsort($countries);

        return collect($countries)
            ->take(5)
            ->map(fn ($count, $code) => [
                'code' => $code,
                'requests' => $count,
            ])
            ->values()
            ->toArray();
    }

    protected function parseStatusCodes(array $totals): array
    {
        $codes = $totals['requests']['http_status'] ?? [];

        return [
            '2xx' => ($codes[200] ?? 0) + ($codes[201] ?? 0) + ($codes[204] ?? 0),
            '3xx' => ($codes[301] ?? 0) + ($codes[302] ?? 0) + ($codes[304] ?? 0) + ($codes[307] ?? 0) + ($codes[308] ?? 0),
            '4xx' => ($codes[400] ?? 0) + ($codes[401] ?? 0) + ($codes[403] ?? 0) + ($codes[404] ?? 0) + ($codes[405] ?? 0) + ($codes[408] ?? 0) + ($codes[429] ?? 0),
            '5xx' => ($codes[500] ?? 0) + ($codes[502] ?? 0) + ($codes[503] ?? 0) + ($codes[504] ?? 0),
        ];
    }

    protected function emptySummary(): array
    {
        return [
            'requests' => 0,
            'visits' => 0,
            'bandwidth' => 0,
            'cache' => 0,
            'countries' => [],
            'paths' => [],
            'status_codes' => ['2xx' => 0, '3xx' => 0, '4xx' => 0, '5xx' => 0],
            'devices' => [],
        ];
    }
}
