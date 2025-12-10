@extends('layouts.admin')

@section('title', 'Дашборд')

@section('content')
    <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white shadow-sm rounded-lg p-4 border border-gray-200">
            <p class="text-sm text-gray-500">Інструментів</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['instruments'] }}</p>
        </div>
        <div class="bg-white shadow-sm rounded-lg p-4 border border-gray-200">
            <p class="text-sm text-gray-500">Категорій</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['categories'] }}</p>
        </div>
        <div class="bg-white shadow-sm rounded-lg p-4 border border-gray-200">
            <p class="text-sm text-gray-500">Брендів</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['brands'] }}</p>
        </div>
        <div class="bg-white shadow-sm rounded-lg p-4 border border-gray-200">
            <p class="text-sm text-gray-500">Користувачів</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['users'] }}</p>
        </div>
    </div>

    <div class="mt-8 bg-white border border-gray-200 shadow-sm rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Останні додані інструменти</h2>
            <a href="{{ route('admin.instruments.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded-md text-sm">+ Додати</a>
        </div>
        <div class="space-y-3">
            @forelse ($recentInstruments as $item)
                <div class="flex flex-wrap items-center gap-3 p-3 border border-gray-200 rounded-lg">
                    <div class="flex-1">
                        <p class="text-gray-900 font-semibold">{{ $item->title }}</p>
                        <p class="text-sm text-gray-600">{{ $item->brand->name }} • {{ $item->category->name }}</p>
                    </div>
                    <div class="text-gray-900 font-semibold">₴{{ number_format($item->price, 0, ',', ' ') }}</div>
                    <a href="{{ route('admin.instruments.edit', $item) }}" class="text-blue-600 hover:underline text-sm">Редагувати</a>
                </div>
            @empty
                <p class="text-gray-600">Поки немає інструментів.</p>
            @endforelse
        </div>
    </div>
@endsection
