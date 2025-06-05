<?php

namespace App\Filament\Resources\SupMeetingResource\Pages;

use App\Filament\Resources\SupMeetingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupMeeting extends EditRecord
{
    protected static string $resource = SupMeetingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
