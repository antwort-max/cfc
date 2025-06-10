@isset($banner)
<div class="container mx-auto mb-8">
    <section class="relative rounded-lg overflow-hidden shadow flex flex-col md:flex-row">
        {{-- Imagen (1/3 en escritorio) --}}
        <div class="relative w-full md:w-1/3 h-56 md:h-auto">
            <img
                src="{{ Str::startsWith($banner->image, ['http://', 'https://']) ? $banner->image : asset('storage/'.$banner->image) }}"
                alt="{{ $banner->name }}"
                class="w-full h-full object-cover"
            >
            <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                <h2 class="text-3xl md:text-4xl font-bold text-white text-center px-4">
                    {{ $banner->name }}
                </h2>
            </div>
        </div>

<div class="w-full md:w-2/3 p-6 flex items-center bg-white/90 backdrop-blur-sm">
    <article class="prose prose-sm md:prose text-gray-700">
        {!! $banner->description !!}
    </article>
</div>
    </section>
</div>
@endisset
