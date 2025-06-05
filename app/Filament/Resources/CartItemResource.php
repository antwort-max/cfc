<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CartItemResource\Pages;
use App\Models\EcoCartItem;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CartItemResource extends Resource
{
    protected static ?string $model = EcoCartItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'E-commerce';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('cart_id')
                    ->relationship('cart', 'id')
                    ->label('Carrito')
                    ->searchable()
                    ->required(),
                TextInput::make('sku')
                    ->label('SKU')
                    ->required(),
                TextInput::make('name')
                    ->label('Nombre')
                    ->required(),
                TextInput::make('package_unit')
                    ->label('Unidad de paquete'),
                TextInput::make('package_qty')
                    ->label('Cant. por paquete')
                    ->numeric(),
                TextInput::make('package_price')
                    ->label('Precio por paquete')
                    ->numeric(),
                TextInput::make('quantity')
                    ->label('Cantidad total')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('cart_id')->label('Cart ID')->sortable(),
                TextColumn::make('sku')->label('SKU')->searchable(),
                TextColumn::make('name')->label('Producto')->searchable(),
                TextColumn::make('quantity')->label('Cantidad'),
                TextColumn::make('package_unit')->label('Unidad'),
                TextColumn::make('package_price')->label('Precio Unit.')->money('CLP'),
                TextColumn::make('created_at')->label('Agregado')->dateTime(),
            ])
            ->filters([
                // Define filters if needed
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCartItems::route('/'),
            'create' => Pages\CreateCartItem::route('/create'),
            'edit'   => Pages\EditCartItem::route('/{record}/edit'),
        ];
    }
}
