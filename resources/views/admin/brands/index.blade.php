@extends('layouts.admin')

@section('title', 'Бренди')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-900">Бренди</h2>
        <a href="{{ route('admin.brands.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded-md text-sm">+ Додати бренд</a>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full text-sm text-gray-800">
            <thead class="bg-gray-50 text-gray-600 uppercase tracking-wide">
                <tr>
                    <th class="px-4 py-3 text-left">Назва</th>
                    <th class="px-4 py-3 text-left">Країна</th>
                    <th class="px-4 py-3 text-left">Slug</th>
                    <th class="px-4 py-3 text-right">Дії</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($brands as $brand)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold text-gray-900">{{ $brand->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $brand->country ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $brand->slug }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('admin.brands.edit', $brand) }}" class="text-sm text-blue-600 hover:underline">Редагувати</a>
                            <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="text-sm text-red-600 hover:text-red-700" onclick="return confirm('Видалити бренд?')">Видалити</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $brands->links() }}
    </div>
@endsection
