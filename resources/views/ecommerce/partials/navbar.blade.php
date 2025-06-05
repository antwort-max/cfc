{{-- resources/views/ecommerce/partials/navbar.blade.php --}}
@props(['menus'])

{{-- Alpine: se encarga de mostrar / ocultar el menú en móviles --}}
<nav x-data="{ open: false }"
     class="w-full bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-20">

    <div class="max-w-screen-xl mx-auto flex items-center justify-between gap-6 py-4 px-6">

        {{-- Logo --}}
        <a href="{{ url('/') }}" class="shrink-0 flex items-center">
            <img
                src="{{ asset('storage/' . ($themeOptions?->logo ?? 'fallback/logo.png')) }}"
                alt="Logo"
                class="h-12 w-auto"
            >
        </a>

        {{-- Botón hamburguesa (visible < sm) --}}
        <button
            @click="open = !open"
            class="sm:hidden p-2 rounded-md hover:bg-gray-100 focus:outline-none focus:ring"
        >
            <i :class="open ? 'ti ti-x' : 'ti ti-menu-2'" class="text-2xl"></i>
        </button>

        {{-- Menú horizontal (desktop) --}}
        <ul class="hidden sm:flex items-center gap-6">
            @foreach ($menus as $menu)
                <li class="relative group">
                    {{-- enlace raíz con tu componente --}}
                    <x-link-button-movile :href="$menu->url"
                                   :icon="$menu->icon"
                                   color="white"
                                   class="">
                        {{ $menu->title }}
                    </x-link-button-movile>

                    {{-- Sub-menú desplegable (desktop) --}}
                    @if ($menu->children->isNotEmpty())
                        <ul
                            class="absolute left-0 mt-3 w-40 bg-white rounded-md shadow-lg
                                   overflow-hidden opacity-0 group-hover:opacity-100
                                   scale-95 group-hover:scale-100 transform origin-top
                                   transition-all duration-150">
                            @foreach ($menu->children as $child)
                                <li>
                                    <x-link-button-movile :href="$child->url"
                                                   color="white"
                                                   class="">
                                        {{ $child->title }}
                                    </x-link-button-movile>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Menú vertical (mobile) --}}
    <div x-show="open"
         x-collapse
         class="sm:hidden border-t border-gray-200 bg-white">

        <ul class="flex flex-col py-2">
            @foreach ($menus as $menu)
                <li>
                    <x-link-button-movile :href="$menu->url"
                                   :icon="$menu->icon"
                                   color="white"
                                   class="">
                        {{ $menu->title }}
                    </x-link-button-movile>

                    {{-- hijos en móvil --}}
                    @if ($menu->children->isNotEmpty())
                        <ul class="pl-6">
                            @foreach ($menu->children as $child)
                                <li>
                                    <x-link-button-movile :href="$child->url"
                                                   color="white"
                                                   class="">
                                        {{ $child->title }}
                                    </x-link-button-movile>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</nav>
