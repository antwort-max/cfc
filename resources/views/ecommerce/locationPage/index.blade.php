@extends('ecommerce.layouts.app')

@section('title', 'Nuestros Locales')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <h2 class="text-2xl font-semibold text-center mb-6">
        <i class="fa fa-map-marker-alt text-primary"></i>
        Nuestros Locales
    </h2>

    {{-- 📌 Filtros --}}
    <form method="GET" class="flex flex-col sm:flex-row sm:space-x-4 space-y-4 sm:space-y-0 mb-6">
        <div class="flex-1">
            <select name="city" onchange="this.form.submit()"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-primary focus:border-primary">
                <option value="">Todas las ciudades</option>
                @foreach ($cities as $city)
                    <option value="{{ $city }}" @selected(request('city') === $city)>{{ $city }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1">
            <select name="type" onchange="this.form.submit()"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-primary focus:border-primary">
                <option value="">Todos los tipos</option>
                @foreach ($types as $type)
                    <option value="{{ $type }}" @selected(request('type') === $type)>{{ ucfirst($type) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <a href="{{ route('locationPage') }}"
               class="inline-block w-full text-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50">
               Limpiar filtros
            </a>
        </div>
    </form>

    {{-- 📌 Mapa --}}
    <div id="map" class="w-full h-64 md:h-96 rounded-xl shadow mb-8"></div>

    {{-- 📌 Listado de Locales --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($locations as $location)
            <div class="bg-white rounded-xl shadow flex flex-col overflow-hidden">
                @if ($location->image)
                    <img src="{{ asset('storage/' . $location->image) }}"
                         alt="{{ $location->name }}"
                         class="h-48 w-full object-cover">
                @else
                    <div class="h-48 w-full bg-gray-100 flex items-center justify-center text-gray-400">
                        Sin imagen
                    </div>
                @endif

                <div class="p-4 flex-1 flex flex-col">
                    <h5 class="text-lg font-medium mb-2 flex items-center space-x-2">
                        <i class="fa fa-store text-primary"></i>
                        <span>{{ $location->name }}</span>
                    </h5>

                    <p class="text-sm text-gray-600 mb-1 flex items-center space-x-1">
                        <i class="fa fa-map-marker-alt"></i>
                        <span>{{ $location->address }}, {{ $location->city }}</span>
                    </p>
                    <p class="text-sm text-gray-600 mb-3 flex items-center space-x-1">
                        <i class="fa fa-phone"></i>
                        <span>{{ $location->phone ?? 'Sin teléfono' }}</span>
                    </p>

                    <p class="text-sm text-gray-600 mb-4 flex items-center space-x-1">
                        <i class="fa fa-tags"></i>
                        <span>Tipo: {{ ucfirst($location->type) }}</span>
                    </p>

                    @if (is_array($location->working_hours) && count($location->working_hours))
                        <div class="mt-auto">
                            <p class="text-sm font-semibold text-gray-700 mb-1 flex items-center space-x-1">
                                <i class="fa fa-clock"></i>
                                <span>Horarios</span>
                            </p>
                            <ul class="text-sm text-gray-600 space-y-1">
                                @foreach ($location->working_hours as $day => $hours)
                                    <li class="flex justify-between">
                                        <span class="capitalize">{{ $day }}</span>
                                        <span>
                                            {{ $hours['open'] ?? 'Cerrado' }} –
                                            {{ $hours['close'] ?? 'Cerrado' }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 mt-auto">
                            <i class="fa fa-clock"></i> No hay horario definido.
                        </p>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <p class="text-yellow-700 text-center">No se encontraron locales con los filtros seleccionados.</p>
                </div>
            </div>
        @endforelse
    </div>

 
</div>

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        const map = L.map('map').setView([-33.4489, -70.6693], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        @foreach ($locations as $location)
            @if ($location->latitude && $location->longitude)
                L.marker([{{ $location->latitude }}, {{ $location->longitude }}])
                    .addTo(map)
                    .bindPopup(`
                        <strong>{{ $location->name }}</strong><br>
                        {{ $location->address }}<br>
                        <i class="fa fa-phone"></i> {{ $location->phone ?? 'Sin teléfono' }}
                    `);
            @endif
        @endforeach
    </script>
@endpush
@endsection
