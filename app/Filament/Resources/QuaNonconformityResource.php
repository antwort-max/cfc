<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuaNonconformityResource\Pages;
use App\Filament\Resources\QuaNonconformityResource\RelationManagers;
use App\Models\QuaNonconformity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Select;

class QuaNonconformityResource extends Resource
{
    protected static ?string $model = QuaNonconformity::class;

    protected static ?string $modelLabel = 'No Conformidad';
    protected static ?string $pluralModelLabel = 'No Conformidades';
    protected static ?string $navigationGroup = 'Calidad';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\DatePicker::make('detected_at')
                    ->label('Fecha de detección')
                    ->required(),

                Forms\Components\TextInput::make('name')->label('Nombre')->required(),

                Forms\Components\RichEditor::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),


                Forms\Components\Select::make('area_id')
                    ->label('Área')
                    ->relationship('area', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Forms\Components\TextInput::make('category_severity')->label('Gravedad')->required(),
                Forms\Components\TextInput::make('status')->label('Estado')->required(),

                Select::make('status')
                ->label('Estado')
                ->options([
                    'register' => 'Prospecto',
                    'earring'  => 'Pendiente',
                    'close'    => 'Cerrado',
                ])
                ->required(),

                Forms\Components\Select::make('employee_id')
                    ->label('Responsable')
                    ->relationship('employee', 'name')
                    ->searchable()
                    ->nullable(),

                Forms\Components\FileUpload::make('image')->label('Imagen')->image()->directory('nonconformities/images'),
                Forms\Components\FileUpload::make('attachment')->label('Adjunto')->directory('nonconformities/docs'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),
                
                    Tables\Columns\TextColumn::make('detected_at')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),
                              
                Tables\Columns\TextColumn::make('area.name')->label('Área'),
                
                BadgeColumn::make('status')
                ->label('Estado')
                ->formatStateUsing(fn($state) => match ($state) {
                    'register' => 'Prospecto',
                    'earring'  => 'Pendiente',
                    'close'    => 'Cerrado',
                    default    => $state,
                })
                ->colors([
                    'warning'   => 'register',
                    'success'   => 'close',
                    'danger'    => 'earring',
                ]),
                Tables\Columns\TextColumn::make('employee.name')->label('Responsable'),
            ])
            ->filters([
                // Puedes agregar filtros por estado o área si deseas
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
            'index' => Pages\ListQuaNonconformities::route('/'),
            'create' => Pages\CreateQuaNonconformity::route('/create'),
            'edit' => Pages\EditQuaNonconformity::route('/{record}/edit'),
        ];
    }
}
