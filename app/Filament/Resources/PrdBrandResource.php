<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrdBrandResource\Pages;
use App\Models\PrdBrand;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use App\Http\Controllers\Sync\SyncBrandsController;

class PrdBrandResource extends Resource
{
    protected static ?string $model = PrdBrand::class;

    protected static ?string $navigationGroup = 'Productos';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 4;

    public static function getLabel(): string
    {
        return 'Marca de Producto';
    }

    public static function getPluralLabel(): string
    {
        return 'Marcas de Producto';
    }

    public static function getNavigationLabel(): string
    {
        return static::getPluralLabel();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('code')->required()->maxLength(255),
            Forms\Components\TextInput::make('name')->required()->maxLength(255),
            Forms\Components\TextInput::make('slug')->required()->maxLength(255),
            Forms\Components\Textarea::make('description')->columnSpanFull(),
            Forms\Components\FileUpload::make('image')->image(),
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
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
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
                Action::make('syncBrands')
                    ->label('Sincronizar Marcas')
                    ->icon('heroicon-o-arrow-path')
                    ->action(function () {
                        // ✅ Llamada directa al controlador, sin usar HTTP
                        $controller = new SyncBrandsController();
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
            'index' => Pages\ListPrdBrands::route('/'),
            'create' => Pages\CreatePrdBrand::route('/create'),
            'edit' => Pages\EditPrdBrand::route('/{record}/edit'),
        ];
    }
}
