<?php

namespace App\Filament\Ecommerce\Resources\WebActivityResource\Pages;

use App\Filament\Ecommerce\Resources\WebActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWebActivities extends ListRecords
{
    protected static string $resource = WebActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
