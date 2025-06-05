<x-filament::page>
    <h2 class="text-xl font-bold mb-6">Listado de atrasos – mes de {{ now()->translatedFormat('F Y') }}</h2>
    <div class="flex justify-end mb-4">
        <div class="flex justify-end space-x-2 mb-4">
            <x-filament::button color="gray" onclick="window.print()">
                Imprimir reporte
            </x-filament::button>

            <form wire:submit.prevent="sendReport">
                <x-filament::button type="submit" color="primary">
                    Enviar por correo
                </x-filament::button>
            </form>
        </div>
    </div>

    @if ($employeeTotals->isEmpty())
        <p class="text-gray-600">No hay registros de atrasos para este mes.</p>
    @else
        <table class="w-full table-auto text-sm border border-gray-300">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="px-3 py-2">#</th>
                    <th class="px-3 py-2">Empleado</th>
                    <th class="px-3 py-2">Cantidad de atrasos</th>
                    <th class="px-3 py-2">Tiempo perdido</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employeeTotals as $index => $item)
                    @php
                        $hours = intdiv($item['total_minutes'], 60);
                        $minutes = $item['total_minutes'] % 60;
                    @endphp
                    <tr class="border-t border-gray-200">
                        <td class="px-3 py-2">{{ $index + 1 }}</td>
                        <td class="px-3 py-2">{{ $item['employee'] }}</td>
                        <td class="px-3 py-2">{{ $item['count'] }}</td>
                        <td class="px-3 py-2">
                            {{ str_pad($hours, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($minutes, 2, '0', STR_PAD_LEFT) }} hrs
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</x-filament::page>

