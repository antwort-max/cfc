<?php

namespace App\Filament\Resources\WrkAbsenceResource\Pages;

use App\Filament\Resources\WrkAbsenceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWrkAbsence extends EditRecord
{
    protected static string $resource = WrkAbsenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
