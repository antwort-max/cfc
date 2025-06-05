<?php

namespace App\Filament\Resources\HistoricDocumentResource\Widgets;

use App\Models\HistoricDocument;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class HistoricSalesPerHourChart extends ChartWidget
{
    protected static ?string $heading = 'Ventas por franja horaria';
    protected int|string|array $columnSpan = 'full';

    /* ---------- tipo de gráfico ---------- */
    protected function getType(): string
    {
        return 'bar';
    }

    /* ---------- filtros simples ---------- */
    protected function getFilters(): ?array
    {
        return [
            'today'     => 'Hoy',
            'yesterday' => 'Ayer',
            'last_7'    => 'Últimos 7 días',
            'last_30'   => 'Últimos 30 días',
            'last_90'   => 'Ultimos 90 días',
        ];
    }

    /* ---------- datos para Chart.js ---------- */
    protected function getData(): array
    {
        // Elegimos rango según el filtro activo (o "today" por defecto)
        [$from, $until] = match ($this->filter) {
            'yesterday' => [now()->subDay()->startOfDay(),      now()->subDay()->endOfDay()],
            'last_7'    => [now()->subDays(6)->startOfDay(),    now()->endOfDay()],
            'last_30'   => [now()->subDays(29)->startOfDay(),   now()->endOfDay()],
            'last_90'   => [now()->subDays(89)->startOfDay(),   now()->endOfDay()],
            default     => [now()->startOfDay(),                now()->endOfDay()], // today
        };

        // Consulta agrupada por hora (ajusta para SQL Server si es tu caso)
        $sales = HistoricDocument::query()
            ->whereBetween('document_date', [$from->toDateString(), $until->toDateString()])
            ->selectRaw(
                app('db')->getDriverName() === 'sqlsrv'
                    ? 'DATEPART(HOUR, document_time) as hour,
                       SUM(total_sales_amount_with_tax) as total'
                    : 'HOUR(document_time) as hour,
                       SUM(total_sales_amount_with_tax) as total'
            )
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('total', 'hour'); // [hour => total]

        // Rellenamos horas sin ventas
        $hours  = collect(range(0, 23));
        $totals = $hours->map(fn ($h) => $sales[$h] ?? 0);

        return [
            'labels'   => $hours->map(fn ($h) => sprintf('%02d:00', $h))->toArray(),
            'datasets' => [
                [
                    'label' => 'Ventas (CLP)',
                    'data'  => $totals,
                ],
            ],
        ];
    }

    
}
