@extends('layouts.admin')

@section('title', 'Замовлення #'.$order->id)

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">Замовлення #{{ $order->id }}</h2>
            <p class="text-sm text-gray-600">Клієнт: {{ $order->name }} ({{ $order->email }})</p>
        </div>
        <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="flex items-center gap-2">
            @csrf
            @method('PUT')
            <select name="status" class="rounded-md border-gray-300">
                @foreach (['new' => 'new', 'paid' => 'paid', 'shipped' => 'shipped', 'canceled' => 'canceled'] as $value => $label)
                    <option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <button class="px-3 py-2 bg-blue-600 text-white rounded-md text-sm">Оновити</button>
        </form>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-5 space-y-2">
            <h3 class="font-semibold text-gray-900">Контакти</h3>
            <p class="text-gray-700"><span class="text-gray-500 text-sm">Email:</span> {{ $order->email }}</p>
            @if($order->phone)<p class="text-gray-700"><span class="text-gray-500 text-sm">Телефон:</span> {{ $order->phone }}</p>@endif
            @if($order->city)<p class="text-gray-700"><span class="text-gray-500 text-sm">Місто:</span> {{ $order->city }}</p>@endif
            @if($order->address)<p class="text-gray-700"><span class="text-gray-500 text-sm">Адреса:</span> {{ $order->address }}</p>@endif
            @if($order->notes)<p class="text-gray-700"><span class="text-gray-500 text-sm">Коментар:</span> {{ $order->notes }}</p>@endif
            <p class="text-gray-700"><span class="text-gray-500 text-sm">Користувач:</span> {{ $order->user?->email ?? 'гість' }}</p>
        </div>
        <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-5 space-y-2">
            <h3 class="font-semibold text-gray-900">Підсумок</h3>
            <p class="text-gray-700"><span class="text-gray-500 text-sm">Статус:</span> {{ strtoupper($order->status) }}</p>
            <p class="text-gray-700"><span class="text-gray-500 text-sm">Сума:</span> ₴{{ number_format($order->total, 0, ',', ' ') }}</p>
            <p class="text-gray-700"><span class="text-gray-500 text-sm">Створено:</span> {{ $order->created_at->format('d.m.Y H:i') }}</p>
        </div>
    </div>

    <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 mt-6 overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase tracking-wide">
                <tr>
                    <th class="px-4 py-3 text-left">Товар</th>
                    <th class="px-4 py-3 text-left">Кількість</th>
                    <th class="px-4 py-3 text-left">Ціна</th>
                    <th class="px-4 py-3 text-left">Сума</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($order->items as $item)
                    <tr>
                        <td class="px-4 py-3 text-gray-800">{{ $item->instrument->title }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $item->quantity }}</td>
                        <td class="px-4 py-3 text-gray-800">₴{{ number_format($item->price, 0, ',', ' ') }}</td>
                        <td class="px-4 py-3 text-gray-800">₴{{ number_format($item->price * $item->quantity, 0, ',', ' ') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
