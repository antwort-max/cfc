<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrdSubfamilyResource\Pages;
use App\Models\PrdSubfamily;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use App\Http\Controllers\Sync\SyncSubfamiliesController;

class PrdSubfamilyResource extends Resource
{
    protected static ?string $model = PrdSubfamily::class;

    protected static ?string $navigationGroup = 'Productos';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 2;

    public static function getLabel(): string
    {
        return 'Subfamilia de Producto';
    }

    public static function getPluralLabel(): string
    {
        return 'Subfamilias de Producto';
    }

    public static function getNavigationLabel(): string
    {
        return static::getPluralLabel();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                Action::make('syncSubfamilies')
                    ->label('Sincronizar Sub Familias')
                    ->icon('heroicon-o-arrow-path')
                    ->action(function () {
                        $controller = new SyncSubfamiliesController();
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
            'index' => Pages\ListPrdSubfamilies::route('/'),
            'create' => Pages\CreatePrdSubfamily::route('/create'),
            'edit' => Pages\EditPrdSubfamily::route('/{record}/edit'),
        ];
    }
}
