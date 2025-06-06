<?php

namespace App\Filament\Ecommerce\Resources;

use App\Filament\Ecommerce\Resources\WebThemeOptionResource\Pages;
use App\Models\WebThemeOption;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WebThemeOptionResource extends Resource
{
    protected static ?string $model = WebThemeOption::class;

    protected static ?string $navigationIcon     = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup    = 'Sitio Web';
    protected static ?string $modelLabel         = 'Opción de Tema';
    protected static ?string $pluralModelLabel   = 'Opciones de Tema';

    /** ------------------------------------------------------------------
     *  FORM
     * -----------------------------------------------------------------*/
    public static function form(Form $form): Form
    {
        return $form->schema([
            /* Logo */
            Forms\Components\FileUpload::make('logo')
                ->label('Logo')
                ->image()
                ->directory('theme-options')   // storage/app/public/theme-options
                ->preserveFilenames()
                ->imagePreviewHeight('150')
                ->columnSpanFull(),

            /* Ícono (fav-icon, 1:1) */
            Forms\Components\FileUpload::make('icon')
                ->label('Ícono')
                ->image()
                ->directory('theme-options')
                ->preserveFilenames()
                ->imageCropAspectRatio('1:1')
                ->imagePreviewHeight('64')
                ->columnSpanFull(),

            Forms\Components\Select::make('theme_mode')
                ->label('Modo de Tema')
                ->options([
                    'light' => 'Claro',
                    'dark'  => 'Oscuro',
                    'auto'  => 'Automático',
                ])
                ->required(),

            Forms\Components\TextInput::make('font_family')
                ->label('Fuente')
                ->default('Roboto'),

            Forms\Components\Select::make('button_style')
                ->label('Estilo de Botón')
                ->options([
                    'rounded' => 'Redondeado',
                    'flat'    => 'Plano',
                    'outline' => 'Contorno',
                ])
                ->required(),

            Forms\Components\Select::make('layout_type')
                ->label('Diseño')
                ->options([
                    'boxed'     => 'Caja',
                    'fullwidth' => 'Pantalla Completa',
                ])
                ->required(),

            Forms\Components\Toggle::make('status')
                ->label('Activo')
                ->default(true),
        ]);
    }

    /** ------------------------------------------------------------------
     *  TABLE
     * -----------------------------------------------------------------*/
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label('Logo')
                    ->square()
                    ->height(32),

                Tables\Columns\ImageColumn::make('icon')
                    ->label('Ícono')
                    ->square()
                    ->height(24),

                Tables\Columns\TextColumn::make('theme_mode')
                    ->label('Tema'),

                Tables\Columns\TextColumn::make('font_family')
                    ->label('Fuente'),

                Tables\Columns\TextColumn::make('button_style')
                    ->label('Botón'),

                Tables\Columns\TextColumn::make('layout_type')
                    ->label('Diseño'),

                Tables\Columns\IconColumn::make('status')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /** ------------------------------------------------------------------
     *  RELATIONS & PAGES
     * -----------------------------------------------------------------*/
    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListWebThemeOptions::route('/'),
            'create' => Pages\CreateWebThemeOption::route('/create'),
            'edit'   => Pages\EditWebThemeOption::route('/{record}/edit'),
        ];
    }
}
