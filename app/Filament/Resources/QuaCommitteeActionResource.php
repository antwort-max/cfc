<?php 

namespace App\Filament\Resources;

use App\Filament\Resources\QuaCommitteeActionResource\Pages;
use App\Models\QuaCommitteeAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class QuaCommitteeActionResource extends Resource
{
    protected static ?string $model = QuaCommitteeAction::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Calidad';
    protected static ?string $modelLabel = 'Acción de Comité';
    protected static ?string $pluralModelLabel = 'Acciones de Comité';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('meeting_id')
                    ->label('Reunión')
                    ->relationship('meeting', 'name')
                    ->searchable()
                    ->nullable(),
               
                Forms\Components\RichEditor::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),

                Forms\Components\Select::make('responsible_id')
                    ->label('Responsable')
                    ->relationship('responsible', 'name')
                    ->searchable()
                    ->nullable(),

                Forms\Components\DatePicker::make('deadline')
                    ->label('Fecha Límite')
                    ->nullable(),

                Forms\Components\Select::make('status')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'en_proceso' => 'En Proceso',
                        'completada' => 'Completada',
                    ])
                    ->nullable(),

                Forms\Components\Textarea::make('followup_notes')
                    ->label('Seguimiento')
                    ->rows(3)
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('meeting.name')->label('Reunión'),
                Tables\Columns\TextColumn::make('responsible.name')->label('Responsable'),
                Tables\Columns\TextColumn::make('deadline')->label('Plazo')->date(),
                Tables\Columns\BadgeColumn::make('status')->label('Estado')->colors([
                    'danger' => 'pendiente',
                    'warning' => 'en_proceso',
                    'success' => 'completada',
                ]),
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
            'index' => Pages\ListQuaCommitteeActions::route('/'),
            'create' => Pages\CreateQuaCommitteeAction::route('/create'),
            'edit' => Pages\EditQuaCommitteeAction::route('/{record}/edit'),
        ];
    }
}
