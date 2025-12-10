@extends('layouts.admin')

@section('title', 'Редагувати інструмент')

@section('content')
    <div class="bg-white border border-gray-200 shadow-sm rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">{{ $instrument->title }}</h2>
            <a href="{{ route('instruments.show', $instrument) }}" class="text-sm text-blue-600 hover:underline">Переглянути публічно</a>
        </div>
        <form method="POST" action="{{ route('admin.instruments.update', $instrument) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            @include('admin.instruments._form', ['instrument' => $instrument])
        </form>
    </div>
@endsection
