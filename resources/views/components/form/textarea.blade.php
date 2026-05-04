@props(['name','label'=>null,'placeholder'=>null,'rows'=>4,'required'=>false])

@php
    $id = $attributes->get('id', $name);
    $hasError = $errors->has($name);
    $base = 'mt-2 block w-full rounded-2xl bg-white px-4 py-3 text-[15px] outline-none border';
    $state = $hasError
      ? 'border-red-500 focus:border-red-500 focus:ring-4 focus:ring-red-100'
      : 'border-cerulean-200 focus:border-cerulean-600 focus:ring-4 focus:ring-cerulean-100';
@endphp

<div>
    @if($label)
        <label for="{{ $id }}" class="block text-xs font-medium text-cerulean-800">
            {{ $label }} @if($required)<span class="text-red-600">*</span>@endif
        </label>
    @endif

    <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}"
              placeholder="{{ $placeholder }}"
              @if($required) required @endif
        {{ $attributes->merge(['class' => trim("$base $state")]) }}
  >{{ old($name) }}</textarea>

    <x-form.error :name="$name" />
</div>
