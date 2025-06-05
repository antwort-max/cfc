<x-filament::page>
    <form wire:submit.prevent="submit" class="flex items-end space-x-4">
        <div class="flex flex-col">
            <label for="days" class="text-sm font-medium text-gray-700">Período (días)</label>
            <input
                id="days"
                type="number"
                min="1"
                wire:model.defer="days"
                class="mt-1 block w-24 border-gray-300 rounded shadow-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
            />
            @error('days')
                <span class="text-xs text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <x-filament::button type="submit">
            Aplicar
        </x-filament::button>
    </form>

    {{-- El PieChart se mostrará automáticamente arriba, como header widget --}}
</x-filament::page>