<?php

namespace App\Filament\Ecommerce\Resources\WebLocationResource\Pages;

use App\Filament\Ecommerce\Resources\WebLocationResource;
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
