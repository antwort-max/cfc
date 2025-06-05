<?php

namespace App\Filament\Resources\WrkVacationResource\Pages;

use App\Filament\Resources\WrkVacationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWrkVacation extends EditRecord
{
    protected static string $resource = WrkVacationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
