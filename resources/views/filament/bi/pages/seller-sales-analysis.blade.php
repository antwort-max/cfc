<x-filament::page>
   <div class="flex flex-wrap items-center justify-between max-w-5xl mx-auto mb-6 gap-4">
        Seleccione Periodo
        <select wire:model="period" wire:change="loadSellerData"
            class="border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring focus:ring-blue-200">
            <option value="1">Hoy</option>
            <option value="7">Últimos 7 días</option>
            <option value="15">Últimos 15 días</option>
            <option value="30">Últimos 30 días</option>
            <option value="60">Últimos 60 días</option>
            <option value="90">Últimos 90 días</option>
            <option value="120">Últimos 120 días</option>
            <option value="150">Últimos 150 días</option>
            <option value="360">Últimos 360 días</option>
        </select>

        {{-- Botón para descargar PDF --}}
        <x-filament::button tag="a" href="{{ route('seller-sales-report') }}" icon="heroicon-o-arrow-down-tray">
            PDF
        </x-filament::button>

        {{-- Botón para enviar correo --}}
       <x-filament::button wire:click="sendEmail" color="primary" icon="heroicon-o-envelope">
        Enviar Informe 
        </x-filament::button>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full table-auto text-sm border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">#</th>
                    <th class="px-4 py-2 text-left">Vendedor</th>
                    <th class="px-4 py-2 text-right">Total Ventas</th>
                    <th class="px-4 py-2 text-right">% Participación</th>
                    <th class="px-4 py-2 text-right">Cantidad Docs</th>
                    <th class="px-4 py-2 text-right">Promedio por Doc</th>
                    <th class="px-4 py-2 text-right">Marge Promedio</th>
                    <th class="px-4 py-2 text-right">Contribución</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $salesAvgDocument = $totalSalesAmount / $totalDocuments;
                @endphp

                @forelse ($topSellers as $index => $seller)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">{{ $seller->seller_name }}</td>
                        <td class="px-4 py-2 text-right">
                            ${{ number_format($seller->products_sales, 0, ',', '.') }}
                            @if ($seller->products_sales < $avgSalesPerSeller)
                                <span class="ml-1 text-red-600">▼</span>
                            @else
                                <span class="ml-1 text-green-600">▲</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-right">{{ number_format($seller->percentage, 2,',','.') }}%</td>
                        <td class="px-4 py-2 text-right">
                            {{ number_format($seller->document_count, 0, ',', '.') }}
                            @if ($seller->document_count < $avgDocsPerSeller)
                                <span class="ml-1 text-red-600">▼</span>
                            @else
                                <span class="ml-1 text-green-600">▲</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-right">
                            ${{ number_format($seller->avg_per_document, 0, ',', '.') }}
                            @if ($seller->avg_per_document < $salesAvgDocument)
                                <span class="ml-1 text-red-600">▼</span>
                            @else
                                <span class="ml-1 text-green-600">▲</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-right">
                            {{ number_format($seller->margin, 2, ',', '.') }} %
                            @if ($seller->margin < $avgMargin)
                                <span class="ml-1 text-red-600">▼</span>
                            @else
                                <span class="ml-1 text-green-600">▲</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-right">
                            ${{ number_format($seller->profit, 0, ',', '.') }}
                            @if ($seller->profit < $totalContributionAvg)
                                <span class="ml-1 text-red-600">▼</span>
                            @else
                                <span class="ml-1 text-green-600">▲</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-2 text-center text-gray-500">No hay datos disponibles.</td>
                    </tr>
                @endforelse

                <tr class="bg-gray-50 font-semibold border-t">
                    <td class="px-4 py-2 text-left" colspan="2">Totales</td>
                    <td class="px-4 py-2 text-right">$ {{ number_format($totalSalesAmount, 0, ',', '.') }}</td>
                    <td class="px-4 py-2 text-right">100%</td>
                    <td class="px-4 py-2 text-right">{{ number_format($totalDocuments, 0, ',', '.') }}</td>
                    <td class="px-4 py-2 text-right"></td>
                    <td class="px-4 py-2 text-right"></td>
                    <td class="px-4 py-2 text-right">$ {{ number_format($totalContributions, 0, ',', '.') }}</td>
                </tr>
                <tr class="bg-gray-50 font-semibold border-t">
                    <td class="px-4 py-2 text-left" colspan="2">Promedios</td>
                    <td class="px-4 py-2 text-right">$ {{ number_format($avgSalesPerSeller, 0, ',', '.') }}</td>
                    <td class="px-4 py-2 text-right"></td>
                    <td class="px-4 py-2 text-right">{{ number_format($avgDocsPerSeller, 0) }}</td>
                    <td class="px-4 py-2 text-right">$ {{ number_format($salesAvgDocument, 0, ',', '.') }}</td>
                    <td class="px-4 py-2 text-right">{{ number_format($avgMargin, 2) }} %</td>
                    <td class="px-4 py-2 text-right">$ {{ number_format($totalContributionAvg, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</x-filament::page>
