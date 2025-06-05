<?php

namespace App\Filament\Bi\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\HistoricProduct;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DailySalesSummary extends BaseWidget
{
    // El heading debe ser no estático porque la propiedad en la clase base es no estática
    protected ?string $heading = 'Daily Sales Summary';

    protected function getCards(): array
    {
        $today = Carbon::today();

        // 1) Total sales amount for today
        $totalSales = HistoricProduct::query()
            ->whereDate('document_date', $today)
            ->sum('total_sales_amount');

        // 2) Number of transactions (distinct documents) today
        $transactionCount = HistoricProduct::query()
            ->whereDate('document_date', $today)
            ->distinct('document_number')
            ->count('document_number');

        // 3) Average order value (AOV)
        $averageOrder = $transactionCount > 0
            ? round($totalSales / $transactionCount, 2)
            : 0;

        return [
            Card::make('Total Sales', "$" . number_format($totalSales, 2, '.', ','))
                ->description('Today'),

            Card::make('Transactions', (string) $transactionCount)
                ->description('Orders Today'),

            Card::make('Avg. Order Value', "$" . number_format($averageOrder, 2, '.', ','))
                ->description('AOV'),
        ];
    }
}
