<?php

namespace App\Filament\Bi\Pages;

use Filament\Pages\Page;
use App\Models\HistoricDocument;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ClientSalesAnalysis extends Page
{
    protected static string $view = 'filament.bi.pages.client-sales-analysis';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Análisis de Ventas';
    protected static ?string $navigationLabel = 'Análisis Clientes';

    public int $period = 30;
    public Collection $topClients;

    public function mount(): void
    {
        $this->loadClientData();
    }

    public function updatedPeriod(): void
    {
        $this->loadClientData();
    }

    public function loadClientData(): void
    {
        $fromDate = now()->subDays($this->period);

        $this->topClients = HistoricDocument::query()
            ->selectRaw('client, seller, SUM(total_sales_amount_with_tax) as total')
            ->where('document_date', '>=', $fromDate)
            ->groupBy('client', 'seller')
            ->orderByDesc('total')
            ->limit(50)
            ->get();
    }
}
