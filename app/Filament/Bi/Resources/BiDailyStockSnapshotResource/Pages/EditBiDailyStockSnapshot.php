<?php

namespace App\Filament\Bi\Resources\BiDailyStockSnapshotResource\Pages;

use App\Filament\Bi\Resources\BiDailyStockSnapshotResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBiDailyStockSnapshot extends EditRecord
{
    protected static string $resource = BiDailyStockSnapshotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
