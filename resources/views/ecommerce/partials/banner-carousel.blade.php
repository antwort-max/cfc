@props([
    /** @var \Illuminate\Support\Collection<\App\Models\WebBanner> */
    'banners' => collect(),
    'interval' => 5000,   // ms entre slides
])

@if($banners->isNotEmpty())
<div
    x-data="{ active: 0, count: {{ $banners->count() }} }"
    x-init="setInterval(() => active = ++active % count, {{ $interval }})"
    class="relative w-full overflow-hidden rounded-2xl shadow-lg"
>
    {{-- Slides --}}
    <div class="flex transition-transform duration-700"
         :style="{ transform: `translateX(-${active * 100}%)` }">
        @foreach($banners as $banner)
            <a href="{{ $banner->link ?? '#' }}"
               class="w-full flex-shrink-0">
                <img
    src="{{ asset('storage/' . $banner->image) }}"
    alt="{{ $banner->title }}"
    class="w-full h-auto object-cover"    {{-- ← sin altura fija --}}
    loading="lazy"
/>
            </a>
        @endforeach
    </div>

    {{-- Indicadores --}}
    <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-2">
        @foreach($banners as $i => $banner)
            <button
                class="h-2.5 w-2.5 rounded-full bg-white/60 hover:bg-white"
                :class="{ 'bg-white': active === {{ $i }} }"
                @click="active = {{ $i }}"
            ></button>
        @endforeach
    </div>

    {{-- Flechas --}}
    <button @click="active = (active === 0) ? count - 1 : --active"
            class="absolute top-1/2 -translate-y-1/2 left-3
                   p-2 bg-white/70 rounded-full hover:bg-white">
        <svg class="w-4 h-4" fill="none" stroke="currentColor">
            <path d="M15 19l-7-7 7-7" stroke-width="2"
                  stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>

    <button @click="active = ++active % count"
            class="absolute top-1/2 -translate-y-1/2 right-3
                   p-2 bg-white/70 rounded-full hover:bg-white">
        <svg class="w-4 h-4" fill="none" stroke="currentColor">
            <path d="M9 5l7 7-7 7" stroke-width="2"
                  stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>
</div>
@endif
