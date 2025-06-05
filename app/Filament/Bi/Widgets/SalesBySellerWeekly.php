<?php

namespace App\Filament\Bi\Widgets;

use Filament\Widgets\ChartWidget;

class SalesBySellerWeekly extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
