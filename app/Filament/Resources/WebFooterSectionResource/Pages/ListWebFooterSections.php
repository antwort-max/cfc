<?php

namespace App\Filament\Resources\WebFooterSectionResource\Pages;

use App\Filament\Resources\WebFooterSectionResource;
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
