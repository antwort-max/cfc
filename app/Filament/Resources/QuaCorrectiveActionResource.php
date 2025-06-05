<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuaCorrectiveActionResource\Pages;
use App\Models\QuaCorrectiveAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class QuaCorrectiveActionResource extends Resource
{
    protected static ?string $model = QuaCorrectiveAction::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationGroup = 'Calidad';
    protected static ?string $modelLabel = 'Acción Correctiva';
    protected static ?string $pluralModelLabel = 'Acciones Correctivas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('nonconformity_id')
                    ->label('No Conformidad')
                    ->relationship('nonconformity', 'name')
                    ->searchable()
                    ->required(),
                
                Forms\Components\RichEditor::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),

                Forms\Components\Select::make('responsible_id')
                    ->label('Responsable')
                    ->relationship('responsible', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Forms\Components\DatePicker::make('start_date')
                    ->label('Fecha Inicio')
                    ->nullable(),

                Forms\Components\DatePicker::make('end_date')
                    ->label('Fecha Término')
                    ->nullable(),

                Forms\Components\Select::make('status')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'en_proceso' => 'En Proceso',
                        'completada' => 'Completada',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nonconformity.name')->label('No Conformidad'),
                Tables\Columns\TextColumn::make('responsible.name')->label('Responsable'),
                Tables\Columns\TextColumn::make('start_date')->label('Inicio')->date(),
                Tables\Columns\TextColumn::make('end_date')->label('Término')->date(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'danger' => 'pendiente',
                        'warning' => 'en_proceso',
                        'success' => 'completada',
                    ])
                    ->label('Estado'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'en_proceso' => 'En Proceso',
                        'completada' => 'Completada',
                    ]),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuaCorrectiveActions::route('/'),
            'create' => Pages\CreateQuaCorrectiveAction::route('/create'),
            'edit' => Pages\EditQuaCorrectiveAction::route('/{record}/edit'),
        ];
    }
}

