@extends('layouts.app')

@section('title', 'Просмотр автомобиля')

@section('content')
<div class="max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Просмотр автомобиля</h1>
        <div class="flex space-x-2">
            <a href="{{ route('cars.edit', $car->car_id) }}" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded transition duration-200">
                Редактировать
            </a>
            <a href="{{ route('cars.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded transition duration-200">
                Назад
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <!-- Основная информация -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Информация об автомобиле</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Марка и модель</dt>
                            <dd class="text-lg font-semibold text-gray-800">{{ $car->brand }} {{ $car->model }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Год выпуска</dt>
                            <dd class="text-gray-800">{{ $car->year }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Госномер</dt>
                            <dd class="font-mono text-gray-800">{{ $car->license_plate }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">VIN</dt>
                            <dd class="font-mono text-gray-800">{{ $car->vin ?? 'Не указан' }}</dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Технические характеристики</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Двигатель</dt>
                            <dd class="text-gray-800">
                                {{ $car->engine_model ? $car->engine_model . ', ' : '' }}
                                {{ $car->engine_volume ? $car->engine_volume . 'л' : 'Не указан' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Коробка передач</dt>
                            <dd class="text-gray-800">
                                @if($car->transmission == 'manual')
                                    МКПП
                                @elseif($car->transmission == 'auto')
                                    АКПП
                                @else
                                    Не указана
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Цвет</dt>
                            <dd class="text-gray-800">{{ $car->color ?? 'Не указан' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Информация о владельце -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Владелец</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-semibold text-gray-800">{{ $car->client->full_name }}</div>
                            <div class="text-sm text-gray-600 mt-1">
                                <span class="mr-4">📞 {{ $car->client->phone }}</span>
                                @if($car->client->email)
                                    <span>✉️ {{ $car->client->email }}</span>
                                @endif
                            </div>
                            @if($car->client->address)
                                <div class="text-sm text-gray-600 mt-1">📍 {{ $car->client->address }}</div>
                            @endif
                        </div>
                        <a href="{{ route('clients.show', $car->client->client_id) }}" class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium py-2 px-3 rounded transition duration-200">
                            Профиль клиента
                        </a>
                    </div>
                </div>
            </div>

            <!-- Заметки -->
            @if($car->notes)
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Заметки</h3>
                <div class="bg-yellow-50 rounded-lg p-4">
                    <p class="text-gray-700">{{ $car->notes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
