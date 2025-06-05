<?php

namespace App\Filament\Resources\WrkVacationResource\Pages;

use App\Filament\Resources\WrkVacationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWrkVacations extends ListRecords
{
    protected static string $resource = WrkVacationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
