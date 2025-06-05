<?php

namespace App\Filament\Resources\WrkTaskResource\Pages;

use App\Filament\Resources\WrkTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWrkTasks extends ListRecords
{
    protected static string $resource = WrkTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
