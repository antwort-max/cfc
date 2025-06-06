<?php

namespace App\Filament\Ecommerce\Resources\WebFooterSectionResource\Pages;

use App\Filament\Ecommerce\Resources\WebFooterSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWebFooterSections extends ListRecords
{
    protected static string $resource = WebFooterSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
