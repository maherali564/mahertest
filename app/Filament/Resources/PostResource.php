<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasLocaleTabs;
use App\Filament\Resources\PostResource\Pages;
use App\Models\Category;
use App\Models\Post;
use App\Services\MediaOptimizer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    use HasLocaleTabs;
    use \App\Filament\Concerns\HasPermissionBasedAuthorization;

    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make()
                ->schema([
                    Forms\Components\TextInput::make('slug')
                        ->label(__('filament.resources.post.slug'))
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->placeholder(__('filament.resources.post.slug_auto'))
                        ->columnSpan(1),
                    Forms\Components\Select::make('user_id')
                        ->label(__('filament.resources.post.author'))
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->default(auth()->id())
                        ->columnSpan(1),
                ]),
            static::localeTabs('title', __('filament.resources.post.title')),
            static::localeTabs('content', __('filament.resources.post.content'), 'richtext'),
            static::localeTabs('excerpt', __('filament.resources.post.excerpt'), 'textarea'),
            Forms\Components\Section::make(__('filament.resources.post.section.meta'))
                ->schema([
                    static::localeTabs('meta_title', __('filament.resources.post.meta_title')),
                    static::localeTabs('meta_description', __('filament.resources.post.meta_description'), 'textarea'),
                    static::localeTabs('meta_keywords', __('filament.resources.post.meta_keywords')),
                ])->collapsible(),
            Forms\Components\Section::make(__('filament.resources.post.section.settings'))
                ->schema([
                    Forms\Components\Grid::make()
                        ->schema([
                            Forms\Components\Select::make('category_id')
                                ->label(__('filament.resources.post.category'))
                                ->relationship('category', 'slug', fn ($query) => $query->reorder()->orderBy('id'))
                                ->getOptionLabelFromRecordUsing(fn (Category $record) => $record->getTranslation('name', app()->getLocale()) ?: $record->getTranslation('name', 'ar'))
                                ->searchable()
                                ->preload(),
                            Forms\Components\Select::make('tags')
                                ->label(__('filament.resources.post.tags'))
                                ->options(fn () => \App\Models\Tag::orderBy('id')->get()->mapWithKeys(fn ($tag) => [$tag->id => $tag->getTranslation('name', app()->getLocale())]))
                                ->multiple()
                                ->searchable()
                                ->preload()
                                ->saveRelationshipsUsing(fn ($state, $record) => $record->tags()->sync($state ?? [])),
                            Forms\Components\Toggle::make('is_featured')
                                ->label(__('filament.resources.post.is_featured'))
                                ->default(false),
                            Forms\Components\Select::make('status')
                                ->label(__('filament.resources.post.status'))
                                ->options([
                                    'draft' => __('filament.resources.post.status_draft'),
                                    'published' => __('filament.resources.post.status_published'),
                                    'archived' => __('filament.resources.post.status_archived'),
                                ])
                                ->default('draft'),
                            Forms\Components\DateTimePicker::make('published_at')
                                ->label(__('filament.resources.post.published_at'))
                                ->default(now()),
                        ]),
                ]),
            Forms\Components\Section::make(__('filament.resources.post.section.image'))
                ->schema([
                    Forms\Components\FileUpload::make('featured_image')
                        ->label(__('filament.resources.post.featured_image'))
                        ->image()
                        ->directory('posts')
                        ->saveUploadedFileUsing(MediaOptimizer::saveUploadedImage('posts'))
                        ->deleteUploadedFileUsing(fn ($file) => Storage::disk('public')->delete($file))
                        ->removeUploadedFileButtonPosition('right'),
                    Forms\Components\FileUpload::make('images')
                        ->label(__('filament.resources.post.images'))
                        ->multiple()
                        ->image()
                        ->directory('posts')
                        ->saveUploadedFileUsing(MediaOptimizer::saveUploadedImage('posts'))
                        ->deleteUploadedFileUsing(fn ($file) => Storage::disk('public')->delete($file))
                        ->removeUploadedFileButtonPosition('right')
                        ->reorderable(),
                    Forms\Components\Grid::make()
                        ->schema([
                            Forms\Components\TextInput::make('video_url')
                                ->label(__('filament.resources.post.video_url'))
                                ->placeholder('https://www.youtube.com/watch?v=...'),
                            Forms\Components\Select::make('video_type')
                                ->label(__('filament.resources.post.video_type'))
                                ->options([
                                    'youtube' => 'YouTube',
                                    'vimeo' => 'Vimeo',
                                    'upload' => __('filament.resources.post.video_upload'),
                                ])
                                ->default('youtube'),
                        ]),
                ]),
            Forms\Components\Section::make(__('filament.resources.post.section.translate'))
                ->schema([
                    Forms\Components\Checkbox::make('_auto_translate')
                        ->label(__('filament.resources.post.auto_translate_label'))
                        ->default(true)
                        ->reactive(),
                ])->compact(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\ImageColumn::make('featured_image')
                ->label(__('filament.resources.post.featured_image'))
                ->size(50),
            Tables\Columns\TextColumn::make('title')
                ->label(__('filament.resources.post.title'))
                ->formatStateUsing(fn ($state, $record) => $record?->getTranslation('title', 'ar') ?? '')
                ->limit(50)
                ->searchable(),
            Tables\Columns\TextColumn::make('category.name')
                ->label(__('filament.resources.post.category'))
                ->formatStateUsing(fn ($state) => $state ?: '-'),
            Tables\Columns\TextColumn::make('status')
                ->label(__('filament.resources.post.status'))
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'published' => 'success',
                    'draft' => 'warning',
                    'archived' => 'danger',
                    default => 'gray',
                }),
            Tables\Columns\TextColumn::make('published_at')
                ->label(__('filament.resources.post.published_at'))
                ->date('Y-m-d')
                ->sortable(),
            Tables\Columns\TextColumn::make('views')
                ->label(__('filament.resources.post.views'))
                ->sortable(),
            Tables\Columns\TextColumn::make('translation_status')
                ->label(__('filament.resources.post.translation_status'))
                ->badge()
                ->state(fn (Post $record): string => $record->hasTranslation('title', 'en') ? __('filament.resources.post.translated') : __('filament.resources.post.not_translated'))
                ->color(fn (string $state): string => $state === __('filament.resources.post.translated') ? 'success' : 'danger'),
        ])->defaultSort('published_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('filament.resources.post.status'))
                    ->options([
                        'draft' => __('filament.resources.post.status_draft'),
                        'published' => __('filament.resources.post.status_published'),
                        'archived' => __('filament.resources.post.status_archived'),
                    ]),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label(__('filament.resources.post.is_featured')),
                Tables\Filters\Filter::make('translated')
                    ->label(__('filament.resources.post.translated'))
                    ->query(fn ($query) => $query->whereJsonLength('title->en', '>', 0)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('translate_now')
                    ->label(__('filament.resources.post.translate_now'))
                    ->icon('heroicon-o-language')
                    ->action(fn (Post $record) => $record->autoTranslate())
                    ->requiresConfirmation()
                    ->color('success'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('translate_bulk')
                        ->label(__('filament.resources.post.translate_bulk'))
                        ->icon('heroicon-o-language')
                        ->action(fn ($records) => $records->each(fn ($record) => $record->autoTranslate()))
                        ->requiresConfirmation()
                        ->color('success'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
            'view' => Pages\ViewPost::route('/{record}'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.post.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.post.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.post.plural_label');
    }

    public static function getNavigationGroup(): string
    {
        return __('filament.nav.groups.content');
    }
}
