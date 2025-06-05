<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WrkTaskResource\Pages;
use App\Models\WrkTask;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class WrkTaskResource extends Resource
{
    protected static ?string $model = WrkTask::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Tareas';
    protected static ?string $modelLabel = 'Tarea';
    protected static ?string $pluralModelLabel = 'Mis Tareas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->rows(3)
                    ->nullable(),

                Forms\Components\Select::make('area_id')
                    ->label('Área')
                    ->relationship('area', 'name')
                    ->searchable()
                    ->nullable(),

                Forms\Components\Select::make('priority')
                    ->label('Prioridad')
                    ->options([
                        'alta' => 'Alta',
                        'media' => 'Media',
                        'baja' => 'Baja',
                    ])
                    ->default('media')
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'en_progreso' => 'En Progreso',
                        'completada' => 'Completada',
                    ])
                    ->default('pendiente')
                    ->required(),

                Forms\Components\DatePicker::make('due_date')
                    ->label('Fecha límite')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Título')->searchable(),
                Tables\Columns\TextColumn::make('area.name')->label('Área'),
                Tables\Columns\TextColumn::make('priority')->label('Prioridad')->badge(),
                Tables\Columns\TextColumn::make('status')->label('Estado')->badge(),
                Tables\Columns\TextColumn::make('due_date')->label('Fecha límite')->date(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'en_progreso' => 'En Progreso',
                        'completada' => 'Completada',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', Auth::id());
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWrkTasks::route('/'),
            'create' => Pages\CreateWrkTask::route('/create'),
            'edit' => Pages\EditWrkTask::route('/{record}/edit'),
        ];
    }

    /**
     * Al guardar, se asigna el user_id automáticamente al usuario actual.
     */
    public static function beforeCreate($record): void
    {
        $record->user_id = Auth::id();
    }

    public static function beforeSave($record): void
    {
        if (! $record->user_id) {
            $record->user_id = Auth::id();
        }
    }
}
