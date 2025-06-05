<?php

namespace App\Filament\Resources\WebThemeOptionResource\Pages;

use App\Filament\Resources\WebThemeOptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWebThemeOption extends EditRecord
{
    protected static string $resource = WebThemeOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
