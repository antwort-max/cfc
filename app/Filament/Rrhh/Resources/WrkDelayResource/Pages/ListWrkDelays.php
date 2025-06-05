<?php

namespace App\Filament\Rrhh\Resources\WrkDelayResource\Pages;

use App\Filament\Rrhh\Resources\WrkDelayResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWrkDelays extends ListRecords
{
    protected static string $resource = WrkDelayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
