@props(['title', 'action' => null])

<div class="flex items-center justify-between">
    <h1 class="text-xl font-semibold tracking-tight">{{ $title }}</h1>
    @if($action)
        <div>{{ $action }}</div>
    @endif
</div>
