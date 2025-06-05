<x-filament::page>
    
    <div class="space-y-6">
        {{-- Formulario de fechas --}}
        <form wire:submit.prevent>
            {{ $this->form }}
        </form>

        {{-- Tabla de resultados --}}
        <div class="overflow-x-auto rounded-xl shadow">
            <table class="min-w-full text-sm text-left rtl:text-right text-gray-700">
                <thead class="bg-gray-100 font-semibold">
                    <tr>
                        <th class="px-4 py-2">Categoria</th> 
                        <th class="px-4 py-2">SKU</th>
                        <th class="px-4 py-2">Nombre del Producto</th>
                        <th class="px-4 py-2 text-right">Cantidad Vendida</th>
                        <th class="px-4 py-2 text-right">Total Vendido</th>
                        <th class="px-4 py-2 text-right">Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sales as $sale)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $sale['category_name'] }}</td>
                            <td class="px-4 py-2">{{ $sale['product_sku'] }}</td>
                            <td class="px-4 py-2">{{ $sale['product_name'] }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($sale['total_qty'], 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-right">$ {{ number_format($sale['total_sales'], 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-right"> {{ number_format($sale['stock'], 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">No se encontraron ventas en el rango seleccionado.</td>
                        </tr>
                    @endforelse
                    <div class="mt-4">
</div>
                </tbody>
            </table>
        </div>
    </div>
</x-filament::page>
