<?php

namespace App\Filament\Resources\QuaNonconformityResource\Pages;

use App\Filament\Resources\QuaNonconformityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuaNonconformities extends ListRecords
{
    protected static string $resource = QuaNonconformityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
