<?php

namespace App\Filament\Bi\Pages;

use Filament\Pages\Page;
use Filament\Pages\Actions\Action;
use App\Filament\Bi\Widgets\TopSellingProductsReport;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProductAnalysisReportPdf;
use Filament\Notifications\Notification;


class ProductAnalysis extends Page
{
    protected static string $view = 'filament.bi.pages.product-analysis';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Analisis de Productos';
    protected static ?string $title = 'Analisis de Productos';
    protected static ?string $navigationGroup = 'Análisis';

    // Inyectamos el widget
    protected function getHeaderWidgets(): array
    {
        return [
            TopSellingProductsReport::class,
        ];
    }

    // Registramos la acción en el header
    protected function getActions(): array
    {
        return [
            Action::make('exportEmail')
                ->label('Export & Email PDF')
                ->icon('heroicon-s-document-arrow-down')
                ->color('primary')
                ->action('handleExportEmail'),
        ];
    }

    // Método que se dispara al hacer clic
    public function handleExportEmail(): void
    {
        // 1) Recolectar los datos como los usa el widget
        $data = app(TopSellingProductsReport::class)->getData()['data'];

        // 2) Generar el PDF
        $pdf = \PDF::loadView('filament.bi.pdf.product-analysis', compact('data'));

        // 3) Enviar el correo, pasándole $data también
        Mail::to('maximiliano.lopez.robles@gmail.com')
            ->send(new ProductAnalysisReportPdf($pdf->output(), $data));

        Notification::make()
            ->title('Informe enviado')
            ->success()
            ->body('El informe fue enviado por correo satisfactoriamente.')
            ->send();
    }

    // Helper para extraer los datos
    protected function getReportData(): array
    {
        return app(TopSellingProductsReport::class)->getData()['data'];
    }
}
