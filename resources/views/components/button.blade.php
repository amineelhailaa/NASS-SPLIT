@props(['variant' => 'primary','type'=>'submit','loading'=>false,'disabled'=>false])

@php
    $base = 'cursor-pointer inline-flex items-center justify-center gap-2 rounded-2xl font-semibold transition active:scale-[0.99]';
    $size = 'h-14 w-full px-2';

    $map = [
      'primary' => 'bg-cerulean-600 text-white hover:bg-cerulean-700',
      'secondary' => 'bg-transparent border border-cerulean-300 text-cerulean-800 hover:bg-cerulean-50',
      'ghost' => 'bg-transparent text-cerulean-800 hover:bg-cerulean-50',
      'danger' => 'bg-red-600 text-white hover:bg-red-700',
    ];
    $cls = $map[$variant] ?? $map['primary'];

    $isDisabled = $disabled || $loading;
@endphp

<button type="{{ $type }}" @if($isDisabled) disabled @endif
    {{ $attributes->merge(['class' => trim("$base $size $cls ".($isDisabled?'opacity-60 cursor-not-allowed':''))]) }}>
    @if($loading)
        <span class="h-4 w-4 rounded-full border-2 border-white/40 border-t-white animate-spin"></span>
    @endif
    {{ $slot }}
</button>
