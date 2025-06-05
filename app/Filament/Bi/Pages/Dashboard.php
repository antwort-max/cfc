<?php

namespace App\Filament\Bi\Pages;

use Filament\Pages\Page;
use App\Filament\Bi\Widgets\SalesByPlacePie;
use App\Filament\Bi\Widgets\SalesDailyLine;
use App\Filament\Bi\Widgets\MonthlyComparisonMetrics;

class Dashboard extends Page
{
    protected static string $view = 'filament.bi.pages.dashboard';
    protected static ?string $navigationIcon  = 'heroicon-o-chart-pie';
    protected static ?string $navigationGroup = 'Análisis';
    protected static ?string $navigationLabel = 'Dashboard BI';

    // Filtro común (días)
    public int $days = 30;

    public function submit()
    {
        $this->validate([
            'days' => ['required','integer','min:1'],
        ]);
    }

    protected function getHeaderWidgets(): array
    {
        return [
            MonthlyComparisonMetrics::class,
            SalesDailyLine::make(['days' => $this->days]),
        ];
    }
}