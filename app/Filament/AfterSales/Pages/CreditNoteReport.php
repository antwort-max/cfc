<?php

namespace App\Filament\AfterSales\Pages;

use App\Models\HistoricDocument;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Forms;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;

class CreditNoteReport extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string $view = 'filament.after-sales.pages.credit-note-report';
    public static ?string $navigationIcon = 'heroicon-o-chart-bar';
    public static ?string $navigationLabel = 'Notas de Crédito';
    protected static ?string $title = 'Notas de Crédito';

    protected function getTableQuery()
    {
        return HistoricDocument::query()
            ->where('document_type', 'NCV');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('document_date')->label('Fecha')->date(),
            TextColumn::make('document_number')->label('Folio'),
            TextColumn::make('client')->label('Rut Cliente'),
            TextColumn::make('place')->label('Cod Lugar'),
            TextColumn::make('seller')->label('Cod Vendedor'),
            TextColumn::make('total_sales_amount')->label('Monto')
                ->formatStateUsing(fn (int $state): string => '$' . number_format($state, 0, ',', '.'))
                ->sortable(),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            Filter::make('folio')
                ->form([
                    TextInput::make('document_number')->label('Folio'),
                ])
                ->query(fn($query, array $data) => $data['document_number']
                    ? $query->where('document_number', 'like', "%{$data['document_number']}%")
                    : $query),
            Filter::make('date_range')
                ->form([
                    DatePicker::make('from')->label('From'),
                    DatePicker::make('until')->label('Until'),
                ])
                ->query(fn($query, array $data) => $query
                    ->when($data['from'], fn($q) => $q->whereDate('document_date', '>=', $data['from']))
                    ->when($data['until'], fn($q) => $q->whereDate('document_date', '<=', $data['until']))),
        ];
    }
}
