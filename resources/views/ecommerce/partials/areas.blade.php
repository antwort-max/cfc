@if(isset($areas) && $areas->isNotEmpty())
    <div class="w-full min-h-screen flex items-center justify-center bg-gray-50">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-6 gap-6 w-full px-2">
            @foreach($areas as $area)
                <div>
                    <a 
        href=""
        class="block text-center text-lg font-medium text-gray-800 hover:text-primary transition"
    >
                    <a 
                        href="{{ route('products.byArea', ['slug' => $area->slug]) }}"
                        class="group block rounded-lg shadow hover:shadow-lg transition overflow-hidden relative aspect-square"
                    >
                        {{-- Imagen de fondo --}}
                        <img
                            src="{{ Str::startsWith($area->image, ['http://','https://']) ? $area->image : asset('storage/'.$area->image) }}"
                            alt="{{ $area->name }}"
                            class="w-full h-full object-cover transition-transform group-hover:scale-110"
                        >

                        {{-- Overlay con nombre --}}
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                            <span class="text-xl font-semibold text-white text-center px-2">
                                {{ $area->name }}
                            </span>
                        </div>
                    </a>

                    {{-- Descripción corta --}}
                    @if(!empty($area->short_description))
                        <div class="mt-3 px-2 prose prose-sm prose-indigo mx-auto">
                            {!! $area->short_description !!}
                        </div>

                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif
