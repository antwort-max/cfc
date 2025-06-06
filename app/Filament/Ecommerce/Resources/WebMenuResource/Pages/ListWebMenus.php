<?php

namespace App\Filament\Ecommerce\Resources\WebMenuResource\Pages;

use App\Filament\Ecommerce\Resources\WebMenuResource;
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
