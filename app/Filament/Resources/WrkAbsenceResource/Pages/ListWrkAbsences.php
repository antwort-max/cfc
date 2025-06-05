<?php

namespace App\Filament\Resources\WrkAbsenceResource\Pages;

use App\Filament\Resources\WrkAbsenceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWrkAbsences extends ListRecords
{
    protected static string $resource = WrkAbsenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
