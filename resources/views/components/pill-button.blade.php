
<button
    type="{{ $type ?: 'submit' }}"
    @if($disabled) disabled @endif
    {{ $attributes->merge([
        'class' => '
            inline-flex items-center justify-center gap-1
            h-8 px-4 rounded-full transition
            bg-primary text-gray-500 text-xs font-semibold
            shadow-md shadow-primary/40
            hover:bg-primary/90 hover:shadow-lg hover:shadow-primary/50
            focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-primary
            disabled:bg-primary/40 disabled:cursor-not-allowed'
    ]) }}
>
    <i class="fa-solid {{ $icon }} " aria-hidden="true"></i>
    <span>{{ $label ?? $slot }}</span>
</button>
