<?php

namespace App\Filament\Bi\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Carbon;
use App\Models\HistoricProduct;
use App\Models\HistoricDocument;

class SalesByDateRange extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static string $view = 'filament.bi.pages.sales-by-date-range';
    protected static ?string $title = 'Ventas por Rango de Fechas';
    protected static ?string $navigationGroup = 'Reportes';

    public ?string $startDate = null;
    public ?string $endDate = null;

    public array $sales = [];

    public function mount(): void
    {
        $this->startDate = now()->toDateString();
        $this->endDate = now()->toDateString();
        $this->loadSales();
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            DatePicker::make('startDate')->label('Desde')->reactive(),
            DatePicker::make('endDate')->label('Hasta')->reactive(),
        ])->statePath('');
    }

    public function updated($property): void
    {
        if (in_array($property, ['startDate', 'endDate'])) {
            $this->loadSales();
        }
    }

    public function loadSales(): void
    {
        $this->sales = \App\Models\HistoricProduct::query()
            ->leftJoin('prd_products', 'historic_products.product_sku', '=', 'prd_products.sku')
            ->leftJoin('prd_categories', 'prd_products.category_id', '=', 'prd_categories.id')
            ->when($this->startDate, fn ($q) => $q->whereDate('document_date', '>=', $this->startDate))
            ->when($this->endDate, fn ($q) => $q->whereDate('document_date', '<=', $this->endDate))
            ->selectRaw('
                historic_products.product_sku,
                historic_products.product_name,
                prd_categories.name as category_name,
                SUM(historic_products.quantity) as total_qty,
                SUM(historic_products.total_sales_amount) as total_sales,
                prd_products.stock as stock
            ')
            ->groupBy('historic_products.product_sku', 'historic_products.product_name', 'prd_products.category_id')
            ->orderByDesc('category_name','stock')
            ->limit(200)
            ->get()
            ->toArray();
    }

}
