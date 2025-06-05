<?php

namespace App\Filament\Resources\HistoricDocumentResource\Pages;

use App\Filament\Resources\HistoricDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\Actions\Action;

class EditHistoricDocument extends EditRecord
{
    protected static string $resource = HistoricDocumentResource::class;

    
    protected function getHeaderActions(): array
    {
        return [
          
        ];

    }
}
