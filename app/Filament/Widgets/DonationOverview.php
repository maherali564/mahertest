<?php

namespace App\Filament\Widgets;

use App\Models\Donation;
use App\Models\User;
use App\Models\Volunteer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DonationOverview extends BaseWidget
{
    /** Only show to users with donation view permission. */
    public static function canView(): bool
    {
        return auth()->user()?->can('view_any_donation') ?? false;
    }

    /** Show aggregate stats: total donations, donors, users, and volunteers. */
    protected function getStats(): array
    {
        return [
            Stat::make(__('filament.widgets.donation_overview.total_donations'), Donation::completed()->sum('amount').' $')
                ->description(__('filament.widgets.donation_overview.since_start'))
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
            Stat::make(__('filament.widgets.donation_overview.total_donors'), Donation::completed()->distinct('email')->count('email'))
                ->description(__('filament.widgets.donation_overview.donors_count'))
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
            Stat::make(__('filament.widgets.donation_overview.total_users'), User::count())
                ->description(__('filament.widgets.donation_overview.users_count'))
                ->descriptionIcon('heroicon-m-users')
                ->color('warning'),
            Stat::make(__('filament.widgets.donation_overview.total_volunteers'), Volunteer::count())
                ->description(__('filament.widgets.donation_overview.volunteers_count'))
                ->descriptionIcon('heroicon-m-hand-raised')
                ->color('danger'),
        ];
    }
}
