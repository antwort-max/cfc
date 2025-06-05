@props(['banner'])

<div
    x-data="{ open: true }"
    x-show="open"
    x-cloak
    x-transition.opacity.duration.300ms
    {{-- Tailwind no compila clases dinámicas con {{ }}.
         Concatenamos la clase en Alpine (o safelist en tailwind.config.js). --}}
    :class="'fixed top-0 inset-x-0 z-50 flex items-center justify-between px-4 py-2 text-white ' + '{{ $banner['bg_color'] }}'"
>
    <span class="flex-1 text-center font-bold text-sm">
        {{ $banner['text'] }}
    </span>

    <button @click="open = false" class="hover:text-gray-300 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

