<?php

namespace App\Filament\Widgets;

use App\Models\EmergencyCampaign;
use App\Models\EmergencyDonation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EmergencyCampaignStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $activeCampaigns = EmergencyCampaign::where('is_active', true)->count();
        $totalDonations = EmergencyDonation::where('payment_status', 'completed')->sum('amount');
        $totalDonors = EmergencyDonation::where('payment_status', 'completed')->distinct('donor_email')->count('donor_email');
        $topCampaign = EmergencyCampaign::orderBy('collected_amount', 'desc')->first();

        return [
            Stat::make('Active Campaigns', $activeCampaigns)
                ->description('Currently active emergency campaigns')
                ->icon('heroicon-o-exclamation-triangle'),
            Stat::make('Total Donations', '$' . number_format($totalDonations, 2))
                ->description('Across all campaigns')
                ->icon('heroicon-o-currency-dollar'),
            Stat::make('Total Donors', $totalDonors)
                ->description('Unique donor emails')
                ->icon('heroicon-o-users'),
            Stat::make('Top Campaign', $topCampaign?->getTranslation('title', 'ar') ?? 'N/A')
                ->description('$' . number_format($topCampaign?->collected_amount ?? 0, 2) . ' collected')
                ->icon('heroicon-o-trophy'),
        ];
    }
}
