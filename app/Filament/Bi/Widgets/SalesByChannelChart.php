<?php

namespace App\Filament\Bi\Widgets;

use Filament\Widgets\PieChartWidget;
use App\Models\HistoricProduct;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesByChannelChart extends PieChartWidget
{
    protected static ?string $heading = 'Documentos de Venta';

    public function getCardsData(): array
    {
        return $this->getCards();
    }

    protected array $backgroundColors = [
        '#F87171', // red-400
        '#60A5FA', // blue-400
        '#34D399', // green-400
        '#FBBF24', // yellow-400
        '#A78BFA', // purple-400
        '#F472B6', // pink-400
    ];

    protected function getData(): array
    {
        $today = Carbon::today();

        // Datos por canal: suma de ventas y conteo de documentos
        $results = HistoricProduct::query()
            ->select([
                'document_type',
                DB::raw('SUM(total_sales_amount)    as total_sales'),
                DB::raw('COUNT(DISTINCT document_number) as docs_count'),
            ])
            ->whereDate('document_date', $today)
            ->groupBy('document_type')
            ->get();

        $totalSales = $results->sum('total_sales');

        $labels = [];
        $data   = [];
        $colors = [];
        $i      = 0;
        $colorCount = count($this->backgroundColors);

        foreach ($results as $row) {
            $pct = $totalSales > 0
                ? round(($row->total_sales / $totalSales) * 100, 1)
                : 0;

            // Formateo de etiqueta: Canal – $venta, docs, X%
            $labels[] = sprintf(
                '%s – $%s, %d docs (%.1f%%)',
                $row->document_type,
                number_format($row->total_sales, 0, '.', ','),
                $row->docs_count,
                $pct
            );

            $data[] = (float) $row->total_sales;

            // Color cíclico
            $colors[] = $this->backgroundColors[$i % $colorCount];
            $i++;
        }

        return [
            'labels'   => $labels,
            'datasets' => [
                [
                    'label'           => 'Sales',
                    'data'            => $data,
                    'backgroundColor' => $colors,
                ],
            ],
        ];
    }
}
