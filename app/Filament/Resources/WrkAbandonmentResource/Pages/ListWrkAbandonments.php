<?php

namespace App\Filament\Resources\WrkAbandonmentResource\Pages;

use App\Filament\Resources\WrkAbandonmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWrkAbandonments extends ListRecords
{
    protected static string $resource = WrkAbandonmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
