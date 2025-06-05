<?php

namespace App\Filament\Resources\WrkAreaResource\Pages;

use App\Filament\Resources\WrkAreaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWrkArea extends EditRecord
{
    protected static string $resource = WrkAreaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
