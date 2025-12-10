<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Кошик</h1>
                <p class="text-gray-600 text-sm">Керуйте товарами перед оформленням замовлення.</p>
            </div>
            @if ($items->count())
                <form method="POST" action="{{ route('cart.clear') }}">
                    @csrf
                    <button class="btn-ghost text-sm">Очистити</button>
                </form>
            @endif
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-red-100 bg-white px-4 py-3 text-sm text-red-700 shadow-sm">
                {{ session('status') }}
            </div>
        @endif
        @error('cart')
            <div class="rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-700">{{ $message }}</div>
        @enderror

        @if ($items->isEmpty())
            <div class="bg-white border border-red-100 rounded-2xl p-8 text-center text-gray-600 shadow-sm">
                Кошик порожній. <a href="{{ route('catalog') }}" class="text-red-600 hover:text-red-700">Повернутися до каталогу</a>.
            </div>
        @else
            <div class="bg-white border border-red-100 rounded-2xl divide-y divide-red-50 shadow-sm">
                @foreach ($items as $item)
                    <div class="p-4 flex flex-wrap items-center gap-4">
                        <div class="flex-1 min-w-[200px]">
                            <p class="font-semibold text-gray-900">{{ $item['instrument']->title }}</p>
                            <p class="text-sm text-gray-600">{{ $item['instrument']->brand->name }}</p>
                        </div>
                        <div class="text-gray-900 font-semibold flex flex-col">
                            @if($item['instrument']->has_sale)
                                <span class="text-xs text-gray-400 line-through">₴{{ number_format($item['instrument']->price, 0, ',', ' ') }}</span>
                                <span>₴{{ number_format($item['price'], 0, ',', ' ') }}</span>
                            @else
                                <span>₴{{ number_format($item['price'], 0, ',', ' ') }}</span>
                            @endif
                        </div>
                        <form method="POST" action="{{ route('cart.update', $item['instrument']) }}" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <input type="number" name="quantity" min="1" max="10" value="{{ $item['quantity'] }}" class="w-20 input">
                            <button class="btn-ghost text-sm">Оновити</button>
                        </form>
                        <div class="text-gray-900 font-semibold">
                            ₴{{ number_format($item['price'] * $item['quantity'], 0, ',', ' ') }}
                        </div>
                        <form method="POST" action="{{ route('cart.destroy', $item['instrument']) }}">
                            @csrf
                            @method('DELETE')
                            <button class="text-sm text-red-600 hover:text-red-700">Видалити</button>
                        </form>
                    </div>
                @endforeach
            </div>

            <div class="flex flex-wrap items-center justify-between gap-4 bg-white border border-red-100 rounded-2xl p-6 shadow-sm">
                <div class="text-gray-600 text-sm">Загалом</div>
                <div class="text-3xl font-bold text-red-700">₴{{ number_format($total, 0, ',', ' ') }}</div>
                <div class="flex gap-3">
                    <a href="{{ route('catalog') }}" class="btn-ghost">Продовжити покупки</a>
                    <a href="{{ route('checkout.index') }}" class="btn-primary">Оформити</a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
