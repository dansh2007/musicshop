@extends('layouts.admin')

@section('title', 'Додати інструмент')

@section('content')
    <div class="bg-white border border-gray-200 shadow-sm rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Новий інструмент</h2>
        <form method="POST" action="{{ route('admin.instruments.store') }}" enctype="multipart/form-data" class="space-y-4">
            @include('admin.instruments._form')
        </form>
    </div>
@endsection
