<?php

namespace App\Filament\Bi\Widgets;

use Filament\Widgets\TableWidget;
use Filament\Tables;
use App\Models\PrdProduct;
use App\Models\HistoricProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Carbon\Carbon;

class LowStockAlertsTable extends TableWidget
{
    protected static ?string $heading = 'Low Stock Alerts';

    /**
     * Umbral de stock bajo. Ajusta según necesidad.
     *
     * @var int
     */
    protected int $lowStockThreshold = 5;

    /**
     * Get the base query for low stock products after today's sales.
     *
     * @return Builder|Relation|null
     */
    public function getTableQuery(): Builder|Relation|null
    {
        $today = Carbon::today();

        // Subconsulta para ventas de hoy por SKU
        $salesToday = HistoricProduct::query()
            ->select([
                'product_sku',
                DB::raw('SUM(quantity) as sold_today'),
            ])
            ->whereDate('document_date', $today)
            ->groupBy('product_sku');

        return PrdProduct::query()
            ->select([
                'prd_products.sku as product_sku',
                'prd_products.name as product_name',
                'prd_products.stock as initial_stock',
                DB::raw('COALESCE(sales.sold_today, 0) as sold_today'),
                DB::raw('(prd_products.stock - COALESCE(sales.sold_today, 0)) as stock_after'),
            ])
            ->leftJoinSub($salesToday, 'sales', function ($join) {
                $join->on('sales.product_sku', '=', 'prd_products.sku');
            })
            ->having('stock_after', '<=', $this->lowStockThreshold)
            ->orderBy('stock_after', 'asc');
    }

    protected function getDefaultTableRecordsPerPage(): ?int
    {
        return 10;
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('product_sku')
                ->label('SKU')
                ->searchable(),

            Tables\Columns\TextColumn::make('product_name')
                ->label('Product')
                ->searchable()
                ->wrap(),

            Tables\Columns\TextColumn::make('initial_stock')
                ->label('Initial Stock')
                ->sortable(),

            Tables\Columns\TextColumn::make('sold_today')
                ->label('Sold Today')
                ->sortable(),

            Tables\Columns\TextColumn::make('stock_after')
                ->label('Stock After')
                ->sortable(),
        ];
    }

    /**
     * Use SKU as record key.
     */
    public function getTableRecordKey($record): string
    {
        return (string) $record->product_sku;
    }
}
