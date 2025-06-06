<?php

namespace App\Filament\Ecommerce\Resources\WebMenuResource\Pages;

use App\Filament\Ecommerce\Resources\WebMenuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWebMenu extends EditRecord
{
    protected static string $resource = WebMenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
