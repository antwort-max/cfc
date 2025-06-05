<?php

namespace App\Filament\Bi\Widgets;

use Filament\Widgets\Widget;
use App\Models\HistoricProduct;
use Illuminate\Support\Facades\DB;

class TopSellingProductsReport extends Widget
{
    protected static string $view = 'filament.bi.widgets.top-selling-products-report';

    public function getData(): array
    {
        $periods = [30, 60];
        $data = [];

        foreach ($periods as $days) {

            $topSellingForPeriod = HistoricProduct::query()
                ->select([
                    'historic_products.product_sku',
                    'historic_products.product_name',
                    DB::raw('SUM(historic_products.total_sales_amount) as total_sold'),
                    'prd_products.stock as stock',
                ])
                ->join('prd_products', 'prd_products.sku', '=', 'historic_products.product_sku')
                ->where('historic_products.document_date', '>=', now()->subDays($days))
                ->groupBy([
                    'historic_products.product_sku',
                    'historic_products.product_name',
                    'prd_products.stock',
                ])
                ->orderByDesc('total_sold')
                ->limit(25)
                ->get();

            $sumOfTotalSoldForThisPeriod = $topSellingForPeriod->sum('total_sold');

            $data[$days] = [
                'items' => $topSellingForPeriod,
                'period_total_sales' => $sumOfTotalSoldForThisPeriod
            ];
        }

        return ['data' => $data];
    }

    protected function getViewData(): array
    {
        return array_merge(parent::getViewData(), $this->getData());
    }
}
