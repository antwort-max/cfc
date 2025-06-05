@extends('ecommerce.layouts.app')

@section('content')

<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Imagen del producto --}}
        <div class="w-full">
            <img
                src="{{ asset('storage/' . $product->image) }}"
                alt="{{ $product->name }}"
                class="w-full rounded-lg object-cover max-h-[400px] mx-auto"
            >
        </div>

        {{-- Información del producto --}}
        <div class="space-y-4">

            {{-- Nombre --}}
            <h1 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h1>

            {{-- SKU y marca --}}
            <p class="text-sm text-gray-500">
                SKU: {{ $product->sku }} |
                Marca: {{ $product->brand->name ?? '-' }} |
                Familia: {{ $product->family->name ?? '-' }}
            </p>

            {{-- Descripción --}}
            <div class="text-gray-700">
                {{ $product->description }}
            </div>

            {{-- Precios --}}
            <div class="text-lg font-medium text-gray-900 space-y-1">
                <p>Precio unitario ({{ $product->unit }}): ${{ number_format($product->unit_price, 0, ',', '.') }}</p>
                <p>
                    Precio por {{ $product->package_unit }}:
                    ${{ number_format($product->package_price, 0, ',', '.') }}
                    @if($product->unit !== 'UN')
                        <small class="text-gray-500">({{ $product->package_qty }} {{ $product->unit }})</small>
                    @endif
                </p>
            </div>

            {{-- Formulario para agregar al carrito --}}
            <form method="POST" action="{{ route('cart.store') }}" class="flex items-center gap-2">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <input
                    type="number"
                    name="quantity"
                    value="1"
                    min="1"
                    class="w-16 h-10 border rounded text-center text-sm focus:ring-primary focus:border-primary"
                >

                {{ $product->package_unit }}

                <x-pill-button type="submit" icon="ti ti-shopping-cart" :disabled="false">
                    Agregar
                </x-pill-button>
            </form>
            {{-- Botones de navegación --}}
            <div class="flex flex-wrap justify-between items-center mb-6">
                <a href="{{ url()->previous() }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                    ← Volver 
                </a>

                <a href="{{ route('cart.show') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                    🛒 Carro
                </a>
            </div>
        </div>
    </div>

</div>

@endsection
