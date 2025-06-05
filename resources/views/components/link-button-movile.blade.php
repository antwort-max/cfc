@props(['href' => '#', 'icon' => null, 'color' => 'gray'])
@php
    $classes = "inline-flex items-center px-3 py-1 rounded bg-{$color} text-{$color}-700 hover:bg-{$color}-200";
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon)
        <i class="{{ $icon }} mr-1"></i>
    @endif
    <span class="">
        {{ $slot }}
    </span>
</a>

