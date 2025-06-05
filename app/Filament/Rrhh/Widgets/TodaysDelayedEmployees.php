<?php 

namespace App\Filament\Rrhh\Widgets;

use App\Models\WrkDelay;
use Filament\Widgets\TableWidget as TableWidget;
use Filament\Tables;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class TodaysDelayedEmployees extends TableWidget
{
    protected int|string|array $columnSpan = 'full';


    protected function getTableQuery(): Builder|Relation|null
    {
        return WrkDelay::query()
            ->with('employee')
            ->whereDate('delay_date', Carbon::today())
            ->orderBy('delay_time', 'asc');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('employee.name')->label('Empleado'),
            Tables\Columns\TextColumn::make('delay_time')
                ->label('Hora de Atraso')
                ->time('H:i'),
        ];
    }

    public function getHeading(): string
    {
        return 'Atrasos del Día - ' . Carbon::now()->format('d-m-Y');
    }
}