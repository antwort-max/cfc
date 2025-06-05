<?php

namespace App\Filament\Bi\Widgets;

use Filament\Widgets\LineChartWidget;
use App\Models\HistoricDocument;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HourlySalesTrendChart extends LineChartWidget
{
    protected static ?string $heading = 'Ventas por horas';

    //protected int | string | array $columnSpan = 'full';
    protected bool $showDataLabels = true; 

    protected function getData(): array
    {
          
        $today = Carbon::today();

        // Generar un rango de horas de 0 a 23
        $hours = range(9, 17);

        // Inicializar totales en cero
        $salesByHour = array_fill_keys($hours, 0);

        // Consulta de ventas agrupadas por hora
        $results = HistoricDocument::query()
            ->select([
                DB::raw('HOUR(document_time) as hour'),
                DB::raw('SUM(total_sales_amount) as total'),
            ])
            ->whereDate('document_date', $today)
            ->groupBy(DB::raw('hour'))
            ->orderBy('hour')
            ->get()
            ->pluck('total', 'hour')
            ->toArray();

        // Mezclar resultados en el arreglo completo
        foreach ($results as $hour => $total) {
            $salesByHour[$hour] = (float) $total;
        }

        // Calcular ventas acumuladas
        $cumulative = [];
        $running = 0.0;
        foreach ($hours as $h) {
            $running += $salesByHour[$h];
            $cumulative[] = $running;
        }

        return [
            'labels' => array_map(fn ($h) => sprintf('%02d:00', $h), $hours),
            'datasets' => [
                [
                    'label' => 'Ventas x horas',
                    'data' => array_values($salesByHour),
                ],
                [
                    'label' => 'Ventas acumuladas',
                    'data'  => $cumulative,
                    'borderDash' => [5, 5],
                ],
            ],
        ];
    }
}