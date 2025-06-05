<?php

namespace App\Filament\Resources\QuaCommitteeActionResource\Pages;

use App\Filament\Resources\QuaCommitteeActionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuaCommitteeAction extends EditRecord
{
    protected static string $resource = QuaCommitteeActionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
