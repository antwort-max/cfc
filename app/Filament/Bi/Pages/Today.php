<?php

namespace App\Filament\Bi\Pages;

use Filament\Pages\Page;
use Filament\Pages\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailyReportPdf;
use App\Filament\Bi\Widgets\DailySalesSummary;
use App\Filament\Bi\Widgets\SalesByChannelChart;
use App\Filament\Bi\Widgets\HourlySalesTrendChart;
use App\Filament\Bi\Widgets\TopProductsTodayTable;
use App\Filament\Bi\Widgets\LowStockAlertsTable;
use App\Filament\Bi\Widgets\DayComparisonMetrics;
use App\Filament\Bi\Widgets\SalesBySellerTable;
use App\Filament\Bi\Widgets\SalesByPlacePieToday;
use App\Filament\Bi\Widgets\MonthlyComparisonMetrics;
use Carbon\Carbon;
use App\Http\Controllers\Sync\SyncHistoricController; 

use App\Models\HistoricProduct;

class Today extends Page
{
    protected static string $view = 'filament.bi.pages.today';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Today';
    protected static ?string $title           = 'Today\'s Overview';
    
    public int $days = 1;

    public function submit(): void
    {
        $this->validate([
            'days' => ['required','integer','min:1'],
        ]);
    }


    protected function getHeaderWidgets(): array
    {
        return [
            DayComparisonMetrics::class,
            MonthlyComparisonMetrics::class,
            SalesByChannelChart::class,
            SalesByPlacePieToday::make([
                'days' => $this->days,
            ]),
            HourlySalesTrendChart::class,
            SalesBySellerTable::class,
            TopProductsTodayTable::class,
            LowStockAlertsTable::class,
        ];
    }

    protected function getActions(): array
    {
        return [
            Action::make('exportDailyReport')
                ->label('Export & Email Daily Report')
                ->color('primary')
                ->action('handleExportDailyReport'),

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
         ];
    }

    public function handleExportDailyReport(): void
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // === VENTAS DEL DÍA vs AYER ===
        $salesToday = HistoricProduct::whereDate('document_date', $today)->sum('total_sales_amount');
        $salesYesterday = HistoricProduct::whereDate('document_date', $yesterday)->sum('total_sales_amount');

        $txToday = HistoricProduct::whereDate('document_date', $today)->distinct('document_number')->count('document_number');
        $txYesterday = HistoricProduct::whereDate('document_date', $yesterday)->distinct('document_number')->count('document_number');

        $aovToday = $txToday ? round($salesToday / $txToday, 2) : 0;
        $aovYesterday = $txYesterday ? round($salesYesterday / $txYesterday, 2) : 0;

        // === VENTAS MES ACTUAL vs MISMO MES AÑO PASADO ===
        $startThisMonth = $today->copy()->startOfMonth();
        $endThisMonth = $today;
        $startLastYear = $startThisMonth->copy()->subYear();
        $endLastYear = $endThisMonth->copy()->subYear();

        $salesCurrent = HistoricProduct::whereBetween('document_date', [$startThisMonth, $endThisMonth])->sum('total_sales_amount');
        $salesLastYear = HistoricProduct::whereBetween('document_date', [$startLastYear, $endLastYear])->sum('total_sales_amount');

        $txCurrent = HistoricProduct::whereBetween('document_date', [$startThisMonth, $endThisMonth])->distinct('document_number')->count('document_number');
        $txLastYear = HistoricProduct::whereBetween('document_date', [$startLastYear, $endLastYear])->distinct('document_number')->count('document_number');

        $aovCurrent = $txCurrent ? round($salesCurrent / $txCurrent, 2) : 0;
        $aovLastYear = $txLastYear ? round($salesLastYear / $txLastYear, 2) : 0;

        // === CÁLCULO DE VARIACIONES ===
        $calcChange = fn($current, $previous) => $previous > 0 ? round((($current - $previous) / $previous) * 100, 0) : 0;

        $salesChange = $calcChange($salesToday, $salesYesterday);
        $txChange = $calcChange($txToday, $txYesterday);
        $aovChange = $calcChange($aovToday, $aovYesterday);

        $monthlySalesChange = $calcChange($salesCurrent, $salesLastYear);
        $monthlyTxChange = $calcChange($txCurrent, $txLastYear);
        $monthlyAovChange = $calcChange($aovCurrent, $aovLastYear);

        // === ESTRUCTURA PARA EL PDF ===
        $data = [
            'daily' => [
                [
                    'label' => 'Total Ventas Hoy',
                    'value' => '$' . number_format($salesToday, 0, ',', '.'),
                    'description' => 'Ayer: $' . number_format($salesYesterday, 0, ',', '.') . ' (' . ($salesChange >= 0 ? '+' : '') . $salesChange . '%)',
                ],
                [
                    'label' => 'Tickets Hoy',
                    'value' => number_format($txToday, 0, ',', '.'),
                    'description' => 'Ayer: ' . number_format($txYesterday, 0, ',', '.') . ' (' . ($txChange >= 0 ? '+' : '') . $txChange . '%)',
                ],
                [
                    'label' => 'Ticket Promedio Hoy',
                    'value' => '$' . number_format($aovToday, 0, ',', '.'),
                    'description' => 'Ayer: $' . number_format($aovYesterday, 0, ',', '.') . ' (' . ($aovChange >= 0 ? '+' : '') . $aovChange . '%)',
                ],
            ],
            'monthly' => [
                [
                    'label' => 'Ventas mes actual',
                    'value' => '$' . number_format($salesCurrent, 0, ',', '.'),
                    'description' => 'Año pasado: $' . number_format($salesLastYear, 0, ',', '.') . ' (' . ($monthlySalesChange >= 0 ? '+' : '') . $monthlySalesChange . '%)',
                ],
                [
                    'label' => 'Tickets del mes',
                    'value' => number_format($txCurrent, 0, ',', '.'),
                    'description' => 'Año pasado: ' . number_format($txLastYear, 0, ',', '.') . ' (' . ($monthlyTxChange >= 0 ? '+' : '') . $monthlyTxChange . '%)',
                ],
                [
                    'label' => 'Ticket promedio del mes',
                    'value' => '$' . number_format($aovCurrent, 0, ',', '.'),
                    'description' => 'Año pasado: $' . number_format($aovLastYear, 0, ',', '.') . ' (' . ($monthlyAovChange >= 0 ? '+' : '') . $monthlyAovChange . '%)',
                ],
            ],
        ];

        // === GENERAR PDF ===
        $pdf = \PDF::loadView('filament.bi.pdf.today-report', $data);

        // === ENVIAR CORREO ===
        \Mail::to('maximiliano.lopez.robles@gmail.com')->send(
            new \App\Mail\DailyReportPdf($pdf->output(), $data)
        );

        // === NOTIFICACIÓN EN PANEL ===
        \Filament\Notifications\Notification::make()
            ->title('Informe diario enviado')
            ->success()
            ->body('Se ha enviado el reporte de ventas actualizado con comparativas diarias y mensuales.')
            ->send();
    }

       
}
