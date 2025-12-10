@extends('layouts.admin')

@section('title', 'Редагувати категорію')

@section('content')
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ $category->name }}</h2>
        <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <label class="space-y-2 block">
                <span class="text-sm text-gray-700">Назва</span>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" class="input" required>
                @error('name') <p class="text-sm text-red-400">{{ $message }}</p> @enderror
            </label>
            <label class="space-y-2 block">
                <span class="text-sm text-gray-700">Slug</span>
                <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" class="input">
                @error('slug') <p class="text-sm text-red-400">{{ $message }}</p> @enderror
            </label>
            <div class="flex items-center gap-3">
                <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded-md text-sm">Зберегти</button>
                <a href="{{ route('admin.categories.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Скасувати</a>
            </div>
        </form>
    </div>
@endsection
