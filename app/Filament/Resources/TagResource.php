<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasLocaleTabs;
use App\Filament\Resources\TagResource\Pages;
use App\Models\Tag;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TagResource extends Resource
{
    use HasLocaleTabs;
    use \App\Filament\Concerns\HasPermissionBasedAuthorization;

    protected static ?string $model = Tag::class;

    protected static ?string $navigationIcon = 'heroicon-o-hashtag';

    public static function form(Form $form): Form
    {
        return $form->schema([
            static::localeTabs('name', __('filament.resources.tag.name')),
            TextInput::make('slug')
                ->label(__('filament.resources.tag.slug'))
                ->required()
                ->unique(ignoreRecord: true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')
                ->label(__('filament.resources.tag.name'))
                ->formatStateUsing(fn ($state, $record) => $record?->getTranslation('name', 'ar') ?? ''),
            Tables\Columns\TextColumn::make('slug')
                ->label(__('filament.resources.tag.slug'))
                ->searchable(),
            Tables\Columns\TextColumn::make('posts_count')
                ->label(__('filament.resources.tag.posts_count'))
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
            'index' => Pages\ListTags::route('/'),
            'create' => Pages\CreateTag::route('/create'),
            'edit' => Pages\EditTag::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.tag.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.tag.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.tag.plural_label');
    }

    public static function getNavigationGroup(): string
    {
        return __('filament.nav.groups.content');
    }
}
