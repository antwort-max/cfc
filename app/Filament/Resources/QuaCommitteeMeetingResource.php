<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuaCommitteeMeetingResource\Pages;
use App\Models\QuaCommitteeMeeting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class QuaCommitteeMeetingResource extends Resource
{
    protected static ?string $model = QuaCommitteeMeeting::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Calidad';
    protected static ?string $modelLabel = 'Reunión de Comité';
    protected static ?string $pluralModelLabel = 'Reuniones de Comité';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('meeting_date')
                    ->label('Fecha')
                    ->required(),

                Forms\Components\TimePicker::make('meeting_time')
                    ->label('Hora')
                    ->nullable(),

                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->nullable(),

                Forms\Components\TextInput::make('location')
                    ->label('Lugar')
                    ->nullable(),

                Forms\Components\RichEditor::make('agenda')
                    ->label('Agenda')
                    ->columnSpanFull(),

                Forms\Components\RichEditor::make('minutes')
                    ->label('Acta / Minuta')
                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('meeting_date')->label('Fecha')->date(),
                Tables\Columns\TextColumn::make('meeting_time')->label('Hora')->time(),
                Tables\Columns\TextColumn::make('name')->label('Nombre'),
                Tables\Columns\TextColumn::make('location')->label('Lugar'),
            ])
            ->filters([
                Tables\Filters\Filter::make('meeting_date')
                    ->label('Reuniones recientes')
                    ->query(fn ($query) => $query->where('meeting_date', '>=', now()->subDays(30))),
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
            'index' => Pages\ListQuaCommitteeMeetings::route('/'),
            'create' => Pages\CreateQuaCommitteeMeeting::route('/create'),
            'edit' => Pages\EditQuaCommitteeMeeting::route('/{record}/edit'),
        ];
    }
}
