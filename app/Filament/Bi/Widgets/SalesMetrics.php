<?php

namespace App\Filament\Bi\Widgets;

use App\Models\HistoricDocument;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class SalesMetrics extends StatsOverviewWidget
{
    // Cambiado de static a no-static:
    protected ?string $heading = 'Métricas de Venta';

    public int $days = 30;

    public function setDays(int $days): static
    {
        $this->days = $days;
        return $this;
    }

    protected function getCards(): array
    {
        $today         = Carbon::now()->endOfDay();
        $fromCurrent   = Carbon::now()->subDays($this->days - 1)->startOfDay();
        $toCurrent     = $today;
        $fromPrevious  = (clone $fromCurrent)->subDays($this->days);
        $toPrevious    = (clone $fromCurrent)->subSecond();

        $countCurrent  = HistoricDocument::whereBetween('document_date', [$fromCurrent, $toCurrent])->count();
        $sumCurrent    = HistoricDocument::whereBetween('document_date', [$fromCurrent, $toCurrent])->sum('total_sales_amount');
        $countPrevious = HistoricDocument::whereBetween('document_date', [$fromPrevious, $toPrevious])->count();
        $sumPrevious   = HistoricDocument::whereBetween('document_date', [$fromPrevious, $toPrevious])->sum('total_sales_amount');

        $avgCurrent  = $countCurrent  > 0 ? $sumCurrent  / $countCurrent  : 0;
        $avgPrevious = $countPrevious > 0 ? $sumPrevious / $countPrevious : 0;

        $countDiffPct = $countPrevious > 0
            ? round((($countCurrent - $countPrevious) / $countPrevious) * 100, 1)
            : null;
        $avgDiffPct = $avgPrevious > 0
            ? round((($avgCurrent - $avgPrevious) / $avgPrevious) * 100, 1)
            : null;

        $countCompareText = $countDiffPct === null
            ? ''
            : ' (' . ($countDiffPct >= 0 ? '+' : '') . $countDiffPct . '% vs. anterior)';
        $avgCompareText = $avgDiffPct === null
            ? ''
            : ' (' . ($avgDiffPct >= 0 ? '+' : '') . $avgDiffPct . '% vs. anterior)';

        return [
            Card::make('Número de Transacciones', $countCurrent)
                ->description("Últimos {$this->days} días{$countCompareText}")
                ->descriptionIcon('heroicon-o-calendar'),

            Card::make('Ticket Promedio (CLP)', number_format($avgCurrent, 0, ',', '.'))
                ->description("Últimos {$this->days} días{$avgCompareText}")
                ->descriptionIcon('heroicon-o-currency-dollar'),
        ];
    }
}
