<?php

namespace App\Filament\Resources\PrdFamilyResource\Pages;

use App\Filament\Resources\PrdFamilyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrdFamily extends EditRecord
{
    protected static string $resource = PrdFamilyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
