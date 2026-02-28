@extends('layouts.app')

@section('title', 'Управление услугами')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-800">Управление услугами</h1>
    <a href="{{ route('services.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition duration-200">
        + Добавить услугу
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
                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Тип услуги</th>
                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Наименование</th>
                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Стоимость</th>
                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Действия</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach ($services as $service)
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 text-sm text-gray-800">{{ $service->service_type }}</td>
                <td class="py-3 px-4 text-sm text-gray-800">{{ $service->service_name }}</td>
                <td class="py-3 px-4 text-sm text-gray-800">{{ number_format($service->standard_cost, 2) }} ₽</td>
                <td class="py-3 px-4 text-sm">
                    <a href="{{ route('services.edit', $service->service_id) }}" class="text-blue-600 hover:text-blue-800 mr-3">Редактировать</a>
                    <form action="{{ route('services.destroy', $service->service_id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Удалить эту услугу?')">Удалить</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
