<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WrkDiaryResource\Pages;
use App\Models\WrkDiary;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WrkDiaryResource extends Resource
{
    protected static ?string $model = WrkDiary::class;

    protected static ?string $navigationGroup = 'Colaboradores';

    // Etiquetas en español para el recurso
    protected static ?string $label = 'Registro de Bitácora';
    protected static ?string $pluralLabel = 'Bitácoras de Colaboradores';

    // Texto que aparece en la navegación
    protected static ?string $navigationLabel = 'Bitácoras de Colaboradores';
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

                // Fecha de la entrada en bitácora
                Forms\Components\DatePicker::make('date')
                    ->label('Fecha')
                    ->required(),

                // Título del registro
                Forms\Components\TextInput::make('name')
                    ->label('Título')
                    ->maxLength(255)
                    ->required(),

                // Descripción detallada
                Forms\Components\RichEditor::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),

                // Archivo adjunto
                Forms\Components\FileUpload::make('attachment')
                    ->label('Adjunto')
                    ->disk('public')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Nombre del empleado
                Tables\Columns\TextColumn::make('employee.name')
                    ->label('Empleado')
                    ->sortable()
                    ->searchable(),

                // Fecha de la bitácora
                Tables\Columns\TextColumn::make('date')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),

                // Título
                Tables\Columns\TextColumn::make('name')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),

                // Nombre del archivo adjunto
                Tables\Columns\TextColumn::make('attachment')
                    ->label('Adjunto')
                    ->limit(30)
                    ->searchable(),

                // Fechas de auditoría
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
                // Opcional: filtros de fecha, empleado, etc.
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
            // Definir RelationManagers si aplica
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListWrkDiaries::route('/'),
            'create' => Pages\CreateWrkDiary::route('/create'),
            'edit'   => Pages\EditWrkDiary::route('/{record}/edit'),
        ];
    }
}
