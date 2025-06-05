<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WrkDelayResource\Pages;
use App\Models\WrkDelay;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WrkDelayResource extends Resource
{
    protected static ?string $model = WrkDelay::class;

    protected static ?string $navigationGroup = 'Colaboradores';

    protected static ?string $label = 'Atraso de Colaborador';
    protected static ?string $pluralLabel = 'Atrasos de Colaboradores';

    protected static ?string $navigationLabel = 'Atrasos de Colaboradores';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Selector que muestra el nombre del empleado
                Forms\Components\Select::make('employee_id')
                    ->relationship('employee', 'name')
                    ->label('Empleado')
                    ->searchable()
                    ->required(),

                Forms\Components\DatePicker::make('delay_date')
                    ->label('Fecha')
                    ->required(),

                Forms\Components\TimePicker::make('delay_time')
    		    ->label('Hora')
                    ->required()
                    ->seconds(false),

                Forms\Components\RichEditor::make('reason')
                    ->label('Razón')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Columna que muestra el nombre del empleado relacionado
                Tables\Columns\TextColumn::make('employee.name')
                    ->label('Empleado')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('delay_date')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('delay_time')
                    ->label('Hora')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // ...
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWrkDelays::route('/'),
            'create' => Pages\CreateWrkDelay::route('/create'),
            'edit' => Pages\EditWrkDelay::route('/{record}/edit'),
        ];
    }
}
