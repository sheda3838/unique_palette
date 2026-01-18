<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        .animate-entrance {
            animation: entrance 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes entrance {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
</head>

<body class="bg-[#f8fafc]">
    <!-- Art-Themed Loading Indicator (Requirement 1) -->
    <div wire:loading.fixed class="fixed top-0 left-0 w-full h-1.5 z-[100] pointer-events-none transition-opacity duration-300">
        <div class="brush-stroke-loader"></div>
    </div>
    <div class="antialiased selection:bg-[#1ABC9C] selection:text-white">
        {{ $slot }}
    </div>

    @livewireScripts
</body>

</html>