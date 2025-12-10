<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="min-h-screen flex">
        <aside class="w-64 bg-[#0f172a] text-gray-100 flex flex-col">
            <div class="px-6 py-5 border-b border-white/10">
                <p class="text-lg font-semibold">Адмінпанель</p>
            </div>
            <nav class="flex-1 px-4 py-4 space-y-1 text-sm">
                @php
                    $navLink = function ($route, $label, $match) {
                        $active = request()->routeIs($match);
                        $classes = $active
                            ? 'bg-white/10 text-white'
                            : 'text-gray-200 hover:text-white hover:bg-white/5';
                        return "<a href=\"".route($route)."\" class=\"block px-3 py-2 rounded-md font-medium {$classes}\">{$label}</a>";
                    };
                @endphp
                {!! $navLink('admin.dashboard', 'Dashboard', 'admin.dashboard') !!}
                {!! $navLink('admin.instruments.index', 'Інструменти', 'admin.instruments.*') !!}
                {!! $navLink('admin.categories.index', 'Категорії', 'admin.categories.*') !!}
                {!! $navLink('admin.brands.index', 'Бренди', 'admin.brands.*') !!}
                {!! $navLink('admin.orders.index', 'Замовлення', 'admin.orders.*') !!}
            </nav>
            <div class="px-4 py-4 border-t border-white/10">
                <a href="{{ route('home') }}" class="block text-sm text-gray-200 hover:text-white mb-2">До сайту</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm text-red-300 hover:text-red-100">Вийти</button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="bg-white border-b border-gray-200 h-16 flex items-center px-6">
                <div>
                    <h1 class="text-lg font-semibold text-gray-900">@yield('title', 'Admin')</h1>
                    <p class="text-xs text-gray-500">Вітаємо, {{ auth()->user()->name }}</p>
                </div>
            </header>

            <main class="p-6 flex-1">
                @if (session('status'))
                    <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-800">
                        {{ session('status') }}
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
