@extends('ecommerce.layouts.app')

@section('title', 'Carrito de compras')

@section('content')
    <div class="max-w-5xl mx-auto my-12">

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="mb-6 flex items-center justify-between">
            <a href="{{ url()->previous() }}"
            class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded shadow text-sm font-medium">
                ← Volver
            </a>

            <h1 class="text-2xl md:text-3xl font-bold flex items-baseline space-x-2">
                <span>Carrito {{ $cart->id }}</span>
                <span class="text-base font-medium text-gray-600">
                    ({{ $cart->updated_at->format('D: d/m/Y H:i') }})
                </span>
            </h1>
        </div>

        @if ($cart->items->isEmpty())
            <p class="text-gray-600">Tu carrito está vacío.</p>
        @else
            <table class="w-full table-auto text-sm">
                <thead class="border-b">
                    <tr>
                        <th class="py-2 text-left">Producto</th>
                        <th class="py-2 text-left">Precio Unit.</th>
                        <th class="py-2 text-left">Cantidad</th>
                        <th class="py-2 text-right">Subtotal</th>
                        <th class="py-2"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cart->items as $item)
                        <tr class="border-b">
                            <td class="py-3">
                                <a href="{{ route('products.show', $item->product) }}"
                                    class="text-blue-600 hover:underline">
                                    {{ $item->name }}
                                </a>
                            </td>
                            <td class="py-3">$ {{ number_format($item->package_price, 0, ',', '.') }}</td>
                            <td class="py-3">
                                <form action="{{ route('cart.update', $item) }}" method="POST" class="inline-flex items-center gap-1">
                                    @csrf @method('PATCH')
                                    <input type="number" name="quantity" min="1" value="{{ $item->quantity }}" class="w-16 border rounded text-center">
                                    {{ $item->package_unit }}
                                    <x-button type="submit" icon="ti ti-reload">Actualiza</x-button>
                                </form>
                            </td>
                            <td class="py-3 text-right">
                                ${{ number_format($item->package_price * $item->quantity, 0, ',', '.') }}
                            </td>
                            <td class="py-3 text-right">
                                <form action="{{ route('cart.destroy', $item) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <x-button type="submit" icon="ti ti-trash">Eliminar</x-button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Total de la venta y acciones --}}
@php
    $neto = $cart->items->sum(fn($item) => $item->package_price * $item->quantity);
    $iva = round($neto * 0.19);
    $total = $neto + $iva;
@endphp

<div class="mt-6 grid md:grid-cols-2 gap-6 border-t pt-4">

    {{-- Acciones del cliente --}}
    <div class="flex items-center space-x-3">
        <a href="{{ route('customer.login', [
            'redirect' => route('cart.show')
            ]) }}"
            class="px-3 py-1.5 ...">
            Seleccionar / Crear Cliente
        </a>

        <a href="{{ route('checkout.show') }}"
           class="px-3 py-1.5 text-sm bg-green-600 hover:bg-green-700 text-white rounded font-medium">
            Checkout rápido
        </a>
    </div>

    {{-- Totales --}}
    <div class="text-right text-sm space-y-1">
        <div class="flex justify-between">
            <span class="text-gray-600">Subtotal Neto:</span>
            <span>${{ number_format($neto, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-600">IVA (19%):</span>
            <span>${{ number_format($iva, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between text-lg font-semibold border-t pt-2">
            <span>Total a Pagar:</span>
            <span>${{ number_format($total, 0, ',', '.') }}</span>
        </div>
    </div>
</div>
        @endif
    </div>
@endsection
