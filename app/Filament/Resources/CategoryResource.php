<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasLocaleTabs;
use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    use HasLocaleTabs;
    use \App\Filament\Concerns\HasPermissionBasedAuthorization;

    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        return $form->schema([
            static::localeTabs('name', __('filament.resources.category.name')),
            static::localeTabs('description', __('filament.resources.category.description'), 'textarea'),
            TextInput::make('slug')
                ->label(__('filament.resources.category.slug'))
                ->required()
                ->unique(ignoreRecord: true),
            TextInput::make('color')
                ->label(__('filament.resources.category.color'))
                ->type('color')
                ->default('#4A90D9'),
            Select::make('parent_id')
                ->label(__('filament.resources.category.parent'))
                ->relationship('parent', 'slug', fn ($query) => $query->reorder()->orderBy('id'))
                ->getOptionLabelFromRecordUsing(fn (Category $record) => $record->getTranslation('name', app()->getLocale()) ?: $record->getTranslation('name', 'ar'))
                ->searchable()
                ->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')
                ->label(__('filament.resources.category.name'))
                ->formatStateUsing(fn ($state, $record) => $record?->getTranslation('name', 'ar') ?? ''),
            Tables\Columns\TextColumn::make('slug')
                ->label(__('filament.resources.category.slug'))
                ->searchable(),
            Tables\Columns\ColorColumn::make('color')
                ->label(__('filament.resources.category.color')),
            Tables\Columns\TextColumn::make('posts_count')
                ->label(__('filament.resources.category.posts_count'))
                ->counts('posts'),
        ])->defaultSort('id')
            ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.category.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.category.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.category.plural_label');
    }

    public static function getNavigationGroup(): string
    {
        return __('filament.nav.groups.content');
    }
}
