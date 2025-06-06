<?php

namespace App\Filament\Ecommerce\Resources\WebActivityResource\Pages;

use App\Filament\Ecommerce\Resources\WebActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWebActivity extends EditRecord
{
    protected static string $resource = WebActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
