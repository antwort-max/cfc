<?php

namespace App\Filament\Ecommerce\Resources;

use App\Filament\Ecommerce\Resources\WebFooterSectionResource\Pages;
use App\Models\WebFooterSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WebFooterSectionResource extends Resource
{
    protected static ?string $model = WebFooterSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
    protected static ?string $navigationGroup = 'Sitio Web';
    protected static ?string $modelLabel = 'Sección de Pie de Página';
    protected static ?string $pluralModelLabel = 'Secciones de Pie de Página';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\TextInput::make('order')
                    ->label('Orden')
                    ->numeric()
                    ->default(1),
                    
                Forms\Components\RichEditor::make('content')
                    ->label('Descripción')
                    ->columnSpanFull(),
               
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
                Tables\Columns\TextColumn::make('order')->label('Orden')->sortable(),
                Tables\Columns\IconColumn::make('status')->label('Activo')->boolean(),
            ])
            ->defaultSort('order')
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
            'index' => Pages\ListWebFooterSections::route('/'),
            'create' => Pages\CreateWebFooterSection::route('/create'),
            'edit' => Pages\EditWebFooterSection::route('/{record}/edit'),
        ];
    }
}
