<?php

namespace App\Filament\Bi\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SalesBySellerTable extends BaseWidget
{
    protected static ?string $heading = 'Informe de Ventas por Vendedor';

    protected function getTableQuery(): Builder
    {
        $startOfMonth = Carbon::now()->today();
        $endOfMonth = Carbon::now()->today();

        $subquery = DB::table('historic_documents as hd')
            ->join('wrk_employees as e', 'hd.seller', '=', 'e.code')
            ->selectRaw('
                e.name as seller_name,
                hd.document_type,
                COUNT(*) as total_docs,
                SUM(hd.total_sales_amount) as total_sales,
                MIN(hd.document_number) as id
            ')
            ->whereDate('hd.document_date', '>=', $startOfMonth)
            ->whereDate('hd.document_date', '<=', $endOfMonth)
            ->groupBy('e.name', 'hd.document_type');

        return (new class extends Model {
            protected $table = 'sales_by_seller';
            public $timestamps = false;
            protected $guarded = [];
        })->newQuery()->fromSub($subquery, 'sales_by_seller');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('seller_name')
                ->label('Vendedor')
                ->searchable()
                ->sortable(),

            TextColumn::make('document_type')
                ->label('Tipo de Documento')
                ->searchable()
                ->sortable(),

            TextColumn::make('total_docs')
                ->label('N° Documentos')
                ->sortable(),

            TextColumn::make('total_sales')
                ->label('Total Vendido')
                ->money('CLP', true)
                ->sortable(),
        ];
    }
}
