<?php

namespace App\Filament\Ecommerce\Resources\WebSocialLinkResource\Pages;

use App\Filament\Ecommerce\Resources\WebSocialLinkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWebSocialLink extends EditRecord
{
    protected static string $resource = WebSocialLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
