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
        
        <h1 class="text-3xl font-bold mb-6 flex items-baseline space-x-2">
            <span>Carrito {{ $cart->id }}</span>
            <span class="text-base font-medium text-gray-600">
                ({{ $cart->updated_at->format('D: d/m/Y H:i') }})
            </span>
        </h1>

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

            {{-- Total de la venta y botón de cliente --}}
            <div class="mt-6 flex justify-between items-center border-t pt-4">
                {{-- Cálculo del total --}}
                @php
                    $total = $cart->items->sum(fn($item) => $item->package_price * $item->quantity);
                @endphp

                <span class="text-xl font-semibold">
                    Total: ${{ number_format($total, 0, ',', '.') }}
                </span>

                {{-- Botón seleccionar o crear cliente --}}
                <a href="{{ route('customer.login') }}"
                   class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                    Seleccionar / Crear Cliente
                </a>
            </div>

            {{-- Checkout rápido --}}
            <div class="mt-6 flex justify-end">
                <a href="{{ route('checkout.show') }}"
                   class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium">
                    Checkout rápido
                </a>
            </div>
        @endif
    </div>
@endsection
