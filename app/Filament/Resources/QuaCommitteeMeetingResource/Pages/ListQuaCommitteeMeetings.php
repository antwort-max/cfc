<?php

namespace App\Filament\Resources\QuaCommitteeMeetingResource\Pages;

use App\Filament\Resources\QuaCommitteeMeetingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuaCommitteeMeetings extends ListRecords
{
    protected static string $resource = QuaCommitteeMeetingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
