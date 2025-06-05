@php
    $sum_sales = $this->categorySales->sum('total_sales');
    $sum_cost  = $this->categorySales->sum('total_cost');
    $sum_margin = $this->categorySales->sum('total_margin');
@endphp
<x-filament::page>
    <div class="space-y-6">
        {{-- Filtro de fechas --}}
        <form wire:submit.prevent>
            {{ $this->form }}
        </form>

        {{-- Tabla de resultados --}}
        <div class="overflow-x-auto rounded-xl shadow">
            <table class="min-w-full text-sm text-left text-gray-800">
                <thead class="bg-gray-100 font-semibold">
                    <tr>
                        <th class="px-4 py-2">Categoría</th>
                        <th class="px-4 py-2 text-right">Cantidad Vendida</th>
                        <th class="px-4 py-2 text-right">Total Vendido</th>
                        <th class="px-4 py-2 text-right">Total Costo</th>
                        <th class="px-4 py-2 text-right">Contribución</th>
                        <th class="px-4 py-2 text-right">Participación</th>
                        <th class="px-4 py-2 text-right">Margen</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-yellow-100 font-semibold">
                        <td class="px-4 py-2">TOTAL</td>
                        <td class="px-4 py-2 text-right">—</td>
                        <td class="px-4 py-2 text-right">$ {{ number_format($sum_sales, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-right">$ {{ number_format($sum_cost, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-right">$ {{ number_format($sum_margin, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-right"> 100 %</td>
                        <td class="px-4 py-2 text-right"> {{ number_format($sum_margin / $sum_sales * 100, 2, ',', '.') }} %</td>
                    </tr>

                    @forelse ($this->categorySales as $sale)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $sale->category_name }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($sale->total_quantity, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-right">$ {{ number_format($sale->total_sales, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-right">$ {{ number_format($sale->total_cost, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-right">$ {{ number_format($sale->total_margin, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-right"> {{ number_format($sale->total_sales / $sum_sales * 100, 2, ',', '.') }} %</td>
                            <td class="px-4 py-2 text-right"> {{ number_format($sale->total_margin / $sale->total_sales * 100, 2, ',', '.') }} %</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-gray-500">No hay datos para el rango seleccionado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament::page>
