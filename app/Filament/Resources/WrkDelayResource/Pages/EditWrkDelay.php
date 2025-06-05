<?php

namespace App\Filament\Resources\WrkDelayResource\Pages;

use App\Filament\Resources\WrkDelayResource;
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
