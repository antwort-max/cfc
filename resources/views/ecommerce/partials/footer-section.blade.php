@php
    // Total de columnas: se suman las secciones + 1 para la columna de cliente
    $columns = count($footer_sections) + 1;
@endphp

<footer class="bg-black text-white py-8 mt-4">
    <div class="max-w-7xl mx-auto px-4">
        {{-- Grid responsivo: secciones + columna de cliente --}}
        <div
            @class([
                'grid gap-6 mb-8',
                'grid-cols-1',
                'md:grid-cols-2' => $columns === 2,
                'md:grid-cols-3' => $columns === 3,
                'md:grid-cols-4' => $columns === 4,
                'md:grid-cols-5' => $columns === 5,
                // añade más variantes si esperas más columnas
            ])
        >
            {{-- Secciones dinámicas --}}
            @foreach ($footer_sections as $section)
                @if ($section->status)
                    <div>
                        <h5 class="uppercase font-semibold mb-2">
                            {{ $section->title }}
                        </h5>
                        <div class="prose prose-sm text-white">
                            {!! $section->content !!}
                        </div>
                    </div>
                @endif
            @endforeach

            {{-- Columna para el cliente logeado --}}
            <div>
                @auth('customer')
                    <h5 class="uppercase font-semibold mb-2">Bienvenido</h5>
                    <p class="prose prose-sm text-white">
                        {{ auth('customer')->user()->first_name . " " .auth('customer')->user()->last_name }}
                    </p>
                @endauth
            </div>
        </div>

        <hr class="border-gray-700 mb-6">

        {{-- Redes sociales y copyright --}}
        <div class="flex flex-col md:flex-row justify-between items-center">
            {{-- Social links --}}
            <div class="flex items-center mb-4 md:mb-0">
                <span class="mr-2">Síguenos en:</span>
                @foreach ($social_links as $link)
                    @if ($link->status)
                        <a
                            href="{{ $link->url }}"
                            target="_blank"
                            class="flex items-center mx-1 hover:underline"
                        >
                            <i class="{{ $link->icon }} mr-1 inline-block"></i>
                            <span>{{ $link->platform }}</span>
                        </a>
                    @endif
                @endforeach
            </div>

            {{-- Copyright --}}
            <div class="text-sm">
                &copy; {{ date('Y') }} Mi Ecommerce. Todos los derechos reservados.
            </div>
        </div>
    </div>
</footer>

