<?php

namespace App\Filament\Resources\PrdBrandResource\Pages;

use App\Filament\Resources\PrdBrandResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrdBrand extends EditRecord
{
    protected static string $resource = PrdBrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
