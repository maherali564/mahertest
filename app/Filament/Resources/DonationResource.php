<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonationResource\Pages;
use App\Models\Donation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DonationResource extends Resource
{
    use \App\Filament\Concerns\HasPermissionBasedAuthorization;
    protected static ?string $model = Donation::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make(__('filament.resources.donation.section.donor'))->schema([
                Forms\Components\TextInput::make('donor_name')->label(__('filament.resources.donation.column_donor')),
                Forms\Components\TextInput::make('email')->label(__('filament.resources.newsletter.column_email')),
                Forms\Components\TextInput::make('phone')->label(__('filament.resources.volunteer.column_phone')),
            ])->columns(3),

            Forms\Components\Section::make(__('filament.resources.donation.section.donation'))->schema([
                Forms\Components\TextInput::make('amount')->label(__('filament.widgets.latest_donations.column_amount'))->numeric(),
                Forms\Components\TextInput::make('currency')->label(__('filament.resources.payment_confirmation.currency')),
                Forms\Components\Select::make('payment_method_id')->label(__('filament.resources.donation.column_method'))->relationship('paymentMethod', 'name'),
                Forms\Components\TextInput::make('transaction_id')->label(__('filament.resources.donation.transaction_id')),
                Forms\Components\Select::make('status')->label(__('filament.widgets.latest_donations.column_status'))->options([
                    'pending' => __('filament.resources.donation.status_pending'),
                    'completed' => __('filament.resources.donation.status_completed'),
                    'failed' => __('filament.resources.donation.status_failed'),
                    'cancelled' => __('filament.resources.donation_submission.status_cancelled'),
                ])->required(),
                Forms\Components\Toggle::make('is_anonymous')->label(__('filament.resources.donation.is_anonymous')),
                Forms\Components\Toggle::make('is_recurring')->label(__('filament.resources.donation.is_recurring')),
                Forms\Components\TextInput::make('recurring_interval')->label(__('filament.resources.donation.recurring_interval')),
            ])->columns(2),

            Forms\Components\Section::make(__('filament.resources.donation.section.relations'))->schema([
                Forms\Components\Select::make('project_id')->label(__('filament.resources.donation.column_project'))->relationship('project', 'title')->nullable()->searchable(),
                Forms\Components\Select::make('story_id')->label(__('filament.resources.donation.column_story'))->relationship('story', 'title')->nullable()->searchable(),
            ])->columns(2),

            Forms\Components\Section::make(__('filament.resources.donation.section.extra'))->schema([
                Forms\Components\DateTimePicker::make('donated_at')->label(__('filament.resources.donation.filter_date')),
                Forms\Components\Textarea::make('notes')->label(__('filament.resources.payment_confirmation.notes')),
                Forms\Components\TextInput::make('locale')->label(__('filament.resources.contact_submission.column_locale')),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('donor_name')->label(__('filament.resources.donation.column_donor'))->searchable(),
            Tables\Columns\TextColumn::make('email')->label(__('filament.resources.newsletter.column_email'))->searchable(),
            Tables\Columns\TextColumn::make('amount')->label(__('filament.widgets.latest_donations.column_amount'))->money('USD')->sortable(),
            Tables\Columns\TextColumn::make('paymentMethod.name')->label(__('filament.resources.donation.column_method')),
            Tables\Columns\TextColumn::make('paymentMethod.gateway.name')->label(__('filament.resources.donation.filter_gateway'))->badge()->color('info'),
            Tables\Columns\TextColumn::make('paymentMethod.gateway.driver')->label(__('filament.resources.donation.column_driver'))->badge()->color(fn ($state) => match ($state) {
                'paypal' => 'info',
                'stripe' => 'success',
                default => 'gray',
            })->toggleable(),
            Tables\Columns\SelectColumn::make('status')->label(__('filament.widgets.latest_donations.column_status'))->options([
                'pending' => __('filament.resources.donation.status_pending'),
                'completed' => __('filament.resources.donation.status_completed'),
                'failed' => __('filament.resources.donation.status_failed'),
                'cancelled' => __('filament.resources.donation_submission.status_cancelled'),
            ]),
            Tables\Columns\TextColumn::make('project.title')->label(__('filament.resources.donation.column_project'))->formatStateUsing(fn ($state) => \Illuminate\Support\Str::limit($state, 20))->toggleable(),
            Tables\Columns\TextColumn::make('story.title')->label(__('filament.resources.donation.column_story'))->formatStateUsing(fn ($state) => \Illuminate\Support\Str::limit($state, 20))->toggleable(),

            Tables\Columns\TextColumn::make('donated_at')->label(__('filament.resources.donation.filter_date'))->dateTime()->sortable()->toggleable(),
            Tables\Columns\TextColumn::make('created_at')->label(__('filament.resources.donation.column_created'))->dateTime()->sortable(),
        ])->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->label(__('filament.widgets.latest_donations.column_status'))->options([
                    'pending' => __('filament.resources.donation.status_pending'),
                    'completed' => __('filament.resources.donation.status_completed'),
                    'failed' => __('filament.resources.donation.status_failed'),
                    'cancelled' => __('filament.resources.donation_submission.status_cancelled'),
                ]),
                Tables\Filters\SelectFilter::make('driver')->label(__('filament.resources.donation.filter_gateway'))
                    ->relationship('paymentMethod.gateway', 'driver')
                    ->options([
                        'stripe' => __('filament.resources.payment_gateway.driver_stripe'),
                        'paypal' => __('filament.resources.payment_gateway.driver_paypal'),
                    ]),
                Tables\Filters\Filter::make('created_at')->label(__('filament.resources.donation.filter_date'))
                    ->form([Forms\Components\DatePicker::make('from'), Forms\Components\DatePicker::make('until')])
                    ->query(fn ($query, array $data) => $query->when($data['from'], fn ($q, $d) => $q->whereDate('created_at', '>=', $d))->when($data['until'], fn ($q, $d) => $q->whereDate('created_at', '<=', $d))),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->label(__('filament.resources.donation.action_delete')),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export_csv')->label(__('filament.resources.donation.export_csv'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function () {
                        return static::streamCsv('donations-', Donation::query()->orderByDesc('created_at')->get());
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('export_selected_csv')->label(__('filament.resources.donation.export_selected'))
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('info')
                        ->action(function ($records) {
                            return static::streamCsv('donations-selected-', $records);
                        }),
                ]),
            ]);
    }

    /** Stream a CSV file response with BOM for Excel compatibility. */
    protected static function streamCsv(string $prefix, $donations)
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $prefix . now()->format('Y-m-d') . '.csv"',
        ];
        $callback = function () use ($donations) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, [__('filament.resources.donation.column_donor'), __('filament.resources.newsletter.column_email'), __('filament.widgets.latest_donations.column_amount'), __('filament.resources.donation.column_method'), __('filament.widgets.latest_donations.column_status'), __('filament.resources.donation.filter_date')]);
            foreach ($donations as $d) {
                fputcsv($file, [$d->donor_name, $d->email, number_format($d->amount, 2), $d->paymentMethod?->name ?? '—', $d->status, $d->created_at->format('Y-m-d H:i')]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDonations::route('/'),
            'edit' => Pages\EditDonation::route('/{record}/edit'),
            'view' => Pages\ViewDonation::route('/{record}'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.donation.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.donation.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.donation.plural_label');
    }

    public static function getNavigationGroup(): string
    {
        return __('filament.nav.groups.donations');
    }
}
