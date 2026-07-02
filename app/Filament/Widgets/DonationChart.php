<?php

namespace App\Filament\Widgets;

use App\Models\Donation;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class DonationChart extends ChartWidget
{
    /** Only show to users with donation view permission. */
    public static function canView(): bool
    {
        return auth()->user()?->can('view_any_donation') ?? false;
    }

    /** Get the translated heading for the chart. */
    public function getHeading(): ?string
    {
        return __('filament.widgets.donation_chart.heading');
    }

    /** Get monthly donation counts for the current year as chart data. */
    protected function getData(): array
    {
        $data = Trend::model(Donation::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => __('filament.widgets.donation_chart.label'),
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#0d6b4f',
                    'borderColor' => '#0d6b4f',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    /** Chart type: line chart. */
    protected function getType(): string
    {
        return 'line';
    }
}
