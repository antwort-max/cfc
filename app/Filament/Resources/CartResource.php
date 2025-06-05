<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CartResource\Pages;
use App\Filament\Resources\CartResource\RelationManagers;
use App\Models\EcoCart;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;

class CartResource extends Resource
{
    protected static ?string $model = EcoCart::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Carritos';
    protected static ?string $navigationGroup = 'E-commerce';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('customer_id')
                    ->relationship('customer', 'first_name')
                    ->label('Cliente')
                    ->searchable()
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Usuario')
                    ->searchable(),
                Select::make('status')
                    ->label('Estado')
                    ->options([
                        'open' => 'Abierto',
                        'converted' => 'Convertido',
                        'abandoned' => 'Abandonado',
                    ])
                    ->required(),
                TextInput::make('ip_address')->label('IP')->disabled(),
                Textarea::make('explanation')->label('Notas'),
                TextInput::make('total_quantity')->label('Total Unidades')->disabled(),
                TextInput::make('amount')->label('Monto')->disabled()->numeric(),
                TextInput::make('taxes')->label('Impuestos')->disabled()->numeric(),
                TextInput::make('discount')->label('Descuento')->disabled()->numeric(),
                TextInput::make('shipping_cost')->label('Costo Envío')->disabled()->numeric(),
                TextInput::make('currency')->label('Moneda')->disabled(),
                Select::make('send_method')
                    ->label('Método Envío')
                    ->options([
                        'email' => 'Email',
                        'printed' => 'Impreso',
                        'whatsapp' => 'WhatsApp',
                    ]),
                DateTimePicker::make('converted_at')
                    ->label('Convertido En')
                    ->nullable()
                    ->disabled(),
                DateTimePicker::make('created_at')->label('Creado En')->disabled(),
                DateTimePicker::make('updated_at')->label('Actualizado En')->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('customer.first_name')->label('Cliente')->searchable(),
                TextColumn::make('user.name')->label('Usuario')->searchable(),
                BadgeColumn::make('status')
                    ->label('Estado')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'open' => 'Abierto',
                        'converted' => 'Convertido',
                        'abandoned' => 'Abandonado',
                        default => $state,
                    })
                    ->colors([
                        'warning' => 'open',
                        'success' => 'converted',
                        'danger' => 'abandoned',
                    ]),
                TextColumn::make('total_quantity')->label('Unidades')->sortable(),
                TextColumn::make('amount')->label('Monto')->money('CLP'),
                TextColumn::make('created_at')->label('Creado')->dateTime()->sortable(),
            ])
            ->filters([
                // add any filters here
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CartItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCarts::route('/'),
            'create' => Pages\CreateCart::route('/create'),
            'edit' => Pages\EditCart::route('/{record}/edit'),
        ];
    }
}
