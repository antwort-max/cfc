<?php

namespace App\Filament\Bi\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\HistoricProduct;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MonthlyComparisonMetrics extends StatsOverviewWidget
{
    protected ?string $heading = 'Ventas del mes comparadas con el mismo período del año pasado';

    protected function getCards(): array
    {
        $today = Carbon::today();
        $startThisMonth = $today->copy()->startOfMonth();
        $endThisMonth = $today;

        $startLastYear = $startThisMonth->copy()->subYear();
        $endLastYear = $endThisMonth->copy()->subYear();

        // Ventas totales
        $salesCurrent = HistoricProduct::query()
            ->whereBetween('document_date', [$startThisMonth, $endThisMonth])
            ->sum('total_sales_amount');

        $salesLastYear = HistoricProduct::query()
            ->whereBetween('document_date', [$startLastYear, $endLastYear])
            ->sum('total_sales_amount');

        // Tickets
        $txCurrent = HistoricProduct::query()
            ->whereBetween('document_date', [$startThisMonth, $endThisMonth])
            ->distinct('document_number')
            ->count('document_number');

        $txLastYear = HistoricProduct::query()
            ->whereBetween('document_date', [$startLastYear, $endLastYear])
            ->distinct('document_number')
            ->count('document_number');

        // AOV
        $aovCurrent = $txCurrent ? round($salesCurrent / $txCurrent, 0) : 0;
        $aovLastYear = $txLastYear ? round($salesLastYear / $txLastYear, 0) : 0;

        // Porcentajes
        $calcChange = fn($current, $previous) => $previous > 0 ? round((($current - $previous) / $previous) * 100, 0) : 0;

        $salesChange = $calcChange($salesCurrent, $salesLastYear);
        $txChange = $calcChange($txCurrent, $txLastYear);
        $aovChange = $calcChange($aovCurrent, $aovLastYear);

        return [
            Card::make('Ventas mes actual', '$' . number_format($salesCurrent, 0, ',', '.'))
                ->description(
                    'Año pasado: $' . number_format($salesLastYear, 0, ',', '.') .
                    ' (' . ($salesChange >= 0 ? '+' : '') . $salesChange . '%)'
                ),

            Card::make('Tickets del mes', (string) number_format($txCurrent, 0, ',', '.'))
                ->description(
                    'Año pasado: ' . number_format($txLastYear, 0, ',', '.').
                    ' (' . ($txChange >= 0 ? '+' : '') . $txChange . '%)'
                ),

            Card::make('Valor promedio por ticket', '$' . number_format($aovCurrent, 0, ',', '.'))
                ->description(
                    'Año pasado: $' . number_format($aovLastYear, 0, ',', '.') .
                    ' (' . ($aovChange >= 0 ? '+' : '') . $aovChange . '%)'
                ),
        ];
    }
}
