<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuaCommitteeFollowupResource\Pages;
use App\Models\QuaCommitteeFollowup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class QuaCommitteeFollowupResource extends Resource
{
    protected static ?string $model = QuaCommitteeFollowup::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';
    protected static ?string $navigationGroup = 'Calidad';
    protected static ?string $modelLabel = 'Seguimiento de Acción';
    protected static ?string $pluralModelLabel = 'Seguimientos de Acciones';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('action_id')
                    ->label('Acción de Comité')
                    ->relationship('action', 'description')
                    ->searchable()
                    ->nullable(),

                Forms\Components\Textarea::make('notes')
                    ->label('Notas de Seguimiento')
                    ->rows(4)
                    ->nullable(),

                Forms\Components\Select::make('status')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'en_proceso' => 'En Proceso',
                        'completado' => 'Completado',
                    ])
                    ->nullable(),
                
                Forms\Components\DatePicker::make('followup_date')
                    ->label('Fecha Seguimiento')
                    ->required(),
                    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('action.description')->label('Acción'),
                Tables\Columns\TextColumn::make('status')->label('Estado'),
                Tables\Columns\TextColumn::make('notes')->label('Notas')->limit(50),
                Tables\Columns\TextColumn::make('created_at')->label('Creado')->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'en_proceso' => 'En Proceso',
                        'completado' => 'Completado',
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
            'index' => Pages\ListQuaCommitteeFollowups::route('/'),
            'create' => Pages\CreateQuaCommitteeFollowup::route('/create'),
            'edit' => Pages\EditQuaCommitteeFollowup::route('/{record}/edit'),
        ];
    }
}
