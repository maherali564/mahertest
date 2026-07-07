<?php

namespace App\Filament\Resources\EmergencyCampaignResource\Pages;

use App\Filament\Concerns\HasTranslateAll;
use App\Filament\Resources\EmergencyCampaignResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmergencyCampaign extends EditRecord
{
    use HasTranslateAll;

    protected static string $resource = EmergencyCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getTranslateAllAction(),
            Actions\DeleteAction::make(),
        ];
    }
}
