<?php

namespace App\Filament\Resources\WebSocialLinkResource\Pages;

use App\Filament\Resources\WebSocialLinkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWebSocialLinks extends ListRecords
{
    protected static string $resource = WebSocialLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
