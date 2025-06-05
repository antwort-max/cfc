@extends('ecommerce.layouts.app')

@section('title', 'Checkout')

@section('content')
    <div class="max-w-4xl mx-auto my-12">
        <h1 class="text-3xl font-bold mb-6">Checkout</h1>

        {{-- Resumen del carrito --}}
        <div class="bg-white shadow rounded p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Resumen de tu carrito</h2>
            @if($currentCart->items->isEmpty())
                <p class="text-gray-600">Tu carrito está vacío.</p>
            @else
                <table class="w-full table-auto text-sm">
                    <thead class="border-b">
                        <tr>
                            <th class="py-2 text-left">Producto</th>
                            <th class="py-2 text-center">Unidad</th>
                            <th class="py-2 text-right">Precio</th>
                            <th class="py-2 text-center">Cantidad</th>
                            <th class="py-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($currentCart->items as $item)
                            <tr class="border-b">
                                <td class="py-3">{{ $item->name }}</td>
                                <td class="py-3">{{ $item->package_qty." ".$item->package_unit }}</td>

                                 <td class="py-3 text-right">
                                    ${{ number_format($item->package_price, 0, ',', '.') }}
                                </td>
                                <td class="py-3 text-center">{{ $item->quantity }}</td>
                                <td class="py-3 text-right">
                                    ${{ number_format($item->package_price * $item->quantity, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4 text-right">
                    <p class="font-semibold">Impuestos: ${{ number_format($currentCart->taxes, 0, ',', '.') }}</p>
                    <p class="text-xl font-bold">Total: ${{ number_format($currentCart->amount, 0, ',', '.') }}</p>
                </div>
            @endif
        </div>

        {{-- Formulario de Checkout --}}
        <form action="{{ route('checkout.process') }}" method="POST" class="bg-white shadow rounded p-6">
            @csrf

                    {{-- Método de envío --}}
            <div class="mb-4">
                <label for="send_method" class="block font-medium mb-1">Método de envío de resumen</label>
                <select name="send_method" id="send_method" class="w-full border rounded px-3 py-2">
                    <option value="email" {{ old('send_method')=='email'?'selected':'' }}>Email</option>
                    <option value="printed" {{ old('send_method')=='printed'?'selected':'' }}>Impreso</option>
                    <option value="whatsapp" {{ old('send_method')=='whatsapp'?'selected':'' }}>WhatsApp</option>
                </select>
                @error('send_method')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Correo destinatario (solo si email) --}}
            <div id="email_field" class="mb-4 hidden">
                <label for="recipient_email" class="block font-medium mb-1">Correo destinatario</label>
                <input
                    type="email"
                    name="recipient_email"
                    id="recipient_email"
                    value="{{ old('recipient_email') }}"
                    class="w-full border rounded px-3 py-2"
                    placeholder="cliente@ejemplo.cl"
                >
                @error('recipient_email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Teléfono destinatario (solo si whatsapp) --}}
            <div id="phone_field" class="mb-4 hidden">
                <label for="recipient_phone" class="block font-medium mb-1">Teléfono destinatario</label>
                <input
                    type="text"
                    name="recipient_phone"
                    id="recipient_phone"
                    value="{{ old('recipient_phone') }}"
                    class="w-full border rounded px-3 py-2"
                    placeholder="+569XXXXXXXX"
                >
                @error('recipient_phone')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Comentarios / Instrucciones --}}
            <div class="mb-6">
                <label for="explanation" class="block font-medium mb-1">Comentarios / Instrucciones</label>
                <textarea name="explanation" id="explanation" rows="4" class="w-full border rounded px-3 py-2">{{ old('explanation') }}</textarea>
                @error('explanation')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- NUEVOS BOTONES --}}
            <div class="mb-6 flex flex-col md:flex-row md:space-x-4">
                <button 
                    type="submit" 
                    name="action" 
                    value="quote" 
                    class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-3 rounded font-semibold"
                >
                    Crear cotización
                </button>

                <button 
                    type="submit" 
                    name="action" 
                    value="email" 
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded font-semibold"
                >
                    Enviar por correo
                </button>
            </div>

            {{-- BOTÓN DE COMPRA --}}
            <button 
                type="submit" 
                name="action" 
                value="purchase" 
                class="w-full bg-primary hover:opacity-90 text-white py-3 rounded font-semibold"
            >
                Finalizar compra
            </button>
        </form>
    </div>
@endsection

{{-- Script para alternar campos --}}
            @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const method = document.getElementById('send_method');
                    const emailField = document.getElementById('email_field');
                    const phoneField = document.getElementById('phone_field');

                    function toggleFields() {
                        emailField.classList.toggle('hidden', method.value !== 'email');
                        phoneField.classList.toggle('hidden', method.value !== 'whatsapp');
                    }

                    method.addEventListener('change', toggleFields);
                    toggleFields(); // initialize on load
                });
            </script>
            @endpush


