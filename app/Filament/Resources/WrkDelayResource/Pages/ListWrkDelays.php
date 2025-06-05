<?php

namespace App\Filament\Resources\WrkDelayResource\Pages;

use App\Filament\Resources\WrkDelayResource;
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
