<?php

namespace App\Filament\Ecommerce\Resources;

use App\Filament\Ecommerce\Resources\WebActivityResource\Pages;
use App\Models\WebActivity;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class WebActivityResource extends Resource
{
    protected static ?string $model = WebActivity::class;
    protected static ?string $navigationIcon = 'heroicon-o-eye';
    protected static ?string $navigationLabel = 'Actividades Web';
    protected static ?string $modelLabel = 'Actividad Web';
    protected static ?string $pluralModelLabel = 'Actividades Web';
    protected static ?string $navigationGroup = 'Análisis';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_id')
                    ->label('Usuario')
                    ->relationship('customer', 'first_name') 
                    ->searchable()
                    ->disabled(),

                Forms\Components\TextInput::make('session_id')->disabled(),

                Forms\Components\TextInput::make('event_type')
                    ->label('Tipo de Evento')
                    ->disabled(),

                Forms\Components\Textarea::make('event_data')
                    ->label('Datos del Evento')
                    ->formatStateUsing(fn ($state) => json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))
                    ->disabled(),

                Forms\Components\TextInput::make('duration_seconds')
                    ->label('Duración (segundos)')
                    ->numeric()
                    ->disabled(),

                Forms\Components\TextInput::make('url')->disabled(),
                Forms\Components\TextInput::make('referrer')->disabled(),
                Forms\Components\TextInput::make('ip_address')->disabled(),
                Forms\Components\Textarea::make('user_agent')->disabled(),

                Forms\Components\DateTimePicker::make('created_at')->label('Fecha')->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->label('Fecha')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('customer.first_name')->label('Usuario')->searchable(),
                Tables\Columns\TextColumn::make('event_type')->label('Tipo')->searchable(),
                Tables\Columns\TextColumn::make('url')->label('URL')->limit(40),
                Tables\Columns\TextColumn::make('duration_seconds')->label('Duración')->sortable(),
                Tables\Columns\TextColumn::make('ip_address')->label('IP')->sortable(),
            ])
            ->filters([
                //
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWebActivities::route('/'),
            'create' => Pages\CreateWebActivity::route('/create'),
            'edit' => Pages\EditWebActivity::route('/{record}/edit'),
        ];
    }
}
