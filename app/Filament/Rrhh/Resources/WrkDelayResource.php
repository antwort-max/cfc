<?php

namespace App\Filament\Rrhh\Resources;

use App\Filament\Rrhh\Resources\WrkDelayResource\Pages;
use App\Filament\Rrhh\Resources\WrkDelayResource\RelationManagers;
use App\Models\WrkDelay;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Filament\Forms\Components\TimePicker;

class WrkDelayResource extends Resource
{
    protected static ?string $model = WrkDelay::class;

    protected static ?string $navigationGroup = 'Colaboradores';

    protected static ?string $label = 'Atraso de Colaborador';
    
    public static function getPluralModelLabel(): string
    {
        return 'Atrasos de Colaboradores (' . Carbon::now()->format('D d-m-Y') . ')';
    }

    protected static ?string $navigationLabel = 'Atrasos de Colaboradores';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function form(Form $form): Form
    {
        return $form
        ->schema([

            Forms\Components\DatePicker::make('delay_date')
            ->label('Fecha')
            ->default(now())
            ->required(),

            Forms\Components\Select::make('employee_id')
                ->relationship('employee', 'name')
                ->label('Colaborador')
                ->searchable()
                ->required(),

            Forms\Components\TimePicker::make('delay_time')
                ->label('Hora de llegada')
                ->required()
                ->seconds(false)
                ->displayFormat('H:i'),

            Forms\Components\RichEditor::make('reason')
                ->label('Razón informada')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->whereDate('delay_date', Carbon::today());
            })
            ->columns([
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
                    //
                ])
            ->filters([
                //
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
            //
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
