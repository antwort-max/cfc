<?php

namespace App\Filament\Resources\WebLocationResource\Pages;

use App\Filament\Resources\WebLocationResource;
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
