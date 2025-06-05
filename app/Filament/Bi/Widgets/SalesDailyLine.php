<?php

namespace App\Filament\Bi\Widgets;

use App\Models\HistoricDocument;
use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesDailyLine extends LineChartWidget
{
    protected static ?string $heading = 'Ventas Diarias Totales';

    // Días hacia atrás por defecto (opcional)
    public int $days = 30;

    public function setDays(int $days): static
    {
        $this->days = $days;
        return $this;
    }

    protected function getData(): array
    {
        $from = Carbon::now()->subDays($this->days - 1)->startOfDay();
        $to   = Carbon::now()->endOfDay();

        $records = HistoricDocument::query()
            ->select([
                DB::raw('DATE(document_date) as date'),
                DB::raw('SUM(total_sales_amount) as total'),
            ])
            ->whereBetween('document_date', [$from, $to])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = $records->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d/m'))->toArray();
        $totals = $records->pluck('total')->map(fn($v) => (float) $v)->toArray();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Ventas',
                    'data'  => $totals,
                ],
            ],
        ];
    }
}
