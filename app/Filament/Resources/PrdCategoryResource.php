<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrdCategoryResource\Pages;
use App\Models\PrdCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use App\Http\Controllers\Sync\SyncCategoriesController;

class PrdCategoryResource extends Resource
{
    protected static ?string $model = PrdCategory::class;

    protected static ?string $navigationGroup = 'Productos';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 3;

    public static function getLabel(): string
    {
        return 'categoria de Producto';
    }

    public static function getPluralLabel(): string
    {
        return 'Categorias de Producto';
    }

    public static function getNavigationLabel(): string
    {
        return static::getPluralLabel();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label('Codigo')
                    ->disabled()  
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->disabled()  
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image')
                    ->image(),
                Forms\Components\RichEditor::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->searchable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('slug')->searchable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Action::make('syncCategories')
                    ->label('Sincronizar Categorías')
                    ->icon('heroicon-o-arrow-path')
                    ->action(function () {
                        // ✅ Llamada directa al controlador (sin HTTP)
                        $controller = new SyncCategoriesController();
                        $response = $controller->index();

                        $data = $response->getData(true);

                        Notification::make()
                            ->title('Sincronización completada')
                            ->body($data['message'] . ' - Nuevas: ' . $data['created'] . ', Actualizadas: ' . $data['updated'])
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPrdCategories::route('/'),
            'create' => Pages\CreatePrdCategory::route('/create'),
            'edit' => Pages\EditPrdCategory::route('/{record}/edit'),
        ];
    }
}
