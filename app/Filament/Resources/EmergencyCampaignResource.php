<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasLocaleTabs;
use App\Filament\Resources\EmergencyCampaignResource\Pages;
use App\Models\EmergencyCampaign;
use App\Services\MediaOptimizer;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmergencyCampaignResource extends Resource
{
    use HasLocaleTabs;
    use \App\Filament\Concerns\HasPermissionBasedAuthorization;

    protected static ?string $model = EmergencyCampaign::class;
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?string $navigationGroup = 'التبرعات';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make(__('filament.resources.emergency_campaign.section.campaign_details'))
                ->schema([
                    static::localeTabs('title', __('filament.resources.emergency_campaign.title')),
                    static::localeTabs('description', __('filament.resources.emergency_campaign.description'), 'textarea'),
                    static::localeTabs('excerpt', __('filament.resources.emergency_campaign.excerpt'), 'textarea'),
                ])->columns(1),

            Section::make(__('filament.resources.emergency_campaign.section.target_currency'))
                ->schema([
                    TextInput::make('target_amount')
                        ->numeric()
                        ->required()
                        ->minValue(1)
                        ->prefix('﷼'),
                    Select::make('currency')
                        ->options([
                            'USD' => '$ USD',
                            'EUR' => '€ EUR',
                        ])
                        ->default('USD')
                        ->native(false),
                    TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->helperText(__('filament.resources.emergency_campaign.slug')),
                ])->columns(3),

            Section::make(__('filament.resources.emergency_campaign.section.media'))
                ->schema([
                    FileUpload::make('image')
                        ->image()
                        ->directory('campaigns')
                        ->nullable()
                        ->saveUploadedFileUsing(MediaOptimizer::saveUploadedImage('campaigns')),
                    FileUpload::make('video')
                        ->directory('videos')
                        ->nullable()
                        ->maxSize(102400)
                        ->acceptedFileTypes(['video/mp4', 'video/webm']),
                ]),

            Section::make(__('filament.resources.emergency_campaign.section.status_schedule'))
                ->schema([
                    Toggle::make('is_active')
                        ->label(__('filament.resources.emergency_campaign.is_active'))
                        ->default(true),
                    Toggle::make('is_featured')
                        ->label(__('filament.resources.emergency_campaign.is_featured')),
                    DateTimePicker::make('starts_at')
                        ->label(__('filament.resources.emergency_campaign.starts_at'))
                        ->nullable(),
                    DateTimePicker::make('ends_at')
                        ->label(__('filament.resources.emergency_campaign.ends_at'))
                        ->nullable()
                        ->after('starts_at'),
                ])->columns(2),

            Section::make('الدولة المستهدفة')
                ->schema([
                    Select::make('target_country')
                        ->label('الدولة المستهدفة')
                        ->options([
                            'فلسطين' => 'فلسطين 🇵🇸',
                            'أمريكا' => 'أمريكا 🇺🇸',
                            'أوكرانيا' => 'أوكرانيا 🇺🇦',
                            'تركيا' => 'تركيا 🇹🇷',
                            'سوريا' => 'سوريا 🇸🇾',
                            'اليمن' => 'اليمن 🇾🇪',
                            'السودان' => 'السودان 🇸🇩',
                            'لبنان' => 'لبنان 🇱🇧',
                            'باكستان' => 'باكستان 🇵🇰',
                            'إندونيسيا' => 'إندونيسيا 🇮🇩',
                        ])
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, $set) {
                            $coordinates = [
                                'فلسطين' => ['lat' => 31.5, 'lng' => 34.5, 'code' => 'PS', 'flag' => '🇵🇸', 'loc' => 'فلسطين - غزة'],
                                'أمريكا' => ['lat' => 37.0, 'lng' => -95.0, 'code' => 'US', 'flag' => '🇺🇸', 'loc' => 'أمريكا'],
                                'أوكرانيا' => ['lat' => 48.3, 'lng' => 31.1, 'code' => 'UA', 'flag' => '🇺🇦', 'loc' => 'أوكرانيا'],
                                'تركيا' => ['lat' => 38.9, 'lng' => 35.2, 'code' => 'TR', 'flag' => '🇹🇷', 'loc' => 'تركيا'],
                                'سوريا' => ['lat' => 34.8, 'lng' => 39.0, 'code' => 'SY', 'flag' => '🇸🇾', 'loc' => 'سوريا'],
                                'اليمن' => ['lat' => 15.5, 'lng' => 48.0, 'code' => 'YE', 'flag' => '🇾🇪', 'loc' => 'اليمن'],
                                'السودان' => ['lat' => 15.5, 'lng' => 30.0, 'code' => 'SD', 'flag' => '🇸🇩', 'loc' => 'السودان'],
                                'لبنان' => ['lat' => 33.8, 'lng' => 35.8, 'code' => 'LB', 'flag' => '🇱🇧', 'loc' => 'لبنان'],
                                'باكستان' => ['lat' => 30.3, 'lng' => 69.3, 'code' => 'PK', 'flag' => '🇵🇰', 'loc' => 'باكستان'],
                                'إندونيسيا' => ['lat' => -0.7, 'lng' => 113.9, 'code' => 'ID', 'flag' => '🇮🇩', 'loc' => 'إندونيسيا'],
                            ];
                            $coords = $coordinates[$state] ?? $coordinates['فلسطين'];
                            $set('target_latitude', $coords['lat']);
                            $set('target_longitude', $coords['lng']);
                            $set('target_country_code', $coords['code']);
                            $set('target_flag', $coords['flag']);
                            $set('target_location', $coords['loc']);
                        }),
                    // Coordinates are computed server-side in the model's saving event
                    // (Hidden fields removed to prevent client-side manipulation)
                    TextInput::make('target_location')->label('الموقع')->nullable(),
                ])->columns(2),

            Section::make(__('filament.resources.emergency_campaign.section.statistics'))
                ->schema([
                    TextInput::make('collected_amount')
                        ->label(__('filament.resources.emergency_campaign.collected_amount'))
                        ->disabled()
                        ->dehydrated(false),
                ])->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('title')
                ->label(__('filament.resources.emergency_campaign.title'))
                ->searchable()
                ->formatStateUsing(fn ($record) => $record->getTranslation('title', app()->getLocale()))
                ->limit(40),
            TextColumn::make('target_amount')
                ->money('USD')
                ->sortable()
                ->label(__('filament.resources.emergency_campaign.target_amount')),
            TextColumn::make('collected_amount')
                ->money('USD')
                ->sortable()
                ->label(__('filament.resources.emergency_campaign.collected_amount')),
            TextColumn::make('progressPercent')
                ->label(__('filament.resources.emergency_campaign.column_progress'))
                ->formatStateUsing(fn ($state) => $state . '%')
                ->color(fn ($state) => $state >= 75 ? 'success' : ($state >= 50 ? 'warning' : 'danger'))
                ->badge(),
            TextColumn::make('donorCount')
                ->label(__('filament.resources.emergency_campaign.column_donors'))
                ->sortable(),
            IconColumn::make('is_active')
                ->boolean()
                ->label(__('filament.resources.emergency_campaign.is_active')),
            IconColumn::make('is_featured')
                ->boolean()
                ->label(__('filament.resources.emergency_campaign.is_featured')),
            TextColumn::make('ends_at')
                ->date()
                ->sortable()
                ->label(__('filament.resources.emergency_campaign.ends_at')),
        ])->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmergencyCampaigns::route('/'),
            'create' => Pages\CreateEmergencyCampaign::route('/create'),
            'edit' => Pages\EditEmergencyCampaign::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.emergency_campaign.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.emergency_campaign.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.emergency_campaign.plural_label');
    }
}
