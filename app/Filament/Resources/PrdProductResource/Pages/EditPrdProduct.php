<?php

namespace App\Filament\Resources\PrdProductResource\Pages;

use App\Filament\Resources\PrdProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrdProduct extends EditRecord
{
    protected static string $resource = PrdProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
