<?php

namespace App\Filament\Ecommerce\Resources;

use App\Filament\Ecommerce\Resources\CusCustomerResource\Pages;
use App\Filament\Ecommerce\Resources\CusCustomerResource\RelationManagers;
use App\Models\CusCustomer;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CusCustomerResource extends Resource
{
    protected static ?string $model = CusCustomer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Clientes';
    protected static ?string $navigationGroup = 'E-commerce';
    protected static ?int $navigationSort = 1;

    public static function getLabel(): string
    {
        return 'Cliente';
    }

    public static function getPluralLabel(): string
    {
        return 'Clientes';
    }

    public static function getNavigationLabel(): string
    {
        return static::getPluralLabel();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('rut')
                    ->label('RUT')
                    ->unique(ignoreRecord: true),
                TextInput::make('first_name')
                    ->label('Nombre')
                    ->required(),
                TextInput::make('last_name')
                    ->label('Apellido')
                    ->required(),
                TextInput::make('email')
                    ->email()
                    ->label('Email')
                    ->unique(ignoreRecord: true),
                TextInput::make('phone')
                    ->label('Teléfono'),
                TextInput::make('mobile')
                    ->label('Móvil'),
                TextInput::make('company')
                    ->label('Empresa'),
                Textarea::make('notes')
                    ->label('Notas'),
                Select::make('status')
                    ->label('Estado')
                    ->options([
                        'prospect' => 'Prospecto',
                        'active'   => 'Activo',
                        'inactive' => 'Inactivo',
                        'blocked'  => 'Bloqueado',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('rut')->label('RUT')->searchable(),
                TextColumn::make('first_name')->label('Nombre')->searchable()->sortable(),
                TextColumn::make('last_name')->label('Apellido')->searchable()->sortable(),
                TextColumn::make('email')->label('Email')->searchable(),
                TextColumn::make('mobile')->label('WhatsApp')->searchable(),
                BadgeColumn::make('status')
                    ->label('Estado')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'prospect' => 'Prospecto',
                        'active'   => 'Activo',
                        'inactive' => 'Inactivo',
                        'blocked'  => 'Bloqueado',
                        default    => $state,
                    })
                    ->colors([
                        'warning'   => 'prospect',
                        'success'   => 'active',
                        'secondary' => 'inactive',
                        'danger'    => 'blocked',
                    ]),
                TextColumn::make('created_at')->label('Creado')->dateTime(),
            ])
            ->filters([
                // Define filters if needed
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Define relation managers
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'   => Pages\ListCustomers::route('/'),
            'create'  => Pages\CreateCustomer::route('/create'),
            'edit'    => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
