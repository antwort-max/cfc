<?php

namespace App\Filament\Resources\QuaCorrectiveActionResource\Pages;

use App\Filament\Resources\QuaCorrectiveActionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuaCorrectiveActions extends ListRecords
{
    protected static string $resource = QuaCorrectiveActionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
