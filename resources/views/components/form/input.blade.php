@props([
  'name', 'label' => null, 'type' => 'text', 'placeholder' => null,
  'value' => null, 'autocomplete' => null, 'required' => false, 'disabled' => false,
])

@php
    $id = $attributes->get('id', $name);
    $hasError = $errors->has($name);

    $base = 'mt-2 block w-full h-10 rounded-2xl bg-white px-4 text-[15px] outline-none border';
    $state = $hasError
      ? 'border-red-500 focus:border-red-500 focus:ring-4 focus:ring-red-100'
      : 'border-cerulean-200 focus:border-cerulean-600 focus:ring-4 focus:ring-cerulean-100';
    $disabledCls = $disabled ? 'opacity-60 cursor-not-allowed bg-cerulean-50' : '';
@endphp

<div>
    @if($label)
        <label for="{{ $id }}" class="block text-xs font-medium text-cerulean-800">
            {{ $label }} @if($required)<span class="text-red-600">*</span>@endif
        </label>
    @endif

    <input
        id="{{ $id }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        @if($required) required @endif
        @if($disabled) disabled @endif
        {{ $attributes->merge(['class' => trim("$base $state $disabledCls")]) }}
    />

    <x-form.error :name="$name" />
</div>
