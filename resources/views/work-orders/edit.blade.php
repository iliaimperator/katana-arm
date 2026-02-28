@extends('layouts.app')

@section('title', 'Редактирование заказ-наряда')

@section('content')
<div class="max-w-6xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Редактирование заказ-наряда: {{ $workOrder->order_number }}</h1>

    <form action="{{ route('work-orders.update', $workOrder->order_id) }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Основная информация -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Основная информация</h3>

                <div class="mb-4">
                    <label for="car_id" class="block text-sm font-medium text-gray-700 mb-2">Автомобиль *:</label>
                    <select name="car_id" id="car_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Выберите автомобиль</option>
                        @foreach($cars as $car)
                            <option value="{{ $car->car_id }}" {{ old('car_id', $workOrder->car_id) == $car->car_id ? 'selected' : '' }}>
                                {{ $car->client->full_name }} - {{ $car->brand }} {{ $car->model }} ({{ $car->license_plate }})
                            </option>
                        @endforeach
                    </select>
                    @error('car_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="order_date" class="block text-sm font-medium text-gray-700 mb-2">Дата заказа *:</label>
                        <input type="date" name="order_date" id="order_date" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('order_date', $workOrder->order_date->format('Y-m-d')) }}">
                        @error('order_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="reception_date" class="block text-sm font-medium text-gray-700 mb-2">Дата приема *:</label>
                        <input type="date" name="reception_date" id="reception_date" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('reception_date', $workOrder->reception_date->format('Y-m-d')) }}">
                        @error('reception_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="planned_completion_date" class="block text-sm font-medium text-gray-700 mb-2">Плановая дата завершения:</label>
                    <input type="date" name="planned_completion_date" id="planned_completion_date"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('planned_completion_date', $workOrder->planned_completion_date ? $workOrder->planned_completion_date->format('Y-m-d') : '') }}">
                    @error('planned_completion_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="mileage" class="block text-sm font-medium text-gray-700 mb-2">Пробег (км):</label>
                    <input type="number" name="mileage" id="mileage"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('mileage', $workOrder->mileage) }}"
                        placeholder="Например: 150000">
                    @error('mileage')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Статус *:</label>
                    <select name="status" id="status" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @foreach($statuses as $key => $status)
                            <option value="{{ $key }}" {{ old('status', $workOrder->status) == $key ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Описание работ -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Описание работ</h3>

                <div class="mb-4">
                    <label for="problem_description" class="block text-sm font-medium text-gray-700 mb-2">Описание проблемы *:</label>
                    <textarea name="problem_description" id="problem_description" rows="4" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Опишите проблему, с которой обратился клиент">{{ old('problem_description', $workOrder->problem_description) }}</textarea>
                    @error('problem_description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="work_description" class="block text-sm font-medium text-gray-700 mb-2">Описание работ:</label>
                    <textarea name="work_description" id="work_description" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Опишите планируемые работы">{{ old('work_description', $workOrder->work_description) }}</textarea>
                    @error('work_description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="recommendations" class="block text-sm font-medium text-gray-700 mb-2">Рекомендации:</label>
                    <textarea name="recommendations" id="recommendations" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Дополнительные рекомендации клиенту">{{ old('recommendations', $workOrder->recommendations) }}</textarea>
                    @error('recommendations')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between mt-8 pt-6 border-t">
            <a href="{{ route('work-orders.show', $workOrder->order_id) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded transition duration-200">
                Отмена
            </a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition duration-200">
                Обновить заказ-наряд
            </button>
        </div>
    </form>
</div>
@endsection
