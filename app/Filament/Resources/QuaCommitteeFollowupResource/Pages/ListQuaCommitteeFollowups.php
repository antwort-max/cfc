<?php

namespace App\Filament\Resources\QuaCommitteeFollowupResource\Pages;

use App\Filament\Resources\QuaCommitteeFollowupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuaCommitteeFollowups extends ListRecords
{
    protected static string $resource = QuaCommitteeFollowupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
