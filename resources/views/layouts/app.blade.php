<!doctype html>
<html lang="fr" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title ?? 'NASS SPLIT' }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="h-full bg-cerulean-50 text-cerulean-900">
<x-navbar></x-navbar>
<div class="min-h-screen pb-20">
    {{-- Top bar --}}
    {{ $topbar ?? '' }}

    <div class="px-4 py-4">
        @yield('content')
    </div>
</div>

{{-- Bottom nav (mobile-first) --}}
{{ $bottomNav ?? '' }}
</body>
</html>
