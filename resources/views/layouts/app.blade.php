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
    @if (app()->environment('local'))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
    <link rel="stylesheet" href="{{ asset('build/assets/app-XJQ3DZ2G.css') }}">
    <script src="{{ asset('build/assets/app-XJQ3DZ2G.js') }}" defer></script>
    @endif
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gradient-to-br from-slate-700 via-slate-800 to-slate-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
        <header class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 shadow-lg">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endisset

        <!-- Page Content -->
        <main class="pb-8">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="border-t border-slate-600 bg-slate-800/50 backdrop-blur-sm">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-slate-300">Pokédx Manager - Gotta Catch 'Em All!</p>
            </div>
        </footer>
    </div>
</body>

</html>