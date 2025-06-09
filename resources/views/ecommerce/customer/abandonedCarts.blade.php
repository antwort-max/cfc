// resources/views/ecommerce/customer/abandonedCarts.blade.php
@extends('ecommerce.layouts.app')

@section('title', 'Carros Abandonados')

@section('content')
<div class="max-w-5xl mx-auto my-12">
    <h1 class="text-2xl font-bold mb-6">Carros Abandonados</h1>

    @if($carts->isEmpty())
        <p class="text-gray-600">No tienes carros abandonados.</p>
    @else
        <table class="w-full table-auto text-sm border-collapse">
            <thead>
                <tr class="border-b">
                    <th class="py-2 text-left">ID Carro</th>
                    <th class="py-2 text-left">Fecha</th>
                    <th class="py-2 text-left">Items - Monto</th>
                    <th class="py-2 text-left">Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($carts as $cart)
                    <tr class="border-b">
                        <td class="py-3">#{{ $cart->id }}</td>
                        <td class="py-3">{{ $cart->created_at->format('d/m/Y H:i') }}</td>
                        <td class="py-3">{{ $cart->items->count() }} - $ {{ $cart->amount }}</td>
                        <td class="py-3">
                            <a href="{{ route('cart.show', $cart) }}"
                               class="px-4 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs">
                                Ver</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
