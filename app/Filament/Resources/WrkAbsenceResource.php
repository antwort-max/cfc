<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WrkAbsenceResource\Pages;
use App\Models\WrkAbsence;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WrkAbsenceResource extends Resource
{
    protected static ?string $model = WrkAbsence::class;

    protected static ?string $navigationGroup = 'Colaboradores';

    // Etiquetas en español para el recurso
    protected static ?string $label = 'Ausencia de Colaborador';
    protected static ?string $pluralLabel = 'Ausencias de Colaboradores';

    // Texto que aparece en la navegación
    protected static ?string $navigationLabel = 'Ausencias de Colaboradores';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Selector para elegir empleado
                Forms\Components\Select::make('employee_id')
                    ->relationship('employee', 'name')
                    ->label('Empleado')
                    ->searchable()
                    ->required(),

                // Fecha de la ausencia
                Forms\Components\DatePicker::make('date')
                    ->label('Fecha')
                    ->required(),

                // Detalle de la ausencia
                Forms\Components\RichEditor::make('reason')
                    ->label('Razón')
                    ->columnSpanFull(),

                // Porcentaje o monto de descuento
                Forms\Components\TextInput::make('discount')
                    ->label('Descuento')
                    ->numeric()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Muestra el nombre del empleado
                Tables\Columns\TextColumn::make('employee.name')
                    ->label('Empleado')
                    ->sortable()
                    ->searchable(),

                // Fecha de la ausencia
                Tables\Columns\TextColumn::make('date')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),

                // Descuento aplicado
                Tables\Columns\TextColumn::make('discount')
                    ->label('Descuento')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->searchable(),

                // Timestamps
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([
                // Opcional: agregar filtros personalizados
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
            // Definir RelationManagers si es necesario
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListWrkAbsences::route('/'),
            'create' => Pages\CreateWrkAbsence::route('/create'),
            'edit'   => Pages\EditWrkAbsence::route('/{record}/edit'),
        ];
    }
}
