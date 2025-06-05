<?php

namespace App\Filament\Bi\Widgets;

use App\Models\HistoricDocument;
use Filament\Widgets\BarChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SalesBySellerMonthly extends BarChartWidget
{
    protected static ?string $heading = 'Ventas por Vendedor durante el mes';

    protected function getData(): array
    {
        // Definir el periodo: mes en curso
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Consulta: suma total_sales_amount por seller en el mes
        $records = HistoricDocument::query()
            ->select([
                'seller',
                DB::raw('SUM(total_sales_amount) as total'),
            ])
            ->whereDate('document_date', '>=', $startOfMonth)
            ->whereDate('document_date', '<=', $endOfMonth)
            ->groupBy('seller')
            ->orderByDesc('total')
            ->get();

        // Preparar labels (vendedores) y datos
        $labels = $records->pluck('seller')->toArray();
        $data = $records->pluck('total')->map(fn($value) => (int) $value)->toArray();

        // Calcular media de ventas
        $average = count($data) > 0 ? array_sum($data) / count($data) : 0;

        // Log para depuración
        Log::debug('SalesBySellerMonthly data', [
            'labels' => $labels,
            'data'   => $data,
            'average'=> $average,
        ]);

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total Ventas',
                    'data' => $data,
                ],
                [
                    'label' => 'Media',
                    'data' => array_fill(0, count($data), (int) round($average)),
                    'type' => 'line',
                    'borderColor' => 'red',
                    'borderWidth' => 2,
                    'fill' => false,
                    'pointRadius' => 0,
                ],
            ],
        ];
    }
}

