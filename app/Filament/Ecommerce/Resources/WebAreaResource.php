<?php

namespace App\Filament\Ecommerce\Resources;

use App\Filament\Ecommerce\Resources\WebAreaResource\Pages;
use App\Models\WebArea;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\MultiSelect;

class WebAreaResource extends Resource
{
    protected static ?string $model = WebArea::class;
    protected static ?string $navigationIcon = 'heroicon-o-eye';
    protected static ?string $navigationGroup = 'Sitio Web';
    protected static ?string $modelLabel = 'Areas';
    protected static ?string $navigationLabel = 'Áreas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                
                Forms\Components\RichEditor::make('short_description')
                    ->label('Descripción corta'),
                
                Forms\Components\RichEditor::make('description')
                    ->label('Descripción larga'),

                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->label('Imagen')
                    ->maxSize(1024),
                
                Forms\Components\TextInput::make('icon')
                    ->label('Icono')
                    ->maxLength(255),
                
                Forms\Components\MultiSelect::make('categories')
                    ->relationship('categories', 'name')
                    ->label('Categorías')
                    ->preload()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')->label('Imagen')->square(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
                Tables\Columns\TextColumn::make('categories.name')->label('Categorías')->wrap(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWebAreas::route('/'),
            'create' => Pages\CreateWebArea::route('/create'),
            'edit' => Pages\EditWebArea::route('/{record}/edit'),
        ];
    }
}

