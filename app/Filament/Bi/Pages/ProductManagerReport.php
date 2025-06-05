<?php

namespace App\Filament\Bi\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Facades\DB;

class ProductManagerReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';
    protected static ?string $navigationGroup = 'Reportes';
    protected static ?string $title = 'Informe por Categoría';
    protected static string $view = 'filament.bi.pages.product-manager-report';

    public ?string $startDate = null;
    public ?string $endDate = null;

    public function mount(): void
    {
        $this->startDate = now()->startOfMonth()->toDateString();
        $this->endDate = now()->toDateString();
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            DatePicker::make('startDate')->label('Desde')->reactive(),
            DatePicker::make('endDate')->label('Hasta')->reactive(),
        ])->statePath('');
    }

    public function getCategorySalesProperty()
    {
        return DB::table('historic_products')
            ->join('prd_products', 'historic_products.product_sku', '=', 'prd_products.sku')
            ->join('prd_categories', 'prd_products.category_id', '=', 'prd_categories.id')
            ->selectRaw('
                prd_categories.id as category_id,
                prd_categories.name as category_name,
                SUM(historic_products.quantity * historic_products.product_price) as total_sales,
                SUM(historic_products.quantity) as total_quantity,
                SUM(historic_products.quantity * historic_products.product_cost) as total_cost,
                SUM(historic_products.quantity * (historic_products.product_price - historic_products.product_cost)) as total_margin
            ')
            ->whereBetween('historic_products.document_date', [$this->startDate, $this->endDate])
            ->groupBy('prd_categories.id', 'prd_categories.name')
            ->orderByDesc('total_sales')
            ->get();
    }
}

