<?php

namespace App\Filament\Resources\HistoricDocumentResource\Pages;

use App\Filament\Resources\HistoricDocumentResource;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\HistoricDocumentResource\Widgets\HistoricSalesPerHourChart;   // ⬅️ importa tu widget
// Si aún quieres el botón “Crear”, deja Filament\Actions; si no, elimínalo.
// use Filament\Actions;

class ListHistoricDocuments extends ListRecords
{
    protected static string $resource = HistoricDocumentResource::class;

    /**
     * Widgets que aparecerán sobre la tabla.
     */
    protected function getHeaderWidgets(): array
    {
        return [
            HistoricSalesPerHourChart::class,
        ];
    }

    /**
     * Acciones de cabecera de la página.
     * Como en el recurso tienes `canCreate(): false`, normalmente se deja vacío.
     */
    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),   // ⬅️ quítalo o déjalo comentado
        ];
    }
}