<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupSupplierResource\Pages;
use App\Models\SupSupplier;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;

class SupSupplierResource extends Resource
{
    protected static ?string $model = SupSupplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationLabel = 'Proveedores';
    protected static ?string $navigationGroup = 'Gestión de compras';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->label('Correo electrónico')
                    ->email()
                    ->nullable(),
                Forms\Components\TextInput::make('phone')
                    ->label('Teléfono')
                    ->nullable(),
                Forms\Components\Select::make('brand_code')
                    ->label('Marca asociada')
                    ->relationship('brand', 'name')
                    ->searchable(),
                Forms\Components\Select::make('origin')
                    ->label('Origen')
                    ->options([
                        'nacional'     => 'Nacional',
                        'importacion'  => 'Importación',
                    ])
                    ->required(),
                Forms\Components\Select::make('type')
                    ->label('Tipo de proveedor')
                    ->options([
                        'productos' => 'Productos',
                        'insumos'   => 'Insumos',
                        'activos'   => 'Activos',
                        'servicios' => 'Servicios',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nombre')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('Correo'),
                Tables\Columns\TextColumn::make('phone')->label('Teléfono'),
                Tables\Columns\TextColumn::make('brand.name')->label('Marca'),
                Tables\Columns\BadgeColumn::make('origin')->label('Origen'),
                Tables\Columns\BadgeColumn::make('type')->label('Tipo'),
            ])
            ->filters([])
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
            'index' => Pages\ListSupSuppliers::route('/'),
            'create' => Pages\CreateSupSupplier::route('/create'),
            'edit' => Pages\EditSupSupplier::route('/{record}/edit'),
        ];
    }
}
