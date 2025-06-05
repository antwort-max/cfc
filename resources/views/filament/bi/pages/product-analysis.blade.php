<x-filament::page class="w-full max-w-full p-0 m-0 overflow-hidden">
    <x-slot name="headerWidgets">
        <div class="w-full grid grid-cols-1 gap-6 lg:grid-cols-1 overflow-auto">
            @foreach ($this->getHeaderWidgets() as $widget)
                <div class="w-full">
                    {!! $widget !!}
                </div>
            @endforeach
        </div>
    </x-slot>
</x-filament::page>
