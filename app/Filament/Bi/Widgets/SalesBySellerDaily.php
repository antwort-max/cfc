<?php

namespace App\Filament\Bi\Widgets;

use App\Models\HistoricDocument;
use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SalesBySellerDaily extends LineChartWidget
{
    protected static ?string $heading = 'Ventas Diarias por Vendedor';

    protected function getData(): array
    {
        $from = Carbon::now()->subDays(29)->startOfDay();
        $to   = Carbon::now()->endOfDay();

        // Consulta con whereDate para asegurar coincidencia de fechas
        $records = HistoricDocument::query()
            ->select([
                DB::raw('DATE(document_date) as date'),
                'seller',
                DB::raw('SUM(total_sales_amount) as total'),
            ])
            ->whereDate('document_date', '>=', $from)
            ->whereDate('document_date', '<=', $to)
            ->groupBy('date', 'seller')
            ->orderBy('date')
            ->get();

        // Generar labels de los últimos 30 días
        $labels = collect(range(0, 29))
            ->map(fn($i) => $from->copy()->addDays($i)->format('Y-m-d'))
            ->toArray();

        // Colores predefinidos para los vendedores
        $palette = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
            '#9966FF', '#FF9F40', '#E7E9ED', '#A9A9A9',
        ];

        // Identificar vendedores únicos con registros
        $sellers = $records->pluck('seller')->unique()->values();
        $sellerCount = $sellers->count() ?: 1;

        // Construir datasets para cada vendedor, asignando color
        $datasets = $sellers
            ->map(fn($seller, $index) => [
                'label' => $seller,
                'data'  => array_map(
                    fn($date) => $records->firstWhere(
                        fn($r) => $r->seller === $seller && $r->date === $date
                    )?->total ?? 0,
                    $labels
                ),
                'borderColor'     => $palette[$index % count($palette)],
                'backgroundColor' => $palette[$index % count($palette)],
                'fill'            => false,
                'tension'         => 0.3,
            ])
            ->toArray();

        // Calcular promedio diario de ventas (suma de totales / número de vendedores)
        $averageDaily = array_map(
            fn($date) => (int) round(
                $records->where('date', $date)->sum('total') / $sellerCount
            ),
            $labels
        );

        // Añadir línea de promedio en negro
        $datasets[] = [
            'label'           => 'Promedio Diario',
            'data'            => $averageDaily,
            'borderColor'     => 'black',
            'backgroundColor' => 'black',
            'fill'            => false,
            'pointRadius'     => 0,
            'tension'         => 0.3,
        ];

        // Log para depuración
        Log::debug('SalesBySellerDaily data', [
            'labels'   => $labels,
            'datasets' => $datasets,
        ]);

        return [
            'labels'   => $labels,
            'datasets' => $datasets,
        ];
    }
}
