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

    $gallery = collect([$imageUrl($instrument->main_image)])
        ->merge($instrument->images->pluck('image_path')->map(fn ($p) => $imageUrl($p)))
        ->values();
    $favoriteIds = auth()->check()
        ? \App\Models\Favorite::where('user_id', auth()->id())->pluck('instrument_id')->toArray()
        : [];
    $isFav = in_array($instrument->id, $favoriteIds);
@endphp

<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-10">
        <div class="flex items-center gap-3 text-sm text-gray-500">
            <a href="{{ route('catalog') }}" class="hover:text-red-600">Каталог</a>
            <span>/</span>
            <span class="text-gray-900">{{ $instrument->title }}</span>
        </div>

        <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-start">
            <div class="space-y-4" x-data="{
                images: {{ $gallery->toJson() }},
                active: 0,
                next() { this.active = (this.active + 1) % this.images.length },
                prev() { this.active = (this.active - 1 + this.images.length) % this.images.length }
            }">
                <div class="bg-white border border-red-200 rounded-3xl overflow-hidden shadow-xl relative">
                    <div class="w-full aspect-[4/3] lg:aspect-video bg-white flex items-center justify-center">
                        <img :src="images[active]" alt="{{ $instrument->title }}" class="w-full h-full object-contain">
                    </div>
                    <button @click="prev()" class="absolute left-3 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-red-600 rounded-full h-10 w-10 flex items-center justify-center shadow">
                        ‹
                    </button>
                    <button @click="next()" class="absolute right-3 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-red-600 rounded-full h-10 w-10 flex items-center justify-center shadow">
                        ›
                    </button>
                </div>
                <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                    <template x-for="(img, idx) in images" :key="idx">
                        <button @click="active = idx" class="border rounded-xl overflow-hidden hover:border-red-400" :class="active === idx ? 'border-red-500 ring-2 ring-red-100' : 'border-gray-200'">
                            <img :src="img" alt="thumb" class="w-full h-20 object-cover">
                        </button>
                    </template>
                </div>
            </div>

            <div class="space-y-6">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="badge bg-red-600 text-white border-red-600">{{ $instrument->brand->name }}</span>
                    <span class="badge badge-outline text-red-700 border-red-200">{{ $instrument->category->name }}</span>
                </div>
                <div class="space-y-2">
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900">{{ $instrument->title }}</h1>
                    <div class="flex items-baseline gap-3">
                        @if($instrument->has_sale)
                            <span class="text-xl text-gray-400 line-through">₴{{ number_format($instrument->price, 0, ',', ' ') }}</span>
                            <span class="text-3xl font-bold text-red-700">₴{{ number_format($instrument->effective_price, 0, ',', ' ') }}</span>
                        @else
                            <span class="text-3xl font-bold text-red-700">₴{{ number_format($instrument->price, 0, ',', ' ') }}</span>
                        @endif
                    </div>
                </div>
                <p class="text-gray-700 leading-relaxed">{{ $instrument->full_description }}</p>

                <div class="flex flex-wrap items-center gap-3">
                    <form method="POST" action="{{ route('cart.store', $instrument) }}">
                        @csrf
                        <button type="submit" class="btn-primary">Додати в кошик</button>
                    </form>
                    <a href="{{ route('catalog') }}" class="btn-ghost">Повернутись до каталогу</a>
                    @auth
                        @if($isFav)
                            <form method="POST" action="{{ route('favorites.destroy', $instrument) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn-ghost">В обраному</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('favorites.store', $instrument) }}">
                                @csrf
                                <button class="btn-ghost">В обране</button>
                            </form>
                        @endif
                    @endauth
                </div>

                @if ($instrument->specs)
                    <div class="bg-white border border-red-100 rounded-2xl p-5 space-y-3 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900">Характеристики</h3>
                        <ul class="grid sm:grid-cols-2 gap-2 text-gray-700">
                            @foreach ($instrument->specs as $spec)
                                <li class="flex items-start gap-2">
                                    <span class="mt-1 h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                    <span>{{ $spec }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('catalog', ['brand' => $instrument->brand->slug]) }}" class="btn-primary">Ще від {{ $instrument->brand->name }}</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
