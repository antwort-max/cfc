<?php

namespace App\Filament\Resources\WrkLicenseResource\Pages;

use App\Filament\Resources\WrkLicenseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWrkLicenses extends ListRecords
{
    protected static string $resource = WrkLicenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
