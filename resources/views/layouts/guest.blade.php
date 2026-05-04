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
<main class="min-h-screen flex items-center justify-center px-5 py-10">
        @yield('content')
</main>
</body>
</html>
