<?php

namespace App\Filament\Resources\StoryResource\Pages;

use App\Filament\Concerns\HasTranslateAll;
use App\Filament\Resources\StoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStory extends EditRecord
{
    use HasTranslateAll;

    protected static string $resource = StoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getTranslateAllAction(),
            Actions\DeleteAction::make(),
        ];
    }
}
