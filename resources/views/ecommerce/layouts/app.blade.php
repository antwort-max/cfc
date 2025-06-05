<!DOCTYPE html>
<html lang="es" class="scroll-smooth antialiased">
<head>
    <!-- Evita parpadeos de AlpineJS -->
    <style>
      [x-cloak] { display: none !important; }
    </style>

    <!-- Meta básicos -->
    <meta charset="{{ config('meta.charset') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="{{ config('meta.viewport') }}">
    <meta name="description" content="{{ config('meta.description') }}">
    <meta name="author" content="{{ config('meta.author') }}">
    <meta name="robots" content="{{ config('meta.robots') }}">
    <link rel="canonical" href="{{ config('meta.canonical') }}">

    <!-- Keywords opcional -->
    <meta name="keywords" content="{{ config('meta.keywords') }}">

    <!-- Favicon y Apple Touch Icon -->
    <link rel="icon" href="{{ config('meta.favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ config('meta.favicon.ico') }}" type="image/x-icon">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ config('meta.favicon.png_32') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ config('meta.favicon.png_16') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ config('meta.apple_touch_icon') }}">
    <link rel="manifest" href="{{ config('meta.manifest') }}">

    <!-- Open Graph -->
    <meta property="og:locale"      content="{{ config('meta.og.locale') }}">
    <meta property="og:site_name"   content="{{ config('meta.og.site_name') }}">
    <meta property="og:title"       content="{{ config('meta.og.title') }}">
    <meta property="og:description" content="{{ config('meta.og.description') }}">
    <meta property="og:image"       content="{{ config('meta.og.image') }}">
    <meta property="og:url"         content="{{ config('meta.og.url') }}">
    <meta property="og:type"        content="{{ config('meta.og.type') }}">

    <!-- Twitter Cards -->
    <meta name="twitter:card"        content="{{ config('meta.twitter.card') }}">
    <meta name="twitter:site"        content="{{ config('meta.twitter.site') }}">
    <meta name="twitter:creator"     content="{{ config('meta.twitter.creator') }}">
    <meta name="twitter:title"       content="{{ config('meta.twitter.title') }}">
    <meta name="twitter:description" content="{{ config('meta.twitter.description') }}">
    <meta name="twitter:image"       content="{{ config('meta.twitter.image') }}">

    <!-- Otros meta de seguridad y PWA -->
    <meta name="theme-color"         content="{{ config('meta.theme_color') }}">
    <meta name="referrer"            content="{{ config('meta.referrer') }}">
    <meta name="format-detection"    content="{{ config('meta.format_detection') }}">
    <meta http-equiv="Content-Security-Policy"
          content="{{ config('meta.csp') }}">

    <!-- JSON-LD Schema.org -->
    <script type="application/ld+json">
        {!! json_encode(config('meta.json_ld'), JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) !!}
    </script>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@tabler/icons@latest/iconfont/tabler-icons.min.css"
    />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons@latest/iconfont/tabler-icons.min.css" />

    <!-- Estilos y scripts -->
    @filamentStyles            {{-- + Livewire, Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
    @livewireStyles
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col bg-gray-50 text-gray-900 font-sans">
    <main class="flex-grow">

        @if($topBanner['status'])
            <x-top-banner :banner="$topBanner" />
        @endif

        @include('ecommerce.partials.sale-banner', ['saleBanner' => $saleBanner])
     
        @yield('content')

     
    </main>

    @include('ecommerce.partials.footer-section', compact('footer_sections', 'social_links'))
    
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @filamentScripts
    @livewireScripts
    @stack('scripts')
</body>
</html>
