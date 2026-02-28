@extends('layouts.app')

@section('title', 'Добавление нового автомобиля')

@section('content')
    <div class="max-w-4xl">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Добавление нового автомобиля</h1>

        <form action="{{ route('cars.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
            @csrf

            <div class="mb-6">
                <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">Владелец *:</label>
                <select name="client_id" id="client_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Выберите клиента</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->client_id }}"
                            {{ old('client_id', $selectedClientId) == $client->client_id ? 'selected' : '' }}>
                            {{ $client->full_name }} ({{ $client->phone }})
                        </option>
                    @endforeach
                </select>
                @error('client_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="license_plate" class="block text-sm font-medium text-gray-700 mb-2">Госномер *:</label>
                    <input type="text" name="license_plate" id="license_plate" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 uppercase"
                        value="{{ old('license_plate') }}" placeholder="А123БВ77">
                    @error('license_plate')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="vin" class="block text-sm font-medium text-gray-700 mb-2">VIN номер:</label>
                    <input type="text" name="vin" id="vin"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 uppercase"
                        value="{{ old('vin') }}" placeholder="1HGCM82633A123456">
                    @error('vin')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">Марка *:</label>
                    <input type="text" name="brand" id="brand" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('brand') }}" placeholder="Toyota">
                    @error('brand')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="model" class="block text-sm font-medium text-gray-700 mb-2">Модель *:</label>
                    <input type="text" name="model" id="model" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('model') }}" placeholder="Camry">
                    @error('model')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Год выпуска *:</label>
                    <input type="number" name="year" id="year" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('year', date('Y')) }}" min="1900" max="{{ date('Y') + 1 }}">
                    @error('year')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label for="engine_model" class="block text-sm font-medium text-gray-700 mb-2">Модель двигателя:</label>
                    <input type="text" name="engine_model" id="engine_model"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('engine_model') }}" placeholder="1AZ-FE">
                    @error('engine_model')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="engine_volume" class="block text-sm font-medium text-gray-700 mb-2">Объем двигателя
                        (л):</label>
                    <input type="number" step="0.1" name="engine_volume" id="engine_volume"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('engine_volume') }}" placeholder="2.4" min="0.5" max="10.0">
                    @error('engine_volume')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="transmission" class="block text-sm font-medium text-gray-700 mb-2">Коробка передач:</label>
                    <select name="transmission" id="transmission"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Не указана</option>
                        @foreach ($transmissions as $key => $value)
                            <option value="{{ $key }}" {{ old('transmission') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('transmission')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 mb-2">Цвет:</label>
                    <input type="text" name="color" id="color"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('color') }}" placeholder="Черный">
                    @error('color')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Заметки:</label>
                <textarea name="notes" id="notes" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Особенности автомобиля, предыдущие ремонты и т.д.">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('cars.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded transition duration-200">
                    Назад к списку
                </a>
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition duration-200">
                    Сохранить автомобиль
                </button>
            </div>
        </form>
    </div>
@endsection
