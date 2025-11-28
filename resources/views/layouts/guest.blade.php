<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans text-gray-900 antialiased">
        <div
            class="relative flex min-h-screen flex-col items-center overflow-hidden bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 pt-6 sm:justify-center sm:pt-0">
            <!-- Efeito de partículas de fundo -->
            <div class="pointer-events-none absolute inset-0 overflow-hidden">
                <div class="absolute left-10 top-20 h-4 w-4 animate-pulse rounded-full bg-yellow-400 opacity-20"></div>
                <div class="absolute right-20 top-40 h-6 w-6 animate-pulse rounded-full bg-red-500 opacity-20"
                    style="animation-delay: 0.5s"></div>
                <div class="absolute bottom-40 left-1/4 h-3 w-3 animate-pulse rounded-full bg-blue-400 opacity-20"
                    style="animation-delay: 1s"></div>
                <div class="absolute bottom-20 right-1/3 h-5 w-5 animate-pulse rounded-full bg-green-400 opacity-20"
                    style="animation-delay: 1.5s"></div>
            </div>

            <div class="relative z-10">
                <a href="/" class="block transition-transform duration-300 hover:rotate-12 hover:scale-110">
                    <x-application-logo class="h-24 w-24" />
                </a>
                <h1
                    class="mt-4 bg-gradient-to-r from-yellow-400 via-red-500 to-pink-500 bg-clip-text text-center text-2xl font-bold text-transparent">
                    Pokédex Manager</h1>
            </div>

            <div
                class="relative z-10 mt-6 w-full overflow-hidden border border-slate-700 bg-slate-800/90 px-6 py-8 shadow-2xl backdrop-blur-sm sm:max-w-md sm:rounded-2xl">
                {{ $slot }}
            </div>

            <p class="relative z-10 mt-8 text-sm text-slate-500">Gotta Catch 'Em All!</p>
        </div>
    </body>

</html>
