@props(['label' => '','href' => null])

@php
    $base = 'h-10 w-10 rounded-2xl border border-cerulean-200 bg-white hover:bg-cerulean-50 inline-flex items-center justify-center';
@endphp

@if($href)
    <a href="{{ $href }}" class="{{ $base }}" aria-label="{{ $label }}">{{ $slot }}</a>
@else
    <button type="button" class="{{ $base }}" aria-label="{{ $label }}">{{ $slot }}</button>
@endif
