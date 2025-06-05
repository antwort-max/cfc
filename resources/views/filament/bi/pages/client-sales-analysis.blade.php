<x-filament::page>
    <div class="max-w-xl mx-auto mb-6">
       <select id="period" wire:model="period" wire:change="loadClientData" class="border-gray-300 rounded-md">
            <option value="30">Últimos 30 días</option>
            <option value="60">Últimos 60 días</option>
            <option value="120">Últimos 120 días</option>
            <option value="360">Últimos 360 días</option>
        </select>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full table-auto text-sm border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">#</th>
                    <th class="px-4 py-2 text-left">Cliente</th>
                    <th class="px-4 py-2 text-left">Vendedor</th>
                    <th class="px-4 py-2 text-right">Total Ventas</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($topClients as $index => $client)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">{{ $client->client }}</td>
                        <td class="px-4 py-2">{{ $client->seller }}</td>
                        <td class="px-4 py-2 text-right">${{ number_format($client->total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-2 text-center text-gray-500">No hay datos disponibles.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament::page>
