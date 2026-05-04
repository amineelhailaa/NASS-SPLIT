@props([
  'name' => 'password',
  'label' => 'Mot de passe',
  'placeholder' => '••••••••',
  'autocomplete' => 'current-password',
  'required' => true,
])

@php
    $id = $attributes->get('id', $name);
    $hasError = $errors->has($name);

    $base = 'mt-2 block w-full h-14 rounded-2xl bg-white px-4 pr-12 text-[15px] outline-none border';
    $state = $hasError
      ? 'border-red-500 focus:border-red-500 focus:ring-4 focus:ring-red-100'
      : 'border-cerulean-200 focus:border-cerulean-600 focus:ring-4 focus:ring-cerulean-100';
@endphp

<div>
    <label for="{{ $id }}" class="block text-xs font-medium text-cerulean-800">
        {{ $label }} @if($required)<span class="text-red-600">*</span>@endif
    </label>

    <div class="relative">
        <input
            id="{{ $id }}"
            name="{{ $name }}"
            type="password"
            placeholder="{{ $placeholder }}"
            autocomplete="{{ $autocomplete }}"
            @if($required) required @endif
            {{ $attributes->merge(['class' => trim("$base $state")]) }}
        />

        <button type="button"
                class="absolute inset-y-0 right-0 px-4 text-sm text-cerulean-800"
                data-toggle-password="#{{ $id }}"
                aria-label="Afficher/Masquer">
            👁
        </button>
    </div>

    <x-form.error :name="$name" />
</div>
