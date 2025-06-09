<?php

namespace App\Filament\Ecommerce\Resources\CartResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager; // Changed base class import
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;


class CartItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $recordTitleAttribute = 'name';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('sku')->label('SKU')->disabled(),
                TextInput::make('name')->label('Nombre')->disabled(),
                TextInput::make('quantity')->label('Cantidad')->disabled(),
                TextInput::make('package_price')->label('Precio Paquete')->disabled(),
                TextInput::make('package_qty')->label('Cant. por Paquete')->disabled(),
                TextInput::make('package_unit')->label('Unidad de Paquete')->disabled(),
                TextInput::make('quantity')->label('Total Unidades')->disabled(),
            ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('sku')->label('SKU'),
                TextColumn::make('name')->label('Producto'),
                TextColumn::make('quantity')->label('Cantidad'),
                TextColumn::make('package_price')->label('Precio Paquete')->money('CLP'),
                TextColumn::make('created_at')->label('Agregado')->dateTime(),
            ])
            ->filters([])
            ->headerActions([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
