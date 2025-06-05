<?php

namespace App\Filament\Resources\PrdBrandResource\Pages;

use App\Filament\Resources\PrdBrandResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrdBrands extends ListRecords
{
    protected static string $resource = PrdBrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
