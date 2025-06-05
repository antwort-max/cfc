@extends('ecommerce.layouts.app')

@section('content')
    {{-- Navbar sobre el carrusel --}}
    @include('ecommerce.partials.navbar', ['menus' => $menus])

    {{-- Carrusel de banners --}}
    @include('ecommerce.partials.banner-carousel', ['banners' => $banners])

    <div class="flex items-center justify-center py-32">
        <h1 class="text-4xl font-bold text-primary">LandingPage</h1>
    </div>
@endsection