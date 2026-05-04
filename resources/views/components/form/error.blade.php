@props(['name'])

@php $msg = $errors->first($name); @endphp

@if($msg)
    <p {{ $attributes->merge(['class' => 'mt-2 text-xs text-red-600']) }}>{{ $msg }}</p>
@endif
