<?php

namespace App\Filament\Resources\WebLocationResource\Pages;

use App\Filament\Resources\WebLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWebLocation extends EditRecord
{
    protected static string $resource = WebLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
