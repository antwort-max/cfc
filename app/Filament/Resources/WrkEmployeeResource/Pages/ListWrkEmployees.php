<?php

namespace App\Filament\Resources\WrkEmployeeResource\Pages;

use App\Filament\Resources\WrkEmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWrkEmployees extends ListRecords
{
    protected static string $resource = WrkEmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
