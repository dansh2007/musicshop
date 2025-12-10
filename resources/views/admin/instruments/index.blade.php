@extends('layouts.admin')

@section('title', 'Інструменти')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-900">Каталог інструментів</h2>
        <a href="{{ route('admin.instruments.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded-md text-sm">+ Додати інструмент</a>
    </div>

    <div class="overflow-x-auto bg-white border border-gray-200 shadow-sm rounded-lg">
        <table class="min-w-full text-sm text-gray-800">
            <thead class="bg-gray-50 text-gray-600 uppercase tracking-wide">
                <tr>
                    <th class="px-4 py-3 text-left">Назва</th>
                    <th class="px-4 py-3 text-left">Бренд</th>
                    <th class="px-4 py-3 text-left">Категорія</th>
                    <th class="px-4 py-3 text-left">Ціна</th>
                    <th class="px-4 py-3 text-right">Дії</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($instruments as $instrument)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold text-gray-900">{{ $instrument->title }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $instrument->brand->name }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $instrument->category->name }}</td>
                        <td class="px-4 py-3 text-gray-800">₴{{ number_format($instrument->price, 0, ',', ' ') }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('instruments.show', $instrument) }}" class="text-sm text-gray-700 hover:text-gray-900">Переглянути</a>
                            <a href="{{ route('admin.instruments.edit', $instrument) }}" class="text-sm text-blue-600 hover:underline">Редагувати</a>
                            <form action="{{ route('admin.instruments.destroy', $instrument) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="text-sm text-red-600 hover:text-red-700" onclick="return confirm('Видалити інструмент?')">Видалити</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $instruments->links() }}
    </div>
@endsection
