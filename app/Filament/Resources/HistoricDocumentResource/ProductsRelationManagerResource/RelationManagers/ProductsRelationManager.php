<?php

namespace App\Filament\Resources\HistoricDocumentResource\ProductsRelationManagerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';
    protected static ?string $recordTitleAttribute = 'product_name';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product_sku')->label('SKU'),
                Tables\Columns\TextColumn::make('product_name')
                    ->limit(30)
                    ->label('Producto'),
                Tables\Columns\TextColumn::make('product_unit')->label('Unidad'),
                Tables\Columns\TextColumn::make('quantity')->label('Cantidad'),
                Tables\Columns\TextColumn::make('total_sales_amount')
                    ->money('CLP', true)
                    ->label('Total Neto'),
            ])
            ->headerActions([])    // opcional: quitar botones de creación
            ->actions([])          // opcional: quitar acciones por fila
            ->bulkActions([]);     // opcional: quitar acciones masivas
    }

}
