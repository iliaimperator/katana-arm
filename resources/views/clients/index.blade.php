@extends('layouts.app')

@section('title', 'Клиенты')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Клиенты</h1>
        <a href="{{ route('clients.create') }}"
            class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition duration-200">
            + Добавить клиента
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
                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">ФИО</th>
                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Телефон</th>
                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Email</th>
                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Автомобили</th>
                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Действия</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($clients as $client)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 text-sm text-gray-800">
                            <div class="font-medium">{{ $client->full_name }}</div>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-800">{{ $client->phone }}</td>
                        <td class="py-3 px-4 text-sm text-gray-800">{{ $client->email ?? '-' }}</td>
                        <td class="py-3 px-4 text-sm text-gray-800">
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
        {{ $client->cars_count > 0 ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800' }}">
                                {{ $client->cars_count }} шт.
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm">
                            <a href="{{ route('clients.show', $client->client_id) }}"
                                class="text-blue-600 hover:text-blue-800 mr-3">Просмотр</a>
                            <a href="{{ route('clients.edit', $client->client_id) }}"
                                class="text-green-600 hover:text-green-800 mr-3">Редактировать</a>
                            <form action="{{ route('clients.destroy', $client->client_id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800"
                                    onclick="return confirm('Удалить этого клиента?')">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Статистика -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Всего клиентов</div>
            <div class="text-2xl font-bold text-gray-800">{{ $clients->count() }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Всего автомобилей</div>
            <div class="text-2xl font-bold text-gray-800">{{ $clients->sum('cars_count') }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Клиентов без авто</div>
            <div class="text-2xl font-bold text-orange-600">{{ $clients->where('cars_count', 0)->count() }}</div>
        </div>
    </div>
@endsection
