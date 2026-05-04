@props(['name','label'=>null,'options'=>[],'required'=>false])

@php
    $id = $attributes->get('id', $name);
    $hasError = $errors->has($name);
    $base = 'mt-2 block w-full h-14 rounded-2xl bg-white px-4 text-[15px] outline-none border';
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

    <select id="{{ $id }}" name="{{ $name }}" @if($required) required @endif
        {{ $attributes->merge(['class' => trim("$base $state")]) }}>
        @foreach($options as $value => $text)
            <option value="{{ $value }}" @selected(old($name)==(string)$value)>{{ $text }}</option>
        @endforeach
    </select>

    <x-form.error :name="$name" />
</div>
