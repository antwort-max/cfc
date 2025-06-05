<?php

namespace App\Filament\Resources\PrdCategoryResource\Pages;

use App\Filament\Resources\PrdCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrdCategories extends ListRecords
{
    protected static string $resource = PrdCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
