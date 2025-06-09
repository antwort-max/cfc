@extends('ecommerce.layouts.app')

@section('content')
    {{-- Navbar sobre el carrusel --}}
    @include('ecommerce.partials.navbar', ['menus' => $menus])

    {{-- Carrusel de banners --}}
    @include('ecommerce.partials.banner-carousel', ['banners' => $banners])

    {{-- Carrusel de banners --}}
    @include('ecommerce.partials.areas', ['areas' => $areas])

@endsection