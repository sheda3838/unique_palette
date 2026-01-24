<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Unique Palette') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600|noto-sans-sinhala:400,700|noto-sans-tamil:400,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}">

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-50 dark:bg-gray-900 dark:text-gray-100 min-h-screen flex flex-col">
    <!-- Art-Themed Loading Indicator (Requirement 1) -->
    <div wire:loading.fixed class="fixed top-0 left-0 w-full h-1.5 z-[100] pointer-events-none transition-opacity duration-300">
        <div class="brush-stroke-loader"></div>
    </div>

    <x-public-navigation />

    <main>
        {{ $slot }}
    </main>

    <x-footer />

    @livewireScripts
</body>

</html>