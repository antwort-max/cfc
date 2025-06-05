<?php

namespace App\Filament\Bi\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\HistoricProduct;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DayComparisonMetrics extends StatsOverviewWidget
{
    // Debe ser no estático como en la clase base
    protected ?string $heading = 'Ventas de hoy, comparadas con ayer.';

    protected function getCards(): array
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Totals
        $salesToday = HistoricProduct::query()
            ->whereDate('document_date', $today)
            ->sum('total_sales_amount');
        $salesYesterday = HistoricProduct::query()
            ->whereDate('document_date', $yesterday)
            ->sum('total_sales_amount');

        // Transactions
        $txToday = HistoricProduct::query()
            ->whereDate('document_date', $today)
            ->distinct('document_number')
            ->count('document_number');
        $txYesterday = HistoricProduct::query()
            ->whereDate('document_date', $yesterday)
            ->distinct('document_number')
            ->count('document_number');

        // AOV
        $aovToday = $txToday ? round($salesToday / $txToday, 0) : 0;
        $aovYesterday = $txYesterday ? round($salesYesterday / $txYesterday, 0) : 0;

        // Percentage changes
        $calcChange = fn($current, $previous) => $previous > 0 ? round((($current - $previous) / $previous) * 100, 0) : 0;

        $salesChange = $calcChange($salesToday, $salesYesterday);
        $txChange = $calcChange($txToday, $txYesterday);
        $aovChange = $calcChange($aovToday, $aovYesterday);

        return [
            Card::make('Venta diaria', '$' . number_format($salesToday, 0, ',', '.'))
                ->description(
                    'Ayer: $' . number_format($salesYesterday, 0, ',', '.') .
                    ' (' . ($salesChange >= 0 ? '+' : '') . $salesChange . '%)'
                ),

            Card::make('Ticket del día', (string) $txToday)
                ->description(
                    'Ayer: ' . $txYesterday .
                    ' (' . ($txChange >= 0 ? '+' : '') . $txChange . '%)'
                ),

            Card::make('Valor promedio por ticket', '$' . number_format($aovToday, 0, ',', '.'))
                ->description(
                    'Ayer: $' . number_format($aovYesterday, 0, ',', '.') .
                    ' (' . ($aovChange >= 0 ? '+' : '') . $aovChange . '%)'
                ),
        ];
    }
}
