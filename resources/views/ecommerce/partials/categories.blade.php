{{-- resources/views/ecommerce/partials/categories.blade.php --}}
@if(isset($categories) && $categories->isNotEmpty())
    <div class="container mx-auto py-6">
        <h5 class="text-lg font-semibold mb-4 text-center">Categorías</h5>
        <div class="grid grid-cols-4 md:grid-cols-8 gap-4">
            @foreach($categories as $category)
                <div class="flex flex-col items-center">
                    <a href="{{ route('products.byCategory', $category) }}" class="group">
                        <div class="w-16 h-16 md:w-20 md:h-20 rounded-full overflow-hidden border-2 border-gray-200 group-hover:border-primary transition">
                            <img
                                src="{{ Str::startsWith($category->image, ['http://','https://']) ? $category->image : asset('storage/'.$category->image) }}"
                                alt="{{ $category->name }}"
                                class="w-full h-full object-cover"
                            >
                        </div>
                        <span class="mt-2 text-xs md:text-sm text-center text-gray-700 group-hover:text-primary transition">
                            {{ $category->name }}
                        </span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endif
