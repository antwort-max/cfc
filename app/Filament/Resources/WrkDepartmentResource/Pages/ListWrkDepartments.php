<?php

namespace App\Filament\Resources\WrkDepartmentResource\Pages;

use App\Filament\Resources\WrkDepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWrkDepartments extends ListRecords
{
    protected static string $resource = WrkDepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
