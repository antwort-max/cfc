<?php

namespace App\Filament\Rrhh\Pages;

use Filament\Pages\Page;
use App\Models\WrkDelay;
use Illuminate\Support\Carbon;
use App\Mail\MonthlyDelaysReport;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;

class DelaysReport extends Page
{
    protected static string  $view = 'filament.rrhh.pages.delays-report';
    protected static ?string $navigationIcon  = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Informe Mensual';
    protected static ?string $navigationLabel = 'Listado de Atrasos';

    public $employeeTotals;

    public function mount(): void
    {
        $today = Carbon::now();

        // 1. Traer todos los retrasos del mes actual
        $delays = WrkDelay::with('employee')
            ->whereYear('delay_date', $today->year)
            ->whereMonth('delay_date', $today->month)
            ->get();

        // 2. Agrupar por empleado
        $byEmployee = $delays->groupBy(fn($d) => $d->employee_id);

        // 3. Mapear totales y conteos
        $this->employeeTotals = $byEmployee->map(fn($group) => [
            'employee'      => $group->first()->employee->name,
            'count'         => $group->count(),
            'total_minutes' => $group->sum('lost_minutes'), // <== ya funciona
        ])->values();

    }

    public function sendReport()
    {
        Mail::to('maximiliano.lopez.robles@gmail.com')->send(
            new MonthlyDelaysReport($this->employeeTotals->toArray())
        );

        Notification::make()
            ->title('Reporte Enviado')
            ->send();
    }
}





