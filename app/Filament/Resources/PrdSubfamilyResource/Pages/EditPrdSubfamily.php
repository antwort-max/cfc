<?php

namespace App\Filament\Resources\PrdSubfamilyResource\Pages;

use App\Filament\Resources\PrdSubfamilyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrdSubfamily extends EditRecord
{
    protected static string $resource = PrdSubfamilyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
