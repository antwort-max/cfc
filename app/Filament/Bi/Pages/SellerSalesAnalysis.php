<?php

namespace App\Filament\Bi\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\SellerSalesReport;
use Illuminate\Http\Response;
use Filament\Notifications\Notification;

class SellerSalesAnalysis extends Page
{
    protected static string $view = 'filament.bi.pages.seller-sales-analysis';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Análisis de Ventas';
    protected static ?string $navigationLabel = 'Análisis Vendedores';

    // === Propiedades públicas para la vista ===
    public int $period = 32;
    public Collection $topSellers;
    public float $totalSalesAmount = 0;
    public int $totalDocuments = 0;
    public float $avgDocsPerSeller = 0;
    public float $avgSalesPerSeller = 0;
    public float $totalContributionAvg = 0;
    public float $avgMargin = 0;
    public float $totalContributions = 0;

    public function mount(): void
    {
        $this->loadSellerData();
    }

    public function updatedPeriod(): void
    {
        $this->loadSellerData();
    }

    public function loadSellerData(): void
    {
        $fromDate = now()->subDays($this->period)->toDateString();

        // === Consulta base con JOIN a historic_products y wrk_employees ===
        $sellers = DB::table('historic_documents as hd')
            ->join('wrk_employees as e', 'hd.seller', '=', 'e.code')
            ->join('historic_products as hp', function ($join) {
                $join->on('hd.document_number', '=', 'hp.document_number')
                     ->on('hd.document_type', '=', 'hp.document_type');
            })
            ->selectRaw('
                e.name as seller_name,
                e.code as seller_code,
                COUNT(DISTINCT hd.document_number) as document_count,
                SUM(hp.product_price * hp.quantity) as products_sales,
                SUM(hp.product_cost * hp.quantity) as products_cost
            ')
            ->where('hd.document_date', '>=', $fromDate)
            ->groupBy('e.name', 'e.code')
            ->orderByDesc('products_sales')
            ->get();

        // === Cálculos generales ===
        $totalSales = $sellers->sum('products_sales');
        $totalDocs = $sellers->sum('document_count');
        $sellerCount = $sellers->count();

        $this->totalSalesAmount = $totalSales;
        $this->totalDocuments = $totalDocs;
        $this->avgDocsPerSeller = $this->safeDivide($totalDocs, $sellerCount, 1);
        $this->avgSalesPerSeller = $this->safeDivide($totalSales, $sellerCount, 0);

        // === Procesamiento por vendedor ===
        $this->topSellers = $sellers->map(function ($seller) use ($totalSales) {
            $seller->percentage = $seller->products_sales / $totalSales  * 100;
            $seller->avg_per_document = $this->safeDivide($seller->products_sales, $seller->document_count, 0);
            $seller->profit = $seller->products_sales - $seller->products_cost;
            $seller->margin = $seller->profit / $seller->products_sales * 100;
            return $seller;
        });

        // === Métricas adicionales ===
        $this->totalContributions = $this->topSellers->sum('profit');
        $this->totalContributionAvg = $this->topSellers->avg('profit');
        $this->avgMargin = $this->topSellers->avg('margin');
    }

    private function safeDivide(float|int $numerator, float|int $denominator, int $precision = 0): float
    {
        return $denominator != 0 ? round($numerator / $denominator, $precision) : 0;
    }

    public function downloadPdf(): Response
    {
        $this->loadSellerData();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('filament.bi.pdf.seller-report', [
            'topSellers' => $this->topSellers,
            'period' => $this->period,
        ]);

        return $pdf->download('informe-vendedores.pdf');
    }

    public function sendEmail(): void
    {
        $this->loadSellerData();

        Mail::to('maximiliano.lopez.robles@gmail.com')->send(
            new SellerSalesReport($this->topSellers, $this->period)
        );

        Notification::make()
            ->title('Informe enviado por correo')
            ->success()
            ->send();
    }
}
