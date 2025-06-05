<button
  type="{{ $type }}"
  {{ $attributes->merge([
      'class' => 'inline-flex items-center px-3 py-1 rounded bg-gray text-gray-700 hover:bg-gray-200',
  ]) }}
>
  @if ($icon)
    <i class="{{ $icon }} mr-1"></i>
  @endif

  {{ $slot }}
</button>