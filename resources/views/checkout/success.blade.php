<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-6 text-center">
        <div class="bg-white border border-red-100 rounded-3xl p-10 shadow-lg space-y-4">
            <div class="text-4xl text-green-600">✅</div>
            <h1 class="text-3xl font-bold text-gray-900">Замовлення прийнято</h1>
            <p class="text-gray-600">Номер замовлення #{{ $order->id }}. Ми зв’яжемося з вами найближчим часом.</p>
            <div class="space-y-1 text-sm text-gray-700">
                <p>Статус: <span class="text-red-700 font-semibold">{{ strtoupper($order->status) }}</span></p>
                <p>Сума: <span class="text-red-700 font-semibold">₴{{ number_format($order->total, 0, ',', ' ') }}</span></p>
            </div>
            <div class="flex justify-center gap-3 pt-4 flex-wrap">
                <a href="{{ route('catalog') }}" class="btn-primary">Повернутися в каталог</a>
                <a href="{{ route('cart.index') }}" class="btn-ghost">Переглянути кошик</a>
            </div>
        </div>
    </div>
</x-app-layout>
