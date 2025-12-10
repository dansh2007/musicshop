<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-neutral-950 text-gray-100 antialiased font-[Manrope]">
        <div class="min-h-screen flex items-center justify-center px-4 py-12">
            <div class="w-full max-w-md bg-neutral-900 border border-white/10 rounded-3xl p-8 shadow-2xl space-y-6">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-white text-black flex items-center justify-center font-extrabold text-lg">JM</div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.35em] text-gray-400">jam music</p>
                        <p class="font-semibold">Вхід / Реєстрація</p>
                    </div>
                </div>
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
