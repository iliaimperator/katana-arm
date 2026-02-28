@extends('layouts.app')

@section('title', 'Редактирование услуги')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Редактирование услуги</h1>

    <form action="{{ route('services.update', $service->service_id) }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="service_type" class="block text-sm font-medium text-gray-700 mb-2">Тип услуги:</label>
            <input type="text" name="service_type" id="service_type" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                value="{{ old('service_type', $service->service_type) }}">
            @error('service_type')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="service_name" class="block text-sm font-medium text-gray-700 mb-2">Наименование услуги:</label>
            <input type="text" name="service_name" id="service_name" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                value="{{ old('service_name', $service->service_name) }}">
            @error('service_name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="standard_cost" class="block text-sm font-medium text-gray-700 mb-2">Стандартная стоимость (₽):</label>
            <input type="number" step="0.01" name="standard_cost" id="standard_cost" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                value="{{ old('standard_cost', $service->standard_cost) }}">
            @error('standard_cost')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Описание:</label>
            <textarea name="description" id="description" rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $service->description) }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('services.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded transition duration-200">
                Назад к списку
            </a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition duration-200">
                Обновить услугу
            </button>
        </div>
    </form>
</div>
@endsection
