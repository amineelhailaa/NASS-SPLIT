@props(['name','label','required'=>false])

<div class="flex items-start gap-3">
    <input type="checkbox" name="{{ $name }}" value="1" @checked(old($name))
    @if($required) required @endif
           class="mt-1 rounded border-cerulean-200 text-cerulean-600 focus:ring-cerulean-100" />
    <div>
        <div class="text-sm text-cerulean-800">{{ $label }}</div>
        <x-form.error :name="$name" class="mt-1" />
    </div>
</div>
