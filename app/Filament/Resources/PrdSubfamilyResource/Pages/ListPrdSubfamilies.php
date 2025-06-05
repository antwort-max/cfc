<?php

namespace App\Filament\Resources\PrdSubfamilyResource\Pages;

use App\Filament\Resources\PrdSubfamilyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrdSubfamilies extends ListRecords
{
    protected static string $resource = PrdSubfamilyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
