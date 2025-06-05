
<div class="flex flex-col border border-red-500 bg-white rounded-lg shadow-sm overflow-hidden">

    {{-- Top: datos del producto --}}
    <div class="p-4 grow">
        {{-- SKU --}}
        <span class="block text-[0.7rem] uppercase tracking-wide text-gray-400 mb-1">
            <a href="{{ route('products.show', $product->slug) }}">
                {{ $product->sku }}
            </a>
        </span>

        {{-- Nombre --}}
        <h3 class="text-base font-semibold text-gray-800 mb-1 leading-snug">
            <a href="{{ route('products.show', $product->slug) }}">
                {{ $product->name }}
            </a>
        </h3>

        {{-- Familia | Marca --}}
        <span class="block text-xs text-gray-500 mb-3">
            {{ $product->family->name ?? '-' }} | {{ $product->brand->name ?? '-' }}
        </span>

        {{-- Precios --}}
        <dl class="text-sm text-gray-700 space-y-1">
            <div class="flex justify-between">
                <dt>Ref. ({{ $product->unit }})</dt>
                <dd>${{ number_format($product->unit_price, 0, ',', '.') }}</dd>
            </div>
            <div class="flex justify-between">
                <dt>
                    Venta ({{ $product->package_unit }})
                    @if($product->unit !== 'UN')
                        <small>({{ $product->package_qty }} {{ $product->unit }})</small>
                    @endif
                </dt>
                <dd>${{ number_format($product->package_price, 0, ',', '.') }}</dd>
            </div>
        </dl>
    </div>

    {{-- Acción: agregar al carrito --}}
    <div class="border-t px-4 py-3">
        <form method="POST" action="{{ route('cart.store') }}" class="flex items-center gap-2">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            {{-- Cantidad --}}
            <input
                type="number"
                name="quantity"
                value="1"
                min="1"
                class="w-16 h-8 border rounded text-center text-sm focus:ring-primary focus:border-primary/70"
            >

            {{-- Unidad de paquete --}}
            <span>{{ $product->package_unit }}</span>

            {{-- Botón al lado derecho --}}
            <x-pill-button
                type="submit"
                icon="ti ti-shopping-cart"
                :disabled="false"
                class="ml-auto"
            >
                Agregar
            </x-pill-button>
        </form>
    </div>
</div>