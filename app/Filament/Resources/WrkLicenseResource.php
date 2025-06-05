<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WrkLicenseResource\Pages;
use App\Filament\Resources\WrkLicenseResource\RelationManagers;
use App\Models\WrkLicense;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WrkLicenseResource extends Resource
{
    protected static ?string $model = WrkLicense::class;

    protected static ?string $navigationGroup = 'Colaboradores';

    protected static ?string $label = 'Licencia de Colaborador';
    protected static ?string $pluralLabel = 'Licencias de Colaboradores';

    protected static ?string $navigationLabel = 'Licencias de Calobaradores';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('employee_id')
                    ->relationship('employee', 'name')
                    ->label('Employee')
                    ->searchable()
                    ->required(),

                Forms\Components\DatePicker::make('date_start'),
                Forms\Components\DatePicker::make('date_finish'),
                Forms\Components\TextInput::make('attachment')
                    ->maxLength(255),
                Forms\Components\Textarea::make('details')
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

                Tables\Columns\TextColumn::make('date_start')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_finish')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('attachment')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListWrkLicenses::route('/'),
            'create' => Pages\CreateWrkLicense::route('/create'),
            'edit' => Pages\EditWrkLicense::route('/{record}/edit'),
        ];
    }
}
