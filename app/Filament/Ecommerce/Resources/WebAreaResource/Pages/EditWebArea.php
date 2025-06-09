<?php

namespace App\Filament\Ecommerce\Resources\WebAreaResource\Pages;

use App\Filament\Ecommerce\Resources\WebAreaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWebArea extends EditRecord
{
    protected static string $resource = WebAreaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
