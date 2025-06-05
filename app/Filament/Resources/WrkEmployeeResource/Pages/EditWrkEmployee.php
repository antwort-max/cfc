<?php

namespace App\Filament\Resources\WrkEmployeeResource\Pages;

use App\Filament\Resources\WrkEmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWrkEmployee extends EditRecord
{
    protected static string $resource = WrkEmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
