@props(['href' => '#'])

<a href="{{ $href }}"
    {{ $attributes->merge(['class' => 'font-medium text-cerulean-600 hover:text-cerulean-700']) }}>
    {{ $slot }}
</a>
