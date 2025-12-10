<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Оформлення замовлення</h1>
            <p class="text-gray-600 text-sm">Вкажіть контакти і ми зв’яжемося з вами.</p>
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white border border-red-100 rounded-2xl p-6 space-y-4 shadow-sm">
                <form method="POST" action="{{ route('checkout.store') }}" class="space-y-4">
                    @csrf
                    <div class="grid md:grid-cols-2 gap-4">
                        <label class="space-y-2">
                            <span class="text-sm text-gray-700">Ім’я та прізвище</span>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" class="input" required>
                            @error('name') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
                        </label>
                        <label class="space-y-2">
                            <span class="text-sm text-gray-700">Email</span>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" class="input" required>
                            @error('email') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
                        </label>
                    </div>
                    <div class="grid md:grid-cols-2 gap-4">
                        <label class="space-y-2">
                            <span class="text-sm text-gray-700">Телефон</span>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="input">
                            @error('phone') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
                        </label>
                        <label class="space-y-2">
                            <span class="text-sm text-gray-700">Місто</span>
                            <input type="text" name="city" value="{{ old('city') }}" class="input">
                            @error('city') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
                        </label>
                    </div>
                    <label class="space-y-2">
                        <span class="text-sm text-gray-700">Адреса</span>
                        <input type="text" name="address" value="{{ old('address') }}" class="input">
                        @error('address') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
                    </label>
                    <label class="space-y-2">
                        <span class="text-sm text-gray-700">Коментар</span>
                        <textarea name="notes" rows="3" class="input" placeholder="Бажаний час дзвінка або уточнення">{{ old('notes') }}</textarea>
                        @error('notes') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
                    </label>

                    <div class="flex flex-wrap gap-3">
                        <button class="btn-primary">Підтвердити замовлення</button>
                        <a href="{{ route('cart.index') }}" class="btn-ghost">Назад до кошика</a>
                    </div>
                </form>
            </div>

            <div class="bg-white border border-red-100 rounded-2xl p-6 space-y-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Замовлення</h3>
                    <span class="text-sm text-gray-600">{{ $items->count() }} товар(и)</span>
                </div>
                <div class="divide-y divide-red-50">
                    @foreach ($items as $item)
                        <div class="py-3 flex items-start justify-between gap-3">
                            <div>
                                <p class="text-gray-900 font-semibold">{{ $item['instrument']->title }}</p>
                                <p class="text-sm text-gray-600">x{{ $item['quantity'] }}</p>
                            </div>
                            <div class="text-red-700 font-semibold">
                                ₴{{ number_format($item['price'] * $item['quantity'], 0, ',', ' ') }}
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-red-100">
                    <span class="text-gray-700">Разом</span>
                    <span class="text-2xl font-bold text-red-700">₴{{ number_format($total, 0, ',', ' ') }}</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
