@extends('layouts.admin')

@section('title', 'Новий бренд')

@section('content')
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Новий бренд</h2>
        <form method="POST" action="{{ route('admin.brands.store') }}" class="space-y-4">
            @csrf
            <label class="space-y-2 block">
                <span class="text-sm text-gray-700">Назва</span>
                <input type="text" name="name" value="{{ old('name') }}" class="input" required>
                @error('name') <p class="text-sm text-red-400">{{ $message }}</p> @enderror
            </label>
            <label class="space-y-2 block">
                <span class="text-sm text-gray-700">Slug (опційно)</span>
                <input type="text" name="slug" value="{{ old('slug') }}" class="input">
                @error('slug') <p class="text-sm text-red-400">{{ $message }}</p> @enderror
            </label>
            <label class="space-y-2 block">
                <span class="text-sm text-gray-700">Країна</span>
                <input type="text" name="country" value="{{ old('country') }}" class="input" placeholder="USA, Japan...">
                @error('country') <p class="text-sm text-red-400">{{ $message }}</p> @enderror
            </label>
            <label class="space-y-2 block">
                <span class="text-sm text-gray-700">Опис</span>
                <textarea name="description" rows="3" class="input">{{ old('description') }}</textarea>
                @error('description') <p class="text-sm text-red-400">{{ $message }}</p> @enderror
            </label>
            <div class="flex items-center gap-3">
                <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded-md text-sm">Створити</button>
                <a href="{{ route('admin.brands.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Скасувати</a>
            </div>
        </form>
    </div>
@endsection
