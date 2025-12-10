@php use Illuminate\Support\Facades\Storage; use Illuminate\Support\Str; @endphp

<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Обране</h1>
                <p class="text-gray-600 text-sm">Ваші збережені інструменти.</p>
            </div>
        </div>

        @if(session('status'))
            <div class="rounded-xl border border-red-100 bg-white px-4 py-3 text-sm text-red-700 shadow-sm">
                {{ session('status') }}
            </div>
        @endif

        @if($favorites->isEmpty())
            <div class="bg-white border border-red-100 rounded-2xl p-8 text-center text-gray-600 shadow-sm">
                Поки немає обраних. <a href="{{ route('catalog') }}" class="text-red-600 hover:text-red-700">Перейти до каталогу</a>.
            </div>
        @else
            <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                @foreach($favorites as $instrument)
                    <div class="group bg-white border border-red-100 rounded-3xl shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md h-full flex flex-col">
                        <div class="relative overflow-hidden rounded-3xl rounded-b-none">
                            <div class="aspect-[4/3] bg-gray-50 flex items-center justify-center">
                                @php
                                    $imageUrl = $instrument->main_image
                                        ? (Str::startsWith($instrument->main_image, ['http://', 'https://'])
                                            ? $instrument->main_image
                                            : Storage::url($instrument->main_image))
                                        : null;
                                @endphp
                                @if($imageUrl)
                                    <img src="{{ $imageUrl }}" alt="{{ $instrument->title }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                                @else
                                    <div class="text-sm text-gray-400">Без фото</div>
                                @endif
                            </div>
                        </div>
                        <div class="p-5 flex-1 flex flex-col space-y-2">
                            <h3 class="text-xl font-semibold text-gray-900 leading-snug">{{ $instrument->title }}</h3>
                            <p class="text-sm text-gray-600 leading-relaxed line-clamp-2">{{ $instrument->short_description }}</p>
                            <div class="mt-auto pt-4 flex items-center gap-3">
                                <div class="flex flex-col whitespace-nowrap flex-shrink-0">
                                    @if($instrument->has_sale)
                                        <span class="text-sm text-gray-400 line-through">₴{{ number_format($instrument->price, 0, ',', ' ') }}</span>
                                        <span class="text-2xl font-bold text-red-700">₴{{ number_format($instrument->effective_price, 0, ',', ' ') }}</span>
                                    @else
                                        <span class="text-2xl font-bold text-red-700">₴{{ number_format($instrument->price, 0, ',', ' ') }}</span>
                                    @endif
                                </div>
                                <div class="flex gap-2 ml-auto flex-wrap justify-end">
                                    <a href="{{ route('instruments.show', $instrument) }}" class="btn-ghost">Деталі</a>
                                    <form method="POST" action="{{ route('favorites.destroy', $instrument) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-primary">Прибрати</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
