<?php

namespace App\Filament\Pages;

use App\Models\Donation;
use App\Models\Project;
use Carbon\Carbon;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class AdminReports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Reports';
    protected static ?string $title = 'Donation Reports';
    protected static ?string $slug = 'reports';

    protected static string $view = 'filament.pages.admin-reports';

    /** Restrict to users with view_donation permission or super_admin. */
    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_report')
            || auth()->user()?->can('view_donation')
            || auth()->user()?->hasRole('super_admin');
    }

    public ?string $dateFrom = null;
    public ?string $dateTo = null;
    public ?string $period = 'monthly';

    /** Initialize the date range to the current year. */
    public function mount(): void
    {
        $this->dateFrom = Carbon::now()->startOfYear()->format('Y-m-d');
        $this->dateTo = Carbon::now()->format('Y-m-d');
    }

    /** Get summary stats (total, donors, transactions, average) for the selected period. */
    public function getStats(): array
    {
        $query = Donation::completed()->whereBetween('created_at', [$this->dateFrom, $this->dateTo]);

        $total = $query->sum('amount');
        $donors = $query->distinct('email')->count('email');
        $count = $query->count();
        $avg = $count > 0 ? $total / $count : 0;

        return [
            (new \Filament\Widgets\StatsOverviewWidget\Stat('Total Donations', number_format($total, 0) . ' $', 'Completed donations in period'))->icon('heroicon-m-currency-dollar')->color('success'),
            (new \Filament\Widgets\StatsOverviewWidget\Stat('Donors', (string) $donors, 'Unique donors'))->icon('heroicon-m-user-group')->color('info'),
            (new \Filament\Widgets\StatsOverviewWidget\Stat('Transactions', (string) $count, 'Number of donations'))->icon('heroicon-m-shopping-cart')->color('warning'),
            (new \Filament\Widgets\StatsOverviewWidget\Stat('Average', number_format($avg, 2) . ' $', 'Per donation'))->icon('heroicon-m-calculator')->color('danger'),
        ];
    }

    /** Get chart data grouped by month or year for the selected period. */
    public function getChartData(): array
    {
        $allowedPeriods = ['monthly', 'yearly'];
        $period = in_array($this->period, $allowedPeriods, true) ? $this->period : 'monthly';

        $driver = DB::connection()->getDriverName();
        $dateExpr = $period === 'yearly'
            ? ($driver === 'pgsql' ? "TO_CHAR(created_at, 'YYYY')" : "strftime('%Y', created_at)")
            : ($driver === 'pgsql' ? "TO_CHAR(created_at, 'YYYY-MM')" : "strftime('%Y-%m', created_at)");

        $query = Donation::completed()
            ->whereBetween('created_at', [$this->dateFrom, $this->dateTo])
            ->select(
                DB::raw("$dateExpr as period"),
                DB::raw('SUM(amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        return [
            'labels' => $query->pluck('period')->toArray(),
            'amounts' => $query->pluck('total')->toArray(),
            'counts' => $query->pluck('count')->toArray(),
        ];
    }

    /** Get donation totals broken down by payment method. */
    public function getMethodBreakdown(): array
    {
        return Donation::completed()
            ->whereBetween('created_at', [$this->dateFrom, $this->dateTo])
            ->select('payment_method_id', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->with('paymentMethod')
            ->groupBy('payment_method_id')
            ->get()
            ->map(fn ($d) => [
                'method' => $d->paymentMethod?->name ?? 'Unknown',
                'total' => (float) $d->total,
                'count' => (int) $d->count,
            ])
            ->toArray();
    }

    /** Get donation totals broken down by project. */
    public function getProjectBreakdown(): array
    {
        return Donation::completed()
            ->whereBetween('created_at', [$this->dateFrom, $this->dateTo])
            ->whereNotNull('project_id')
            ->select('project_id', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->with('project')
            ->groupBy('project_id')
            ->get()
            ->map(fn ($d) => [
                'project' => $d->project ? trans_field($d->project, 'title') : 'Unknown',
                'total' => (float) $d->total,
                'count' => (int) $d->count,
            ])
            ->toArray();
    }

    protected function getForms(): array
    {
        return [];
    }
}
