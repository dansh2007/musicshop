@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $imageUrl = static function (?string $path): string {
        if (!$path) {
            return 'https://images.unsplash.com/photo-1483412033650-1015ddeb83d1?auto=format&fit=crop&w=1400&q=80';
        }

        return Str::startsWith($path, ['http://', 'https://'])
            ? $path
            : Storage::url($path);
    };
@endphp

<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">
        <div class="relative overflow-hidden bg-gradient-to-r from-white via-red-50 to-white border border-red-100 rounded-3xl p-10 shadow-sm">
            <div class="absolute inset-0 pointer-events-none opacity-70" style="background-image: radial-gradient(circle at 10% 20%, rgba(220,38,38,0.08) 0, transparent 25%), radial-gradient(circle at 80% 10%, rgba(248,113,113,0.12) 0, transparent 20%), radial-gradient(circle at 60% 80%, rgba(248,113,113,0.12) 0, transparent 22%);"></div>
            <div class="relative grid lg:grid-cols-2 gap-8 items-center">
                <div class="space-y-4">
                    <p class="text-xs uppercase tracking-[0.3em] text-gray-500">Jam Music</p>
                    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight">Обирайте свій звук</h1>
                    <p class="text-gray-600 text-lg">Гітари, клавіші, студійне та DJ-обладнання. Велике фото, чітка ціна, зрозумілі фільтри.</p>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="#catalog" class="btn-primary">До каталогу</a>
                        <a href="#filters" class="btn-ghost">Фільтри</a>
                    </div>
                </div>
                <div class="hidden lg:block">
                    <div class="rounded-2xl border border-red-100 bg-white/70 p-4 shadow-inner">
                        <div class="grid grid-cols-3 gap-3 text-center text-sm font-semibold text-gray-700">
                            <div class="p-3 bg-red-50 rounded-xl">Електрогітари</div>
                            <div class="p-3 bg-red-50 rounded-xl">Клавішні</div>
                            <div class="p-3 bg-red-50 rounded-xl">DJ / Студія</div>
                            <div class="p-3 bg-red-50 rounded-xl">Акустичні</div>
                            <div class="p-3 bg-red-50 rounded-xl">Ударні</div>
                            <div class="p-3 bg-red-50 rounded-xl">Мікрофони</div>
                        </div>
                        <p class="mt-3 text-xs text-gray-500">Каталог створений за стилем jam.ua — мінімалізм і чіткий акцент на товарі.</p>
                    </div>
                </div>
            </div>
        </div>

        <div id="filters" class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
            <form method="GET" action="{{ route('catalog') }}" class="lg:col-span-1 bg-white border border-red-100 rounded-3xl p-6 shadow-sm space-y-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Фільтри</h2>
                    <a href="{{ route('catalog') }}" class="text-xs text-red-600 hover:text-red-700">Скинути</a>
                </div>
                <label class="space-y-2 block">
                    <span class="text-sm text-gray-700">Категорія</span>
                    <select name="category" class="input">
                        <option value="">Всі категорії</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->slug }}" @selected($filters['category'] === $category->slug)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="space-y-2 block">
                    <span class="text-sm text-gray-700">Бренд</span>
                    <select name="brand" class="input">
                        <option value="">Всі бренди</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->slug }}" @selected($filters['brand'] === $brand->slug)>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="space-y-2 block">
                        <span class="text-sm text-gray-700">Ціна від</span>
                        <input type="number" name="price_from" step="0.01" value="{{ $filters['price_from'] }}" class="input" placeholder="0">
                    </label>
                    <label class="space-y-2 block">
                        <span class="text-sm text-gray-700">Ціна до</span>
                        <input type="number" name="price_to" step="0.01" value="{{ $filters['price_to'] }}" class="input" placeholder="50000">
                    </label>
                </div>
                <label class="space-y-2 block">
                    <span class="text-sm text-gray-700">Пошук</span>
                    <input type="text" name="q" value="{{ $filters['q'] }}" class="input" placeholder="Назва або опис">
                </label>
                <div class="flex flex-col gap-3">
                    <button type="submit" class="btn-primary w-full">Застосувати</button>
                    <a href="{{ route('catalog') }}" class="btn-ghost w-full text-center">Очистити</a>
                </div>
            </form>

            <div id="catalog" class="lg:col-span-3 space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-semibold text-gray-900">Каталог</h2>
                    <p class="text-sm text-gray-500">Знайдено: {{ $instruments->total() }}</p>
                </div>

                <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                    @forelse ($instruments as $instrument)
                        <div class="group bg-white border border-red-100 rounded-3xl shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md flex flex-col h-full">
                            <div class="relative overflow-hidden rounded-3xl rounded-b-none">
                                <div class="aspect-[4/3] bg-gray-50 flex items-center justify-center">
                                    <img src="{{ $imageUrl($instrument->main_image) }}" alt="{{ $instrument->title }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                                </div>
                                <div class="absolute top-4 left-4 flex items-center gap-2">
                                    <span class="badge bg-red-600 text-white border-red-600">{{ $instrument->brand->name }}</span>
                                    <span class="badge badge-outline">{{ $instrument->category->name }}</span>
                                </div>
                            </div>

                            <div class="p-5 flex-1 flex flex-col">
                                <div class="space-y-2">
                                    <h3 class="text-xl font-semibold text-gray-900 leading-snug">{{ $instrument->title }}</h3>
                                    <p class="text-sm text-gray-600 leading-relaxed line-clamp-2">{{ $instrument->short_description }}</p>
                                </div>

                                <div class="mt-auto pt-4 flex items-center gap-3">
                                    <div class="flex flex-col whitespace-nowrap flex-shrink-0">
                                        @if($instrument->has_sale)
                                            <span class="text-sm text-gray-400 line-through">₴{{ number_format($instrument->price, 0, ',', ' ') }}</span>
                                            <span class="text-2xl font-bold text-red-700">₴{{ number_format($instrument->effective_price, 0, ',', ' ') }}</span>
                                        @else
                                            <span class="text-2xl font-bold text-red-700">₴{{ number_format($instrument->price, 0, ',', ' ') }}</span>
                                        @endif
                                    </div>
                                    <div class="flex gap-2 ml-auto flex-wrap justify-end items-center">
                                        @auth
                                            @php $isFav = in_array($instrument->id, $favoriteIds ?? []); @endphp
                                            @if($isFav)
                                                <form method="POST" action="{{ route('favorites.destroy', $instrument) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-ghost">В обраному</button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('favorites.store', $instrument) }}">
                                                    @csrf
                                                    <button type="submit" class="btn-ghost">В обране</button>
                                                </form>
                                            @endif
                                        @endauth
                                        <a href="{{ route('instruments.show', $instrument) }}" class="btn-ghost">Деталі</a>
                                        <form method="POST" action="{{ route('cart.store', $instrument) }}">
                                            @csrf
                                            <button class="btn-primary">В кошик</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 bg-white border border-red-100 rounded-2xl p-8 text-center text-gray-600">
                            Жодного інструменту не знайдено. Спробуйте змінити фільтри.
                        </div>
                    @endforelse
                </div>

                <div>
                    {{ $instruments->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
