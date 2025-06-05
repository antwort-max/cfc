<?php

namespace App\Filament\Rrhh\Resources\WrkDelayResource\Pages;

use App\Filament\Rrhh\Resources\WrkDelayResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWrkDelay extends EditRecord
{
    protected static string $resource = WrkDelayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
