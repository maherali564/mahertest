<?php

namespace App\Filament\Widgets;

use App\Services\CloudflareAnalyticsService;
use Filament\Widgets\Widget;

class CloudflareAnalyticsWidget extends Widget
{
    protected static string $view = 'filament.widgets.cloudflare-analytics';

    protected int | string | array $columnSpan = 'full';

    public ?array $data = null;

    public function mount(CloudflareAnalyticsService $service): void
    {
        $this->data = $service->getSummary();
    }
}
