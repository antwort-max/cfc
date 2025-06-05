<?php

namespace App\Filament\Resources\QuaCorrectiveActionResource\Pages;

use App\Filament\Resources\QuaCorrectiveActionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuaCorrectiveAction extends EditRecord
{
    protected static string $resource = QuaCorrectiveActionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
