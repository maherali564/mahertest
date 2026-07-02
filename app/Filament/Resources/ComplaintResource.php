<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComplaintResource\Pages;
use App\Models\Complaint;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ComplaintResource extends Resource
{
    protected static ?string $model = Complaint::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('view_any_complaint') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->disabled(),
            Forms\Components\TextInput::make('email')->disabled(),
            Forms\Components\TextInput::make('subject')->disabled(),
            Forms\Components\Textarea::make('description')->disabled()->columnSpanFull(),
            Forms\Components\Select::make('status')
                ->options([
                    'new' => __('complaints.status_new'),
                    'in_progress' => __('complaints.status_in_progress'),
                    'replied' => __('complaints.status_replied'),
                    'closed' => __('complaints.status_closed'),
                ])
                ->required(),
            Forms\Components\Textarea::make('admin_reply')->label(__('complaints.admin_reply'))->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('subject')->searchable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'new' => __('complaints.status_new'),
                        'in_progress' => __('complaints.status_in_progress'),
                        'replied' => __('complaints.status_replied'),
                        'closed' => __('complaints.status_closed'),
                    ]),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new' => __('complaints.status_new'),
                        'in_progress' => __('complaints.status_in_progress'),
                        'replied' => __('complaints.status_replied'),
                        'closed' => __('complaints.status_closed'),
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComplaints::route('/'),
            'view' => Pages\ViewComplaint::route('/{record}'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('complaints.navigation_label');
    }

    public static function getNavigationBadge(): ?string
    {
        return Complaint::where('status', 'new')->count() ?: null;
    }

    public static function getPluralLabel(): string
    {
        return __('complaints.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('complaints.navigation_label');
    }

    public static function getNavigationGroup(): string
    {
        return __('filament.nav.groups.messages');
    }
}
