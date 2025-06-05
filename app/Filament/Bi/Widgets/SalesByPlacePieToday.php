<?php

namespace App\Filament\Bi\Widgets;

use App\Models\HistoricDocument;
use Filament\Widgets\PieChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesByPlacePieToday extends PieChartWidget
{
    protected static ?string $heading = 'Distribución de Ventas por Locales';

    protected int $days = 5;

    public function setDays(int $days): static
    {
        $this->days = $days;
        return $this;
    }

    protected function getData(): array
    {
        $from = Carbon::now()->today();
        $to   = Carbon::now()->today();

        $records = HistoricDocument::query()
            ->select(['place', DB::raw('SUM(total_sales_amount) AS total')])
            ->whereDate('document_date', '>=', $from)
            ->whereDate('document_date', '<=', $to)
            ->groupBy('place')
            ->orderByDesc('total')
            ->get();

        $rawLabels = $records->pluck('place')->toArray();
        $totals    = $records->pluck('total')->map(fn($v) => (int) $v)->toArray();

        $grandTotal = array_sum($totals) ?: 1;

        // Construye etiquetas con porcentaje
        $labels = array_map(
            fn($label, $value) => sprintf('%s (%.1f%%)', $label, $value / $grandTotal * 100),
            $rawLabels,
            $totals
        );

        // Paleta de colores para slices
        $palette = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
            '#9966FF', '#FF9F40', '#E7E9ED', '#A9A9A9',
        ];
        $backgroundColors = array_map(
            fn($i) => $palette[$i % count($palette)],
            array_keys($labels)
        );

        return [
            'labels'   => $labels,
            'datasets' => [
                [
                    'label'           => 'Ventas',
                    'data'            => $totals,
                    'backgroundColor' => $backgroundColors,
                    'borderColor'     => '#ffffff',
                    'borderWidth'     => 1,
                ],
            ],
        ];
    }
}