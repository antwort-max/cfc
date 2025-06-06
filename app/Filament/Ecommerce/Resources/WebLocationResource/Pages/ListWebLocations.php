<?php

namespace App\Filament\Ecommerce\Resources\WebLocationResource\Pages;

use App\Filament\Ecommerce\Resources\WebLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWebLocations extends ListRecords
{
    protected static string $resource = WebLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
