<?php

namespace App\Filament\Bi\Widgets;

use Filament\Widgets\TableWidget;
use Filament\Tables;
use App\Models\HistoricProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Carbon\Carbon;

class TopProductsTodayTable extends TableWidget
{
    protected static ?string $heading = 'Top Products Today';

    /**
     * Get the base query for the table.
     *
     * @return Builder|Relation|null
     */
    protected function getTableQuery(): Builder|Relation|null
    {
        $today = Carbon::today();

        return HistoricProduct::query()
            ->select([
                'historic_products.product_sku',
                'historic_products.product_name',
                DB::raw('SUM(historic_products.quantity) as total_quantity'),
                DB::raw('SUM(historic_products.total_sales_amount) as total_sales'),
                'prd_products.stock',
            ])
            ->join('prd_products', 'prd_products.sku', '=', 'historic_products.product_sku')
            ->whereDate('historic_products.document_date', $today)
            ->groupBy([
                'historic_products.product_sku',
                'historic_products.product_name',
                'prd_products.stock',
            ])
            ->orderByDesc('total_sales');
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

            Tables\Columns\TextColumn::make('total_quantity')
                ->label('Qty Sold')
                ->sortable()
                ->formatStateUsing(fn ($state) => number_format($state)),

            Tables\Columns\TextColumn::make('total_sales')
                ->label('Sales')
                ->sortable()
                ->formatStateUsing(fn ($state) => '$' . number_format($state, 0,",",".")),

            Tables\Columns\TextColumn::make('stock')
                ->label('Stock')
                ->sortable(),
        ];
    }

    /**
     * Override record key to use SKU, since the query has no id.
     */
    public function getTableRecordKey($record): string
    {
        return (string) $record->product_sku;
    }
}
