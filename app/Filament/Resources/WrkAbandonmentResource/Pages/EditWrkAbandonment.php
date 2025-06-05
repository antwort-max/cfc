<?php

namespace App\Filament\Resources\WrkAbandonmentResource\Pages;

use App\Filament\Resources\WrkAbandonmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWrkAbandonment extends EditRecord
{
    protected static string $resource = WrkAbandonmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
