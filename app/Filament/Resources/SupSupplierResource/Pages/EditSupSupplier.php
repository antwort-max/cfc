<?php

namespace App\Filament\Resources\SupSupplierResource\Pages;

use App\Filament\Resources\SupSupplierResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupSupplier extends EditRecord
{
    protected static string $resource = SupSupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
