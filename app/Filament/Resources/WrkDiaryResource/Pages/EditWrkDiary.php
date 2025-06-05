<?php

namespace App\Filament\Resources\WrkDiaryResource\Pages;

use App\Filament\Resources\WrkDiaryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWrkDiary extends EditRecord
{
    protected static string $resource = WrkDiaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
