<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WrkAbandonmentResource\Pages;
use App\Models\WrkAbandonment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WrkAbandonmentResource extends Resource
{
    protected static ?string $model = WrkAbandonment::class;

    protected static ?string $navigationGroup = 'Colaboradores';

    // Etiquetas en español para el recurso
    protected static ?string $label = 'Salida de Colaborador';
    protected static ?string $pluralLabel = 'Salidas de Colaboradores';

    // Texto que aparece en la navegación
    protected static ?string $navigationLabel = 'Salidas de Colaboradores';
    protected static ?string $navigationIcon = 'heroicon-o-clock';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('employee_id')
                    ->relationship('employee', 'name')
                    ->label('Empleado')
                    ->searchable()
                    ->required(),

                Forms\Components\DatePicker::make('date')
                    ->label('Fecha')
                    ->required(),

                Forms\Components\TimePicker::make('start_time')
                    ->label('Hora Inicio')
                    ->required(),

                Forms\Components\TimePicker::make('finish_time')
                    ->label('Hora Fin')
                    ->required(),

                Forms\Components\RichEditor::make('reason')
                    ->label('Razón')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.name')
                    ->label('Empleado')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('date')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_time')
                    ->label('Hora Inicio')
                    ->sortable(),

                Tables\Columns\TextColumn::make('finish_time')
                    ->label('Hora Fin')
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
            'index'  => Pages\ListWrkAbandonments::route('/'),
            'create' => Pages\CreateWrkAbandonment::route('/create'),
            'edit'   => Pages\EditWrkAbandonment::route('/{record}/edit'),
        ];
    }
}
