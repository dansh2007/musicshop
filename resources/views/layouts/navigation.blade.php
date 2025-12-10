@php
    $cartCount = collect(session('cart', []))->sum('quantity');
    $favoriteCount = auth()->check() ? \App\Models\Favorite::where('user_id', auth()->id())->count() : 0;
@endphp

<nav x-data="{ open: false }" class="fixed top-0 inset-x-0 z-50 bg-white/90 backdrop-blur border-b border-gray-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="flex items-center gap-3 text-red-700">
                <div class="h-10 w-10 rounded-full bg-red-600 text-white flex items-center justify-center font-extrabold text-lg">JM</div>
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-gray-500">Jam Music</p>
                    <p class="font-semibold text-gray-900">Інструменти</p>
                </div>
            </a>

            <div class="hidden md:flex items-center gap-8 text-sm font-semibold">
                <a href="{{ route('catalog') }}" class="nav-link {{ request()->routeIs('catalog') || request()->routeIs('home') ? 'active' : '' }}">Каталог</a>
                @auth
                    <a href="{{ route('favorites.index') }}" class="nav-link {{ request()->routeIs('favorites.*') ? 'active' : '' }}">
                        Обране @if($favoriteCount) ({{ $favoriteCount }}) @endif
                    </a>
                @endauth
                <a href="{{ route('cart.index') }}" class="nav-link">Кошик @if($cartCount) ({{ $cartCount }}) @endif</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Кабінет</a>
                @endauth
            </div>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('favorites.index') }}" class="icon-btn relative" title="Обране">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m12 21-1.45-1.32C6 15.36 3 12.28 3 8.5 3 5.42 5.42 3 8.5 3A5.5 5.5 0 0 1 12 4.94 5.5 5.5 0 0 1 15.5 3 5.5 5.5 0 0 1 21 8.5c0 3.78-3 6.86-7.55 11.18L12 21Z" />
                        </svg>
                        @if ($favoriteCount)
                            <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-semibold rounded-full px-1.5 py-0.5">{{ $favoriteCount }}</span>
                        @endif
                    </a>
                @endauth
                <a href="{{ route('cart.index') }}" class="icon-btn relative" title="Кошик">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0 3 3h4.836a3 3 0 0 0 2.898-2.184l1.263-4.736a1.125 1.125 0 0 0-1.09-1.406H6.516" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm9 0a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" />
                    </svg>
                    @if ($cartCount)
                        <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-semibold rounded-full px-1.5 py-0.5">{{ $cartCount }}</span>
                    @endif
                </a>
                @guest
                    <a href="{{ route('login') }}" class="btn-ghost text-sm">Увійти</a>
                    <a href="{{ route('register') }}" class="btn-primary text-sm">Реєстрація</a>
                @else
                    <a href="{{ route(auth()->user()->isAdmin() ? 'admin.dashboard' : 'profile.edit') }}" class="btn-primary text-sm">Кабінет</a>
                @endguest
                <button @click="open = ! open" class="md:hidden icon-btn">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <div x-show="open" x-cloak class="md:hidden border-t border-gray-200 py-4 space-y-3">
            <a href="{{ route('catalog') }}" class="nav-link block">Каталог</a>
            @auth
                <a href="{{ route('favorites.index') }}" class="nav-link block">Обране @if($favoriteCount) ({{ $favoriteCount }}) @endif</a>
            @endauth
            <a href="{{ route('cart.index') }}" class="nav-link block">Кошик @if($cartCount) ({{ $cartCount }}) @endif</a>
            @auth
                <a href="{{ route('dashboard') }}" class="nav-link block">Кабінет</a>
                <form method="POST" action="{{ route('logout') }}" class="pt-2">
                    @csrf
                    <button class="nav-link block text-left w-full">Вийти</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-link block">Увійти</a>
                <a href="{{ route('register') }}" class="nav-link block">Реєстрація</a>
            @endauth
        </div>
    </div>
</nav>
