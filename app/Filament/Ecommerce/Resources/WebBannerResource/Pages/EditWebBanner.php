<?php

namespace App\Filament\Ecommerce\Resources\WebBannerResource\Pages;

use App\Filament\Ecommerce\Resources\WebBannerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWebBanner extends EditRecord
{
    protected static string $resource = WebBannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
