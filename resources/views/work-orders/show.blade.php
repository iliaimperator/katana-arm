@extends('layouts.app')

@section('title', 'Заказ-наряд ' . $workOrder->order_number)

@section('content')
    <div class="max-w-7xl">
        <!-- Заголовок -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Заказ-наряд: {{ $workOrder->order_number }}</h1>
                <p class="text-gray-600">Создан: {{ $workOrder->created_at->format('d.m.Y H:i') }}</p>
            </div>
            <div class="flex space-x-2">
                @if($workOrder->status == 'completed') {{-- Или другой статус, означающий завершение --}}
    <a href="{{ route('work-orders.act', $workOrder->order_id) }}"
       target="_blank"
       class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-800 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 no-print">
        📄 Акт выполненных работ
    </a>
@endif
                <!-- Новая кнопка печати -->
                <a href="{{ route('work-orders.print', $workOrder->order_id) }}" target="_blank"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    Печать
                </a>

                <a href="{{ route('work-orders.edit', $workOrder->order_id) }}"
                    class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded transition duration-200">
                    Редактировать
                </a>
                <a href="{{ route('work-orders.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded transition duration-200">
                    Назад
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Основная информация -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Информация о клиенте и автомобиле -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Клиент и автомобиль</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Клиент</h4>
                            <p class="text-lg font-semibold">{{ $workOrder->car->client->full_name }}</p>
                            <p class="text-gray-600">📞 {{ $workOrder->car->client->phone }}</p>
                            @if ($workOrder->car->client->email)
                                <p class="text-gray-600">✉️ {{ $workOrder->car->client->email }}</p>
                            @endif
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Автомобиль</h4>
                            <p class="text-lg font-semibold">{{ $workOrder->car->brand }} {{ $workOrder->car->model }}</p>
                            <p class="text-gray-600">🚗 {{ $workOrder->car->license_plate }}</p>
                            <p class="text-gray-600">📅 {{ $workOrder->car->year }} год</p>
                            @if ($workOrder->mileage)
                                <p class="text-gray-600">🛣️ Пробег: {{ number_format($workOrder->mileage, 0, '', ' ') }}
                                    км</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Форма добавления услуги -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Добавить услугу</h3>

                    <form action="{{ route('work-orders.add-service', $workOrder->order_id) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="service_id" class="block text-sm font-medium text-gray-700 mb-1">Услуга
                                    *</label>
                                <select name="service_id" id="service_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="">Выберите услугу</option>
                                    @foreach ($services = \App\Models\Service::orderBy('service_type')->orderBy('service_name')->get() as $service)
                                        <option value="{{ $service->service_id }}">
                                            {{ $service->service_type }} - {{ $service->service_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Кол-во *</label>
                                <input type="number" name="quantity" id="quantity" value="1" min="1" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            </div>

                            <div>
                                <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-1">Цена *</label>
                                <input type="number" step="0.01" name="unit_price" id="unit_price" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                    placeholder="0.00">
                            </div>

                            <div class="flex items-end">
                                <button type="submit"
                                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition duration-200 text-sm">
                                    Добавить
                                </button>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Примечания</label>
                            <input type="text" name="notes" id="notes"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                placeholder="Дополнительная информация">
                        </div>
                    </form>
                </div>

                <!-- Форма добавления запчасти -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Добавить запчасть</h3>

                    <form action="{{ route('work-orders.add-part', $workOrder->order_id) }}" method="POST"
                        id="add-part-form">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <div>
                                <label for="part_id" class="block text-sm font-medium text-gray-700 mb-1">Запчасть
                                    *</label>
                                <select name="part_id" id="part_id" required onchange="updatePartPrice()"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="">Выберите запчасть</option>
                                    @foreach ($parts = \App\Models\SparePart::all() as $part)
                                        <option value="{{ $part->part_id }}" data-price="{{ $part->selling_price }}"
                                            data-stock="{{ $part->stock_quantity }}">
                                            {{ $part->part_name }} (арт: {{ $part->article_number }}) -
                                            {{ $part->stock_quantity }} шт.
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Кол-во *</label>
                                <input type="number" name="quantity" id="part_quantity" value="1" min="1"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            </div>

                            <div>
                                <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-1">Цена *</label>
                                <input type="number" step="0.01" name="unit_price" id="part_unit_price" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                    placeholder="0.00">
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Статус
                                    *</label>
                                <select name="status" id="status" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="ordered">Заказана</option>
                                    <option value="in_stock">На складе</option>
                                    <option value="used">Установлена</option>
                                </select>
                            </div>

                            <div class="flex items-end">
                                <button type="submit"
                                    class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded transition duration-200 text-sm">
                                    Добавить
                                </button>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label for="part_notes"
                                class="block text-sm font-medium text-gray-700 mb-1">Примечания</label>
                            <input type="text" name="notes" id="part_notes"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                placeholder="Дополнительная информация">
                        </div>

                        <div class="mt-2">
                            <div id="part-stock-info" class="text-sm text-gray-600 hidden"></div>
                        </div>
                    </form>
                </div>

                <!-- Форма добавления платежа -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Добавить платеж</h3>

                    <form action="{{ route('work-orders.add-payment', $workOrder->order_id) }}" method="POST"
                        id="add-payment-form">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <div>
                                <label for="payment_type" class="block text-sm font-medium text-gray-700 mb-1">Тип платежа
                                    *</label>
                                <select name="type" id="payment_type" required onchange="updatePaymentInfo()"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="prepayment">Предоплата</option>
                                    <option value="final">Окончательный расчет</option>
                                </select>
                            </div>

                            <div>
                                <label for="payment_amount" class="block text-sm font-medium text-gray-700 mb-1">Сумма
                                    *</label>
                                <input type="number" step="0.01" name="amount" id="payment_amount" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                    placeholder="0.00">
                            </div>

                            <div>
                                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Способ
                                    оплаты *</label>
                                <select name="method" id="payment_method" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="cash">Наличные</option>
                                    <option value="card">Карта</option>
                                    <option value="transfer">Перевод</option>
                                </select>
                            </div>

                            <div>
                                <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-1">Дата
                                    платежа *</label>
                                <input type="date" name="payment_date" id="payment_date" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                    value="{{ date('Y-m-d') }}">
                            </div>

                            <div class="flex items-end">
                                <button type="submit"
                                    class="w-full bg-purple-500 hover:bg-purple-600 text-white font-medium py-2 px-4 rounded transition duration-200 text-sm">
                                    Добавить
                                </button>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label for="payment_notes"
                                class="block text-sm font-medium text-gray-700 mb-1">Примечания</label>
                            <input type="text" name="notes" id="payment_notes"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                placeholder="Дополнительная информация">
                        </div>

                        <!-- Информация о платежах -->
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div id="prepayment-info" class="bg-blue-50 p-3 rounded-lg hidden">
                                <div class="font-medium text-blue-800">Предоплата</div>
                                <div>Требуется: <span id="prepayment-required">0</span> ₽</div>
                                <div>Оплачено: <span id="prepayment-paid">0</span> ₽</div>
                                <div>Осталось: <span id="prepayment-remaining">0</span> ₽</div>
                            </div>

                            <div id="final-info" class="bg-green-50 p-3 rounded-lg hidden">
                                <div class="font-medium text-green-800">Окончательный расчет</div>
                                <div>Требуется: <span id="final-required">0</span> ₽</div>
                                <div>Оплачено: <span id="final-paid">0</span> ₽</div>
                                <div>Осталось: <span id="final-remaining">0</span> ₽</div>
                            </div>
                        </div>

                        <div class="mt-3 text-center">
                            <div class="text-lg font-bold text-gray-800">
                                Всего оплачено: <span id="total-paid">0</span> ₽ из <span id="total-required">0</span> ₽
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Услуги -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Услуги</h3>
                        <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                            {{ $workOrder->services->count() }} услуг
                        </span>
                    </div>

                    @if ($workOrder->services->count() > 0)
                        <div class="space-y-3">
                            @foreach ($workOrder->services as $service)
                                <div
                                    class="flex justify-between items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-800">{{ $service->service->service_name }}</div>
                                        <div class="text-sm text-gray-600">{{ $service->service->service_type }}</div>
                                        @if ($service->notes)
                                            <div class="text-sm text-gray-500 mt-1">{{ $service->notes }}</div>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <div class="font-semibold text-gray-800">
                                            {{ $service->quantity }} × {{ number_format($service->unit_price, 2) }} ₽
                                        </div>
                                        <div class="text-lg font-bold text-blue-600">
                                            {{ number_format($service->total_price, 2) }} ₽
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <form
                                            action="{{ route('work-orders.remove-service', ['workOrder' => $workOrder->order_id, 'service' => $service->order_service_id]) }}"
                                            method="POST" onsubmit="return confirm('Удалить эту услугу из заказа?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 p-1">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Услуги не добавлены</p>
                    @endif
                </div>

                <!-- Запчасти -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Запчасти</h3>
                        <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                            {{ $workOrder->parts->count() }} запчастей
                        </span>
                    </div>

                    @if ($workOrder->parts->count() > 0)
                        <div class="space-y-3">
                            @foreach ($workOrder->parts as $part)
                                <div
                                    class="flex justify-between items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-800">{{ $part->part->part_name }}</div>
                                        <div class="text-sm text-gray-600">
                                            Артикул: {{ $part->part->article_number }}
                                            <span
                                                class="ml-2 px-2 py-1 rounded-full text-xs font-medium
                            {{ $part->status == 'used'
                                ? 'bg-green-100 text-green-800'
                                : ($part->status == 'in_stock'
                                    ? 'bg-blue-100 text-blue-800'
                                    : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ \App\Models\WorkOrderPart::getStatuses()[$part->status] }}
                                            </span>
                                        </div>
                                        @if ($part->notes)
                                            <div class="text-sm text-gray-500 mt-1">{{ $part->notes }}</div>
                                        @endif
                                    </div>
                                    <div class="text-right mr-4">
                                        <div class="font-semibold text-gray-800">
                                            {{ $part->quantity }} × {{ number_format($part->unit_price, 2) }} ₽
                                        </div>
                                        <div class="text-lg font-bold text-green-600">
                                            {{ number_format($part->total_price, 2) }} ₽
                                        </div>
                                    </div>
                                    <div class="flex flex-col space-y-2">
                                        <!-- Форма изменения статуса -->
                                        <form
                                            action="{{ route('work-orders.update-part-status', ['workOrder' => $workOrder->order_id, 'part' => $part->order_part_id]) }}"
                                            method="POST" class="text-sm">
                                            @csrf
                                            <select name="status" onchange="this.form.submit()"
                                                class="text-xs border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                                @foreach (\App\Models\WorkOrderPart::getStatuses() as $key => $status)
                                                    <option value="{{ $key }}"
                                                        {{ $part->status == $key ? 'selected' : '' }}>
                                                        {{ $status }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>

                                        <!-- Кнопка удаления -->
                                        <form
                                            action="{{ route('work-orders.remove-part', ['workOrder' => $workOrder->order_id, 'part' => $part->order_part_id]) }}"
                                            method="POST" onsubmit="return confirm('Удалить эту запчасть из заказа?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                Удалить
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Запчасти не добавлены</p>
                    @endif
                </div>

                <!-- Описание проблемы и работ -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Описание работ</h3>

                    <div class="mb-4">
                        <h4 class="font-medium text-gray-700 mb-2">Проблема:</h4>
                        <p class="text-gray-800">{{ $workOrder->problem_description }}</p>
                    </div>

                    @if ($workOrder->work_description)
                        <div class="mb-4">
                            <h4 class="font-medium text-gray-700 mb-2">Выполненные работы:</h4>
                            <p class="text-gray-800">{{ $workOrder->work_description }}</p>
                        </div>
                    @endif

                    @if ($workOrder->recommendations)
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Рекомендации:</h4>
                            <p class="text-gray-800">{{ $workOrder->recommendations }}</p>
                        </div>
                    @endif
                </div>


            </div>

            <!-- Боковая панель -->
            <div class="space-y-6">
                <!-- Изменение статуса -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Изменение статуса</h3>

                    <form action="{{ route('work-orders.update-status', $workOrder->order_id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <select name="status" id="status" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @foreach (\App\Models\WorkOrder::getStatuses() as $key => $status)
                                    <option value="{{ $key }}"
                                        {{ $workOrder->status == $key ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit"
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition duration-200">
                            Обновить статус
                        </button>
                    </form>
                </div>

                <!-- Статус и даты -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Статус заказа</h3>

                    @php
                        $statusColors = [
                            'draft' => 'bg-gray-100 text-gray-800',
                            'accepted' => 'bg-blue-100 text-blue-800',
                            'in_progress' => 'bg-yellow-100 text-yellow-800',
                            'waiting_parts' => 'bg-orange-100 text-orange-800',
                            'ready' => 'bg-green-100 text-green-800',
                            'completed' => 'bg-purple-100 text-purple-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                        ];
                    @endphp

                    <div class="mb-4">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$workOrder->status] }}">
                            {{ \App\Models\WorkOrder::getStatuses()[$workOrder->status] }}
                        </span>
                    </div>

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Дата приема:</span>
                            <span
                                class="font-medium">{{ $workOrder->reception_date ? $workOrder->reception_date->format('d.m.Y') : 'Не указана' }}</span>
                        </div>
                        @if ($workOrder->planned_completion_date)
                            <div class="flex justify-between">
                                <span class="text-gray-600">План завершения:</span>
                                <span
                                    class="font-medium">{{ $workOrder->planned_completion_date->format('d.m.Y') }}</span>
                            </div>
                        @endif
                        @if ($workOrder->actual_completion_date)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Факт. завершения:</span>
                                <span class="font-medium">{{ $workOrder->actual_completion_date->format('d.m.Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Финансовая информация -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Финансы</h3>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Стоимость услуг:</span>
                            <span class="font-medium">{{ number_format($workOrder->services->sum('total_price'), 2) }}
                                ₽</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Стоимость запчастей:</span>
                            <span class="font-medium">{{ number_format($workOrder->parts->sum('total_price'), 2) }}
                                ₽</span>
                        </div>
                        <div class="flex justify-between items-center border-t pt-2">
                            <span class="text-gray-800 font-semibold">Общая стоимость:</span>
                            <span class="text-lg font-bold text-blue-600">{{ number_format($workOrder->total_cost, 2) }}
                                ₽</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Предоплата (50%):</span>
                            <span
                                class="font-medium text-orange-600">{{ number_format($workOrder->prepayment_amount, 2) }}
                                ₽</span>
                        </div>
                        <div class="flex justify-between items-center border-t pt-2">
                            <span class="text-gray-800 font-semibold">Итоговая стоимость:</span>
                            <span class="text-lg font-bold text-green-600">{{ number_format($workOrder->final_cost, 2) }}
                                ₽</span>
                        </div>
                    </div>
                </div>

                <!-- Платежи -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Платежи</h3>
                        <span class="bg-purple-100 text-purple-800 text-sm font-medium px-3 py-1 rounded-full">
                            {{ $workOrder->payments->count() }} платежей
                        </span>
                    </div>

                    @if ($workOrder->payments->count() > 0)
                        <div class="space-y-3">
                            @foreach ($workOrder->payments as $payment)
                                <div
                                    class="flex justify-between items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3">
                                            <span class="font-medium text-gray-800">
                                                {{ \App\Models\Payment::getTypes()[$payment->type] }}
                                            </span>
                                            <span
                                                class="px-2 py-1 rounded-full text-xs font-medium
                            {{ $payment->type == 'prepayment' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                {{ \App\Models\Payment::getMethods()[$payment->method] }}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-600 mt-1">
                                            Дата:
                                            {{ $payment->payment_date ? $payment->payment_date->format('d.m.Y') : 'Не указана' }}
                                            @if ($payment->notes)
                                                • {{ $payment->notes }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right mr-4">
                                        <div class="text-xl font-bold text-green-600">
                                            {{ number_format($payment->amount, 2) }} ₽
                                        </div>
                                    </div>
                                    <div>
                                        <form
                                            action="{{ route('work-orders.remove-payment', ['workOrder' => $workOrder->order_id, 'payment' => $payment->payment_id]) }}"
                                            method="POST" onsubmit="return confirm('Удалить этот платеж?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 p-1">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Сводка по платежам -->
                        @php
                            $prepaymentTotal = $workOrder->payments->where('type', 'prepayment')->sum('amount');
                            $finalTotal = $workOrder->payments->where('type', 'final')->sum('amount');
                            $totalPaid = $prepaymentTotal + $finalTotal;
                            $paymentProgress =
                                $workOrder->final_cost > 0 ? ($totalPaid / $workOrder->final_cost) * 100 : 0;
                        @endphp

                        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700">Общий прогресс оплаты</span>
                                <span
                                    class="text-sm font-bold text-gray-800">{{ number_format($paymentProgress, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $paymentProgress }}%"></div>
                            </div>
                            <div class="grid grid-cols-3 gap-4 mt-3 text-sm">
                                <div class="text-center">
                                    <div class="text-blue-600 font-semibold">{{ number_format($prepaymentTotal, 2) }} ₽
                                    </div>
                                    <div class="text-gray-600">Предоплата</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-green-600 font-semibold">{{ number_format($finalTotal, 2) }} ₽</div>
                                    <div class="text-gray-600">Окончательный</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-purple-600 font-semibold">{{ number_format($totalPaid, 2) }} ₽</div>
                                    <div class="text-gray-600">Всего оплачено</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Платежи не добавлены</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Скрипт загружен!');

            const serviceSelect = document.getElementById('service_id');
            const unitPriceInput = document.getElementById('unit_price');

            if (!serviceSelect || !unitPriceInput) {
                console.log('Не найдены элементы формы');
                return;
            }

            // Данные услуг - вручную перечислим несколько для теста
            const servicePrices = {
                @foreach (\App\Models\Service::all() as $service)
                    "{{ $service->service_id }}": "{{ $service->standard_cost }}",
                @endforeach
            };

            console.log('Загружены цены услуг:', servicePrices);

            serviceSelect.addEventListener('change', function() {
                const serviceId = this.value;
                console.log('Выбрана услуга с ID:', serviceId);

                if (serviceId && servicePrices[serviceId]) {
                    const price = parseFloat(servicePrices[serviceId]).toFixed(2);
                    console.log('Устанавливаем цену:', price);
                    unitPriceInput.value = price;
                } else {
                    console.log('Услуга не найдена или не выбрана');
                    unitPriceInput.value = '';
                }
            });

            // Добавим кнопку для теста
            console.log('Элементы найдены, скрипт готов к работе');
        });
    </script>

    <script>
        // Функция для обновления цены запчасти
        function updatePartPrice() {
            const partSelect = document.getElementById('part_id');
            const unitPriceInput = document.getElementById('part_unit_price');
            const stockInfo = document.getElementById('part-stock-info');

            const selectedOption = partSelect.options[partSelect.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            const stock = selectedOption.getAttribute('data-stock');

            if (price) {
                unitPriceInput.value = parseFloat(price).toFixed(2);
            } else {
                unitPriceInput.value = '';
            }

            // Показываем информацию о наличии
            if (stockInfo && stock) {
                stockInfo.textContent = `В наличии: ${stock} шт.`;
                stockInfo.classList.remove('hidden');

                if (parseInt(stock) === 0) {
                    stockInfo.classList.add('text-red-600');
                    stockInfo.classList.remove('text-gray-600');
                } else if (parseInt(stock) < 5) {
                    stockInfo.classList.add('text-orange-600');
                    stockInfo.classList.remove('text-gray-600');
                } else {
                    stockInfo.classList.add('text-green-600');
                    stockInfo.classList.remove('text-gray-600');
                }
            }
        }

        // Обработчик изменения количества запчастей
        document.getElementById('part_quantity')?.addEventListener('input', function() {
            const partSelect = document.getElementById('part_id');
            const selectedOption = partSelect.options[partSelect.selectedIndex];
            const stock = selectedOption?.getAttribute('data-stock');
            const quantity = parseInt(this.value) || 0;

            if (stock && quantity > parseInt(stock)) {
                this.setCustomValidity('Недостаточно на складе');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>

    <script>
        // Функция для обновления информации о платежах
        function updatePaymentInfo() {
            const paymentType = document.getElementById('payment_type').value;
            const amountInput = document.getElementById('payment_amount');

            // Используем данные из Blade
            const prepaymentRequired = {{ $workOrder->prepayment_amount }};
            const finalRequired = {{ $workOrder->final_cost }};

            // Получаем оплаченные суммы из Blade
            const prepaymentPaid = {{ $workOrder->payments->where('type', 'prepayment')->sum('amount') }};
            const finalPaid = {{ $workOrder->payments->where('type', 'final')->sum('amount') }};

            // Рассчитываем оставшиеся суммы
            const prepaymentRemaining = Math.max(0, prepaymentRequired - prepaymentPaid);
            const finalRemaining = Math.max(0, finalRequired - prepaymentPaid - finalPaid);

            // Автоматически подставляем сумму при выборе типа платежа
            if (paymentType === 'prepayment') {
                // Подставляем оставшуюся сумму предоплаты
                amountInput.value = prepaymentRemaining > 0 ? prepaymentRemaining.toFixed(2) : '0.00';
                amountInput.max = prepaymentRemaining;
            } else {
                // Подставляем оставшуюся сумму окончательного расчета
                amountInput.value = finalRemaining > 0 ? finalRemaining.toFixed(2) : '0.00';
                amountInput.max = finalRemaining;
            }

            // Обновляем информацию о платежах
            document.getElementById('total-paid').textContent = (prepaymentPaid + finalPaid).toFixed(2);
            document.getElementById('total-required').textContent = finalRequired.toFixed(2);

            // Обновляем информацию о предоплате
            const prepaymentInfo = document.getElementById('prepayment-info');
            if (prepaymentRequired > 0) {
                document.getElementById('prepayment-required').textContent = prepaymentRequired.toFixed(2);
                document.getElementById('prepayment-paid').textContent = prepaymentPaid.toFixed(2);
                document.getElementById('prepayment-remaining').textContent = prepaymentRemaining.toFixed(2);
                prepaymentInfo.classList.remove('hidden');
            }

            // Обновляем информацию об окончательном расчете
            const finalInfo = document.getElementById('final-info');
            if (finalRequired > 0) {
                document.getElementById('final-required').textContent = finalRequired.toFixed(2);
                document.getElementById('final-paid').textContent = finalPaid.toFixed(2);
                document.getElementById('final-remaining').textContent = finalRemaining.toFixed(2);
                finalInfo.classList.remove('hidden');
            }
        }

        // Инициализация при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            updatePaymentInfo();

            // Обновляем при изменении типа платежа
            document.getElementById('payment_type').addEventListener('change', updatePaymentInfo);
        });
    </script>

@endsection
