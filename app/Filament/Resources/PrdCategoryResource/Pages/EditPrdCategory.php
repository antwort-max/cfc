<?php

namespace App\Filament\Resources\PrdCategoryResource\Pages;

use App\Filament\Resources\PrdCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrdCategory extends EditRecord
{
    protected static string $resource = PrdCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
