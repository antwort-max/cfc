<?php

namespace App\Filament\Bi\Resources;

use App\Models\BiDailyStockSnapshot;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\DateFilter;
use Filament\Tables\Filters\Filter;        
use Filament\Forms\Components\DatePicker;

class BiDailyStockSnapshotResource extends Resource
{
    protected static ?string $model = BiDailyStockSnapshot::class;

    protected static ?string $navigationIcon  = 'heroicon-o-document-magnifying-glass';
    protected static ?string $navigationLabel = 'Stock Diario';
    protected static ?string $navigationGroup = 'Business Intelligence';
    protected static ?string $slug            = 'daily-stock';

    public static function canCreate(): bool                { return false; }
    public static function canEdit(Model $record): bool     { return false; }
    public static function canDelete(Model $record): bool   { return false; }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('snapshot_date')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('product_sku')
                    ->label('SKU')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('stock')
                    ->numeric(2)
                    ->label('Stock'),

                Tables\Columns\TextColumn::make('cost')
                    ->money('CLP', true)
                    ->label('Costo'),

                Tables\Columns\TextColumn::make('price')
                    ->money('CLP', true)
                    ->label('Precio'),

                Tables\Columns\TextColumn::make('value')
                    ->money('CLP', true)
                    ->label('Valor'),
            ])
            ->filters([
            
            ])
            ->defaultSort('snapshot_date', 'desc')
            ->actions([])       // sin botones por fila
            ->bulkActions([]);  // sin acciones masivas
    }

    public static function getPages(): array
    {
        return [
            'index' => BiDailyStockSnapshotResource\Pages\ListBiDailyStockSnapshots::route('/'),
        ];
    }
}