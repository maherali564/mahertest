<?php

namespace App\Filament\Resources\EmergencyCampaignResource\Pages;

use App\Filament\Concerns\HasTranslateAll;
use App\Filament\Resources\EmergencyCampaignResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmergencyCampaign extends CreateRecord
{
    use HasTranslateAll;

    protected static string $resource = EmergencyCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getTranslateAllAction(),
        ];
    }
}
