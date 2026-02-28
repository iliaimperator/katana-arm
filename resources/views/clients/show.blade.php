@extends('layouts.app')

@section('title', 'Профиль клиента')

@section('content')
<div class="max-w-6xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Профиль клиента</h1>
        <div class="flex space-x-2">
            <a href="{{ route('cars.create') }}?client_id={{ $client->client_id }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition duration-200">
                + Добавить автомобиль
            </a>
            <a href="{{ route('clients.edit', $client->client_id) }}" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded transition duration-200">
                Редактировать
            </a>
            <a href="{{ route('clients.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded transition duration-200">
                Назад
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Информация о клиенте -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Информация о клиенте</h3>

                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">ФИО:</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $client->full_name }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Телефон:</label>
                        <p class="text-gray-800">{{ $client->phone }}</p>
                    </div>

                    @if($client->email)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Email:</label>
                        <p class="text-gray-800">{{ $client->email }}</p>
                    </div>
                    @endif

                    @if($client->address)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Адрес:</label>
                        <p class="text-gray-800">{{ $client->address }}</p>
                    </div>
                    @endif

                    @if($client->notes)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Заметки:</label>
                        <p class="text-gray-800">{{ $client->notes }}</p>
                    </div>
                    @endif

                    <div>
                        <label class="text-sm font-medium text-gray-500">Дата регистрации:</label>
                        <p class="text-gray-800">{{ $client->created_at->format('d.m.Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Автомобили клиента -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">Автомобили клиента</h3>
                        <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                            {{ $client->cars->count() }} автомобилей
                        </span>
                    </div>
                </div>

                @if($client->cars->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($client->cars as $car)
                        <div class="p-6 hover:bg-gray-50">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h4 class="text-lg font-semibold text-gray-800">
                                            {{ $car->brand }} {{ $car->model }}
                                        </h4>
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded">
                                            {{ $car->year }} год
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-gray-600">
                                        <div>
                                            <span class="font-medium">Госномер:</span>
                                            <span class="font-mono ml-1">{{ $car->license_plate }}</span>
                                        </div>
                                        <div>
                                            <span class="font-medium">VIN:</span>
                                            <span class="font-mono ml-1">{{ $car->vin ?? '—' }}</span>
                                        </div>
                                        <div>
                                            <span class="font-medium">Двигатель:</span>
                                            <span class="ml-1">
                                                {{ $car->engine_model ? $car->engine_model . ', ' : '' }}
                                                {{ $car->engine_volume ? $car->engine_volume . 'л' : '—' }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="font-medium">КПП:</span>
                                            <span class="ml-1">
                                                @if($car->transmission == 'manual')
                                                    МКПП
                                                @elseif($car->transmission == 'auto')
                                                    АКПП
                                                @else
                                                    —
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    @if($car->color || $car->notes)
                                    <div class="mt-3 text-sm text-gray-600">
                                        @if($car->color)
                                            <span class="mr-4">🎨 {{ $car->color }}</span>
                                        @endif
                                        @if($car->notes)
                                            <span>📝 {{ Str::limit($car->notes, 100) }}</span>
                                        @endif
                                    </div>
                                    @endif
                                </div>

                                <div class="flex space-x-2 ml-4">
                                    <a href="{{ route('cars.edit', $car->car_id) }}"
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Редактировать
                                    </a>
                                    <a href="{{ route('cars.show', $car->car_id) }}"
                                       class="text-green-600 hover:text-green-800 text-sm font-medium">
                                        Просмотр
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="text-gray-400 mb-4">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Нет автомобилей</h3>
                        <p class="text-gray-500 mb-4">У этого клиента еще нет добавленных автомобилей.</p>
                        <a href="{{ route('cars.create') }}?client_id={{ $client->client_id }}"
                           class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition duration-200 inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Добавить первый автомобиль
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
