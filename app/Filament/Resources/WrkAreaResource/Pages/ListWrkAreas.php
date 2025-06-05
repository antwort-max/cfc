<?php

namespace App\Filament\Resources\WrkAreaResource\Pages;

use App\Filament\Resources\WrkAreaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWrkAreas extends ListRecords
{
    protected static string $resource = WrkAreaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
