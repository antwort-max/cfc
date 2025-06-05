// resources/views/ecommerce/customer/sales.blade.php
@extends('ecommerce.layouts.app')

@section('title', 'Mis Ventas')

@section('content')
<div class="max-w-5xl mx-auto my-12">
    <h1 class="text-2xl font-bold mb-6">Mis Ventas</h1>

    @if($sales->isEmpty())
        <p class="text-gray-600">No tienes ventas registradas.</p>
    @else
        <table class="w-full table-auto text-sm border-collapse">
            <thead>
                <tr class="border-b">
                    <th class="py-2 text-left">ID</th>
                    <th class="py-2 text-left">Fecha</th>
                    <th class="py-2 text-left">Estado</th>
                    <th class="py-2 text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $order)
                    <tr class="border-b">
                        <td class="py-3">#{{ $order->id }}</td>
                        <td class="py-3">{{ $order->created_at->format('d/m/Y') }}</td>
                        <td class="py-3">{{ ucfirst($order->status) }}</td>
                        <td class="py-3 text-right">$ {{ number_format($order->amount, 0, ',', '.') }}</td>
                   
                        <td class="py-3 flex space-x-2">
                            <!-- Ver detalles -->
                            <a href="{{ route('cart.showAbandoned', $order->id) }}"
                            class="px-4 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs">
                                Ver
                            </a>

                            <!-- Restaurar carro -->
                            <form action="{{ route('cart.restore', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="px-4 py-1 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs">
                                    Restaurar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection