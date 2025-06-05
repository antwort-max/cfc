<?php

namespace App\Filament\Resources\WrkDiaryResource\Pages;

use App\Filament\Resources\WrkDiaryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWrkDiaries extends ListRecords
{
    protected static string $resource = WrkDiaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
