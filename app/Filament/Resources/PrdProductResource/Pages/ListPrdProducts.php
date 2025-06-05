<?php

namespace App\Filament\Resources\PrdProductResource\Pages;

use App\Filament\Resources\PrdProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrdProducts extends ListRecords
{
    protected static string $resource = PrdProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
