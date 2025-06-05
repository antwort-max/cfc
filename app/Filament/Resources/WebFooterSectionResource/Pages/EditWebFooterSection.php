<?php

namespace App\Filament\Resources\WebFooterSectionResource\Pages;

use App\Filament\Resources\WebFooterSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWebFooterSection extends EditRecord
{
    protected static string $resource = WebFooterSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
