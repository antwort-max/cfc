<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HistoricDocumentResource\Pages;
use App\Models\HistoricDocument;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\HistoricDocumentResource\ProductsRelationManagerResource\RelationManagers\ProductsRelationManager;
use Filament\Tables\Columns\Summarizers\Sum;
use App\Http\Controllers\Sync\SyncHistoricController;   // ← tu controlador
use Filament\Tables\Actions\Action;                // ← acción de tabla
use Filament\Notifications\Notification; 



class HistoricDocumentResource extends Resource
{
    protected static ?string $model = HistoricDocument::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Sincronizaciones';

    public static function getWidgets(): array
    {
        return [
            HistoricDocumentResource\Widgets\HistoricSalesPerHourChart::class,
            // … otros widgets si tuvieras
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('document_date')
                    ->label('Fecha')
                    ->disabled(),
                Forms\Components\TimePicker::make('document_time')
                    ->label('Hora')
                    ->disabled(),
                Forms\Components\Select::make('document_type')
                    ->options([
                        'BLV' => 'BLV',
                        'FCV' => 'FCV',
                        'NCV' => 'NCV',
                        'FDV' => 'FDV',
                    ])
                    ->label('Tipo documento')
                    ->disabled(),
                Forms\Components\TextInput::make('document_number')
                    ->maxLength(20)
                    ->label('Folio')
                    ->disabled(),
                Forms\Components\TextInput::make('client')
                    ->maxLength(255)
                    ->label('Rut Cliente')
                    ->disabled(),
                Forms\Components\TextInput::make('place')
                    ->maxLength(10)
                    ->label('Local Venta')
                    ->disabled(),
                Forms\Components\TextInput::make('seller')
                    ->maxLength(10)
                    ->label('Vendedor')
                    ->disabled(),
                Forms\Components\TextInput::make('total_sales_amount')
                    ->numeric()
                    ->label('Monto Neto')
                    ->disabled(),
                Forms\Components\TextInput::make('total_sales_amount_with_tax')
                    ->numeric()
                    ->label('Monto con IVA')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('document_id')->label('ID Doc')->sortable(),
                Tables\Columns\TextColumn::make('document_date')->date()->label('Fecha')->sortable(),
                Tables\Columns\TextColumn::make('document_time')->label('Hora')->sortable(),
                Tables\Columns\TextColumn::make('document_type')->label('Tipo')->sortable(),
                Tables\Columns\TextColumn::make('document_number')->label('Número'),
                Tables\Columns\TextColumn::make('client')->label('Cliente')->limit(20),
                Tables\Columns\TextColumn::make('place')->label('Local'),
                Tables\Columns\TextColumn::make('seller')->label('Vendedor'),
                Tables\Columns\TextColumn::make('total_sales_amount')->money('CLP', true)->label('Neto'),
                Tables\Columns\TextColumn::make('total_sales_amount_with_tax')->money('CLP', true)->label('Con IVA'),
                Tables\Columns\TextColumn::make('total_sales_amount')
                    ->label('Neto')
                    ->money('CLP', true)
                    ->summarize(                     
                        Sum::make()
                            ->label('Total Neto')
                            ->money('CLP', true)
                    ),
            ])
            ->filters([
                Filter::make('date_range')
                    ->label('Fecha')
                    ->form([
                        DatePicker::make('from')
                            ->label('Desde')
                            ->default(now()->toDateString())   // ← hoy
                            ->placeholder('YYYY-MM-DD'),
                        DatePicker::make('until')
                            ->label('Hasta')
                            ->default(now()->toDateString())   // ← hoy
                            ->placeholder('YYYY-MM-DD'),
                    ])
                    ->query(fn ($query, array $data) =>
                        $query
                            ->when($data['from'],  fn ($q) => $q->whereDate('document_date', '>=', $data['from']))
                            ->when($data['until'], fn ($q) => $q->whereDate('document_date', '<=', $data['until']))
                    )
                    ->default([
                        'from'  => now()->toDateString(),
                        'until' => now()->toDateString(),
                    ]),    
                // Filtrar por tipo de documento
                SelectFilter::make('document_type')
                    ->label('Tipo')
                    ->options([
                        'BLV' => 'BLV',
                        'FCV' => 'FCV',
                        'NCV' => 'NCV',
                        'FDV' => 'FDV',
                    ]),

                // Filtrar por vendedor (seller)
                SelectFilter::make('seller')
                    ->label('Vendedor')
                    ->options(
                        HistoricDocument::query()
                            ->distinct()
                            ->pluck('seller', 'seller')
                            ->toArray()
                    ),

                // Filtrar por local de pago (place)
                SelectFilter::make('place')
                    ->label('Local')
                    ->options(
                        HistoricDocument::query()
                            ->distinct()
                            ->pluck('place', 'place')
                            ->toArray()
                    ),

                // Filtrar por cliente (cliente) con búsqueda libre
Filter::make('client')
    ->label('Cliente')
    ->form([
        Forms\Components\TextInput::make('client')
            ->label('Nombre cliente')
            ->placeholder('Buscar...'),
    ])
    ->query(function ($query, array $data) {
        return $query->when(
            $data['client'],
            fn ($q, $client) => $q->where('client', 'like', "%{$client}%")
        );
    }),

// Filtrar por número de documento
Filter::make('document_number')
    ->label('Folio')
    ->form([
        Forms\Components\TextInput::make('document_number')
            ->label('Número de documento')
            ->placeholder('Ej: 123456'),
    ])
    ->query(function ($query, array $data) {
        return $query->when(
            $data['document_number'],
            fn ($q, $documentNumber) => $q->where('document_number', 'like', "%{$documentNumber}%")
        );
    }),
            ])
            ->actions([
               // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->headerActions([
                Action::make('syncHistoric')                // alias importado = Tables\Actions\Action
                    ->label('Sincronizar Documentos')
                    ->icon('heroicon-o-arrow-path')
                    ->requiresConfirmation()               // (opcional) diálogo de “¿Estás seguro?”
                    ->action(function () {
                        // 3) llamamos al controlador vía el contenedor
                        $response = app(SyncHistoricController::class)->index();

                        $data = $response->getData(true);   // asumiendo que devuelve JsonResponse

                        Notification::make()
                            ->title('Sincronización completada')
                            ->body(
                                "{$data['message']} — Documentos: {$data['document']}, Productos: {$data['product']}"
                            )
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('document_time', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListHistoricDocuments::route('/'),
        //    'create' => Pages\CreateHistoricDocument::route('/create'),
            'edit'   => Pages\EditHistoricDocument::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

   // public static function canEdit(Model $record): bool
   // {
  //      return false;
  //  }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function getRelations(): array
    {
        return [
            ProductsRelationManager::class,
        ];
    }

}

