<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('translate_now')
                ->label(__('filament.resources.post.translate_now'))
                ->icon('heroicon-o-language')
                ->action(fn () => $this->record->autoTranslate())
                ->requiresConfirmation()
                ->color('success'),
            Actions\Action::make('view_public')
                ->label(__('filament.resources.post.view_public'))
                ->icon('heroicon-o-external-link')
                ->url(fn () => route('posts.show', ['locale' => app()->getLocale(), 'slug' => $this->record->slug]))
                ->openUrlInNewTab(),
            Actions\DeleteAction::make(),
        ];
    }
}
