<?php

namespace App\Filament\Resources\WebMenuResource\Pages;

use App\Filament\Resources\WebMenuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWebMenus extends ListRecords
{
    protected static string $resource = WebMenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
