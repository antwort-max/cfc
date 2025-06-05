<?php

namespace App\Filament\Resources\SupSupplierResource\Pages;

use App\Filament\Resources\SupSupplierResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSupSuppliers extends ListRecords
{
    protected static string $resource = SupSupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
