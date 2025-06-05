<?php

namespace App\Filament\Bi\Resources\BiDailyStockSnapshotResource\Pages;

use App\Filament\Bi\Resources\BiDailyStockSnapshotResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBiDailyStockSnapshots extends ListRecords
{
    protected static string $resource = BiDailyStockSnapshotResource::class;

    /**
     * Devuelve una clave única para cada fila (Livewire key).
     */
    public function getTableRecordKey($record): string
    {

        return $record->snapshot_date . '|' . $record->product_sku;
    }
}

