@inject('cartService', \App\Services\CartService::class)

@php
    // Obtenemos el carrito actual y el número de items
    $currentCart = $cartService->current();
    $cartCount   = $currentCart->items->count();
@endphp

<div class="w-full bg-white border-b mt-12">
    <div class="container mx-auto flex items-center justify-between px-4 py-2">
        <!-- Izquierda: Dirección y Teléfono -->
        <div class="flex items-center space-x-4">
            <x-link-button
                href="{{ route('locationPage') }}"
                icon="ti ti-map-pin"
                color="gray"
            >
                {{ $saleBanner['address'] }}
            </x-link-button>

            <x-link-button
                href="tel:{{ preg_replace('/\s+/', '', $saleBanner['phone']) }}"
                icon="ti ti-phone"
                color="gray"
            >
                {{ $saleBanner['phone'] }}
            </x-link-button>
        </div>

        <!-- Centro: Buscador de productos -->
        <div class="flex-1 px-4">
            <form action="{{ route('products.search') }}" method="GET" class="relative">
                <label for="buscador" class="sr-only">Buscar productos</label>
                <input
                    id="buscador"
                    type="text"
                    name="q"
                    placeholder="Buscar productos..."
                    class="w-full border border-gray-300 rounded-full py-2 px-4 focus:outline-none focus:ring-2 focus:ring-primary"
                />
                <button
                    type="submit"
                    class="absolute right-3 top-1/2 -translate-y-1/2 focus:outline-none"
                >
                    <span class="sr-only">Buscar</span>
                    <i class="ti ti-search"></i>
                </button>
            </form>
        </div>

        <!-- Derecha: Carro de compra y Usuario -->
        <div class="flex items-center space-x-4">
            {{-- Carrito --}}
            <x-link-button
                href="{{ route('cart.show') }}"
                icon="ti ti-shopping-cart"
                color="gray"
                class="relative"
            >
                Carro
                @if($cartCount > 0)
                    <span
                        class="absolute -top-1 -right-1 inline-flex items-center justify-center
                               px-2 py-0.5 text-xs font-bold leading-none text-white
                               bg-red-600 rounded-full"
                    >
                        {{ $cartCount }}
                    </span>
                @endif
            </x-link-button>

            {{-- Si el cliente está autenticado: menú desplegable --}}
            @auth('customer')
                <div x-data="{ open: false }" class="relative">
                    <button
                        @click="open = !open"
                        class="inline-flex items-center px-3 py-1 rounded bg-gray-100 hover:bg-gray-200"
                    >
                        <i class="ti ti-user mr-1"></i>
                        {{ auth('customer')->user()->first_name }}
                        <i class="ti ti-chevron-down ml-1"></i>
                    </button>

                    <div
                        x-show="open"
                        @click.away="open = false"
                        class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg z-20"
                    >
                        <a href="{{ route('customer.profile') }}"
                           class="block px-4 py-2 hover:bg-gray-100 text-gray-700">
                            Mi ficha
                        </a>
                        <a href="{{ route('customer.sales') }}"
                           class="block px-4 py-2 hover:bg-gray-100 text-gray-700">
                            Mis ventas
                        </a>
                        <a href="{{ route('customer.abandonedCarts') }}"
                           class="block px-4 py-2 hover:bg-gray-100 text-gray-700">
                            Carros abandonados
                        </a>
                        <form method="POST" action="{{ route('customer.logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full text-left px-4 py-2 hover:bg-gray-100 text-gray-700">
                                Salir
                            </button>
                        </form>
                    </div>
                </div>

            {{-- Si NO está logeado: botón Login --}}
            @else
                <x-link-button
                    href="{{ route('customer.login') }}"
                    icon="ti ti-user"
                    color="gray"
                >
                    Login
                </x-link-button>
            @endauth
        </div>
    </div>
</div>

{{-- Asegúrate de incluir Alpine.js en tu layout principal para que funcione x-data, x-show, @click --}}
<script src="//unpkg.com/alpinejs" defer></script>
