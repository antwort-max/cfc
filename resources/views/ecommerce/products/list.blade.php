@extends('ecommerce.layouts.app')

@section('content')

    @include('ecommerce.partials.navbar', ['menus' => $menus])

    @if(!empty($banner))
        <br>
        @include('ecommerce.partials.banner', ['banner' => $banner])
    @endif

    @include('ecommerce.partials.categories', ['categories' => $categories])

    <div class="container mx-auto py-8">

        {{-- Filtro de marcas --}}
        @if(isset($brands) && $brands->isNotEmpty())
            <h5 class="text-lg font-semibold mb-2 text-center">
                <small>Marcas: {{ $brands->count() }}</small>
            </h5>
            <div class="flex flex-wrap gap-2 mb-4 justify-center">
                @foreach($brands as $brand)
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                        <small>
                            <a href="{{ route('products.byBrand', $brand) }}">
                                {{ $brand->name }}
                            </a>
                        </small>
                    </span>
                @endforeach
            </div>
        @endif

        {{-- Conteo de resultados --}}
        <h4 class="text-xl font-semibold mb-6 text-left">
            Productos Encontrados: {{ $products->count() }}
        </h4>

        @if($products->isEmpty())
            <p class="text-gray-600">No se encontraron productos para tu búsqueda.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                    @include('ecommerce.partials.product-card', ['product' => $product])
                @endforeach
            </div>
        @endif
        <div class="mt-6">
          {{ $products->links() }}
        </div>
    </div>
@endsection