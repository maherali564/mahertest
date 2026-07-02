<?php

namespace App\Filament\Resources\EmergencyCampaignResource\Pages;

use App\Filament\Resources\EmergencyCampaignResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmergencyCampaigns extends ListRecords
{
    protected static string $resource = EmergencyCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
