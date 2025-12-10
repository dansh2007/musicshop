<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-6">
        <div class="bg-neutral-900 border border-white/10 rounded-3xl p-8 shadow-2xl">
            <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Вітаємо</p>
            <h1 class="text-3xl font-bold text-white mt-2">{{ auth()->user()->name }}</h1>
            <p class="text-gray-300 mt-4">Ви успішно увійшли до акаунту. Перейдіть у каталог або керуйте інструментами, якщо ви адміністратор.</p>
            <div class="flex flex-wrap gap-3 mt-6">
                <a href="{{ route('catalog') }}" class="btn-primary">Перейти до каталогу</a>
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn-ghost">Адмін-панель</a>
                @else
                    <a href="{{ route('profile.edit') }}" class="btn-ghost">Редагувати профіль</a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
