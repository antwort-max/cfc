<div class="min-h-screen w-full space-y-4 overflow-auto">
    @foreach ($data as $days => $periodData)
        <div class="bg-white shadow rounded-lg w-full">
            <div class="p-3 border-b flex justify-between items-center">
                <h3 class="text-xs font-semibold">Ultimos {{ $days }} dias</h3>
                {{-- Mostrar la suma total de ventas para este periodo --}}
                <p class="text-xs font-semibold text-green-700 dark:text-green-500">
                    Total de Ventas (Top 25): $ {{ number_format($periodData['period_total_sales'], 0, ',', '.') }}
                </p>
            </div>
            <table class="w-full text-2xs">
                <thead class="border-b">
                    <tr>
                        <th class="px-1.5 py-1 text-xs text-left">SKU</th>
                        <th class="px-1.5 py-1 text-xs text-left">Producto</th>
                        <th class="px-1.5 py-1 text-xs text-right">Ventas</th>
                        <th class="px-1.5 py-1 text-xs text-right">Stock</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Iterar sobre los items del periodo actual --}}
                    @foreach ($periodData['items'] as $item)
                        <tr>
                            <td class="px-1.5 py-1 text-xs">{{ $item->product_sku }}</td>
                            <td class="px-1.5 py-1 text-xs">{{ $item->product_name }}</td>
                            <td class="px-1.5 py-1 text-xs text-right">$ {{ number_format($item->total_sold, 0, ',', '.') }}</td>
                            <td class="px-1.5 py-1 text-xs text-right">{{ number_format($item->stock, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
</div>
