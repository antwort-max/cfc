<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WebMenuResource\Pages;
use App\Models\WebMenu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WebMenuResource extends Resource
{
    protected static ?string $model = WebMenu::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3-bottom-left';
    protected static ?string $navigationGroup = 'Sitio Web';
    protected static ?string $modelLabel = 'Menú';
    protected static ?string $pluralModelLabel = 'Menús';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('parent_id')
                    ->label('Menú Padre')
                    ->relationship('parent', 'title')
                    ->searchable()
                    ->nullable(),

                Forms\Components\TextInput::make('url')
                    ->label('Enlace')
                    ->required(),

                Forms\Components\TextInput::make('icon')
                    ->label('Ícono (Heroicon, FontAwesome, etc.)')
                    ->nullable(),

                Forms\Components\TextInput::make('order')
                    ->label('Orden')
                    ->numeric()
                    ->default(1),

                Forms\Components\Toggle::make('status')
                    ->label('Activo')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Título')->searchable(),
                Tables\Columns\TextColumn::make('parent.title')->label('Padre')->sortable(),
                Tables\Columns\TextColumn::make('url')->label('URL'),
                Tables\Columns\TextColumn::make('icon')->label('Icono TI'),
                Tables\Columns\TextColumn::make('order')->label('Orden')->sortable(),
                Tables\Columns\IconColumn::make('status')->label('Activo')->boolean(),
            ])
            ->defaultSort('order')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        true => 'Activo',
                        false => 'Inactivo',
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
            'index' => Pages\ListWebMenus::route('/'),
            'create' => Pages\CreateWebMenu::route('/create'),
            'edit' => Pages\EditWebMenu::route('/{record}/edit'),
        ];
    }
}
