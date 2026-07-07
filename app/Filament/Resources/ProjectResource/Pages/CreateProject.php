<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Concerns\HasTranslateAll;
use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    use HasTranslateAll;

    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getTranslateAllAction(),
        ];
    }
}
