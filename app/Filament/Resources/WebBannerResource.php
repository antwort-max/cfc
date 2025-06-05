<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WebBannerResource\Pages;
use App\Models\WebBanner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WebBannerResource extends Resource
{
    protected static ?string $model = WebBanner::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Sitio Web';
    protected static ?string $modelLabel = 'Banner';
    protected static ?string $pluralModelLabel = 'Banners';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->maxLength(255),

                Forms\Components\FileUpload::make('image')
                    ->label('Imagen')
                    ->image()
                    ->directory('banners')
                    ->required(),

                Forms\Components\TextInput::make('link')
                    ->label('Enlace')
                    ->url()
                    ->nullable(),

                Forms\Components\Select::make('position')
                    ->label('Posición')
                    ->options([
                        'home' => 'Inicio',
                        'category' => 'Categoría',
                        'cart' => 'Carro de Compras',
                    ])
                    ->default('home')
                    ->required(),

                Forms\Components\Toggle::make('status')
                    ->label('Activo')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')->label('Imagen')->height(40),
                Tables\Columns\TextColumn::make('title')->label('Título')->searchable(),
                Tables\Columns\TextColumn::make('position')->label('Posición')->badge(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Activo')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')->label('Creado')->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('position')
                    ->label('Ubicación')
                    ->options([
                        'home' => 'Inicio',
                        'category' => 'Categoría',
                        'cart' => 'Carro',
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
            'index' => Pages\ListWebBanners::route('/'),
            'create' => Pages\CreateWebBanner::route('/create'),
            'edit' => Pages\EditWebBanner::route('/{record}/edit'),
        ];
    }
}
