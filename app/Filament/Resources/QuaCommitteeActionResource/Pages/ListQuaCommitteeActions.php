<?php

namespace App\Filament\Resources\QuaCommitteeActionResource\Pages;

use App\Filament\Resources\QuaCommitteeActionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuaCommitteeActions extends ListRecords
{
    protected static string $resource = QuaCommitteeActionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
