<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupMeetingResource\Pages;
use App\Models\SupMeeting;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;


class SupMeetingResource extends Resource
{
    protected static ?string $model = SupMeeting::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Gestión de compras';
    protected static ?string $navigationLabel = 'Reuniones con proveedores';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('supplier_id')
                ->label('Proveedor')
                ->relationship('supplier', 'name')
                ->searchable()
                ->required(),

            Forms\Components\DatePicker::make('date')
                ->label('Fecha de la reunión')
                ->required(),

            Forms\Components\TextInput::make('title')
                ->label('Título')
                ->required(),

            Forms\Components\RichEditor::make('notes')
                ->label('Notas')
                ->toolbarButtons([
                    'bold',
                    'italic',
                    'underline',
                    'bulletList',
                    'orderedList',
                    'link',
                    'undo',
                    'redo',
                ])
                ->columnSpan('full')
                ->maxLength(10000),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('supplier.name')->label('Proveedor')->searchable(),
                Tables\Columns\TextColumn::make('date')->label('Fecha')->date(),
                Tables\Columns\TextColumn::make('title')->label('Título')->limit(40),
                Tables\Columns\TextColumn::make('user.name')->label('Registrado por')->sortable(),
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
            'index' => Pages\ListSupMeetings::route('/'),
            'create' => Pages\CreateSupMeeting::route('/create'),
            'edit' => Pages\EditSupMeeting::route('/{record}/edit'),
        ];
    }
}
