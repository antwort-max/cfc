<?php

namespace App\Filament\Ecommerce\Resources;

use App\Filament\Ecommerce\Resources\WebSocialLinkResource\Pages;
use App\Models\WebSocialLink;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\TextInput;
use Filament\Forms\Toggle;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class WebSocialLinkResource extends Resource
{
    protected static ?string $model = WebSocialLink::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';
    protected static ?string $navigationGroup = 'Sitio Web';
    protected static ?string $navigationLabel = 'Enlaces Sociales';
    protected static ?string $pluralLabel = 'Enlaces Sociales';
    protected static ?string $modelLabel = 'Enlace Social';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('platform')
                    ->label('Plataforma')
                    ->required()
                    ->maxLength(50),

                Forms\Components\TextInput::make('url')
                    ->label('URL')
                    ->url()
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('icon')
                    ->label('Icono (clase CSS)')
                    ->maxLength(100),

                Forms\Components\Toggle::make('status')
                    ->label('Activo')
                    ->inline(false)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('platform')->label('Plataforma')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('url')->label('URL')->limit(30),
                Tables\Columns\IconColumn::make('status')
                    ->label('Activo')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'   => Pages\ListWebSocialLinks::route('/'),
            'create'  => Pages\CreateWebSocialLink::route('/create'),
            'edit'    => Pages\EditWebSocialLink::route('/{record}/edit'),
        ];
    }
}
