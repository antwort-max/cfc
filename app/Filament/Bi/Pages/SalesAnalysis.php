<?php

namespace App\Filament\Bi\Pages;

use Filament\Pages\Page;
use App\Filament\Bi\Widgets\SalesBySellerDaily;
//use App\Filament\Bi\Widgets\SalesBySellerWeekly;
use App\Filament\Bi\Widgets\SalesBySellerMonthly;

class SalesAnalysis extends Page
{
    protected static string $view = 'filament.bi.pages.sales-analysis';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Analisis de Vendedores';
    protected static ?string $title = 'Analisis de Vendedores';
    protected static ?string $navigationGroup = 'Análisis';

    protected function getWidgets(): array
    {
        return [
            SalesBySellerDaily::class,
           // SalesBySellerWeekly::class,
            SalesBySellerMonthly::class,
        ];
    }
}