@extends('layouts.app')

@section('title', 'Автомобили')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-800">Автомобили</h1>
    <a href="{{ route('cars.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition duration-200">
        + Добавить автомобиль
    </a>
</div>

@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Владелец</th>
                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Автомобиль</th>
                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Госномер</th>
                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">VIN</th>
                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Год</th>
                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Действия</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach ($cars as $car)
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 text-sm text-gray-800">
                    <div class="font-medium">{{ $car->client->full_name }}</div>
                    <div class="text-xs text-gray-500">{{ $car->client->phone }}</div>
                </td>
                <td class="py-3 px-4 text-sm text-gray-800">
                    <div class="font-medium">{{ $car->brand }} {{ $car->model }}</div>
                    <div class="text-xs text-gray-500">
                        {{ $car->engine_model ? $car->engine_model . ', ' : '' }}
                        {{ $car->engine_volume ? $car->engine_volume . 'л' : '' }}
                    </div>
                </td>
                <td class="py-3 px-4 text-sm text-gray-800 font-mono">
                    {{ $car->license_plate }}
                </td>
                <td class="py-3 px-4 text-sm text-gray-800 font-mono">
                    {{ $car->vin ?? '-' }}
                </td>
                <td class="py-3 px-4 text-sm text-gray-800">
                    {{ $car->year }}
                </td>
                <td class="py-3 px-4 text-sm">
                    <a href="{{ route('cars.show', $car->car_id) }}" class="text-blue-600 hover:text-blue-800 mr-3">Просмотр</a>
                    <a href="{{ route('cars.edit', $car->car_id) }}" class="text-green-600 hover:text-green-800 mr-3">Редактировать</a>
                    <form action="{{ route('cars.destroy', $car->car_id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Удалить этот автомобиль?')">Удалить</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Статистика -->
<div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
    <div class="bg-white rounded-lg shadow p-4">
        <div class="text-sm text-gray-500">Всего автомобилей</div>
        <div class="text-2xl font-bold text-gray-800">{{ $cars->count() }}</div>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <div class="text-sm text-gray-500">Марок</div>
        <div class="text-2xl font-bold text-gray-800">{{ $cars->pluck('brand')->unique()->count() }}</div>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <div class="text-sm text-gray-500">Средний год</div>
        <div class="text-2xl font-bold text-gray-800">{{ round($cars->avg('year')) }}</div>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <div class="text-sm text-gray-500">Без VIN</div>
        <div class="text-2xl font-bold text-orange-600">{{ $cars->where('vin', null)->count() }}</div>
    </div>
</div>
@endsection
