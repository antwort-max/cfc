<?php

namespace App\Filament\Resources\WrkLicenseResource\Pages;

use App\Filament\Resources\WrkLicenseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWrkLicense extends EditRecord
{
    protected static string $resource = WrkLicenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
