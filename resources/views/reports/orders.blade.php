@extends('layouts.app')

@section('title', 'Отчеты по заказ-нарядам')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Отчеты по заказ-нарядам</h1>
        <a href="{{ route('reports.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded transition duration-200">
            Назад
        </a>
    </div>

    <!-- Фильтры -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Параметры отчета</h3>
        <form method="GET" action="{{ route('reports.orders') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Начальная дата</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Конечная дата</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Статус</label>
                <select name="status" id="status"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Все статусы</option>
                    @foreach(\App\Models\WorkOrder::getStatuses() as $key => $value)
                        <option value="{{ $key }}" {{ $status == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition duration-200">
                    Сформировать отчет
                </button>
            </div>
        </form>
    </div>

    <!-- Статистика по статусам -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4 mb-8">
        @foreach($statusStats as $key => $count)
            @if($key != 'all')
                @php
                    $statusColors = [
                        'draft' => 'bg-gray-100 text-gray-800',
                        'accepted' => 'bg-blue-100 text-blue-800',
                        'in_progress' => 'bg-yellow-100 text-yellow-800',
                        'waiting_parts' => 'bg-orange-100 text-orange-800',
                        'ready' => 'bg-green-100 text-green-800',
                        'completed' => 'bg-purple-100 text-purple-800',
                        'cancelled' => 'bg-red-100 text-red-800'
                    ];
                @endphp
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <div class="text-2xl font-bold text-gray-800">{{ $count }}</div>
                    <div class="text-xs text-gray-600 mt-1">{{ \App\Models\WorkOrder::getStatuses()[$key] }}</div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Сводная информация -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-blue-600">{{ $statusStats['all'] }}</div>
            <div class="text-sm text-gray-600">Всего заказов</div>
            <div class="text-xs text-gray-500 mt-1">За выбранный период</div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-green-600">
                {{ number_format($orders->where('status', 'completed')->sum('final_cost'), 0, '', ' ') }} ₽
            </div>
            <div class="text-sm text-gray-600">Доход с завершенных</div>
            <div class="text-xs text-gray-500 mt-1">Только завершенные заказы</div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-purple-600">
                {{ $orders->where('status', 'completed')->count() }}
            </div>
            <div class="text-sm text-gray-600">Завершенных заказов</div>
            <div class="text-xs text-gray-500 mt-1">Успешно выполнено</div>
        </div>
    </div>

    <!-- Список заказов -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Список заказов</h3>
                <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                    {{ $orders->count() }} заказов
                </span>
            </div>
            <p class="text-sm text-gray-600 mt-1">
                Период: {{ \Carbon\Carbon::parse($startDate)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d.m.Y') }}
                @if($status != 'all')
                    • Статус: {{ \App\Models\WorkOrder::getStatuses()[$status] }}
                @endif
            </p>
        </div>

        <div class="p-6">
            @if($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">№ Заказа</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Клиент</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Автомобиль</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Дата приема</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Статус</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Стоимость</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-mono text-gray-800">
                                    {{ $order->order_number }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-800">
                                    {{ $order->car->client->full_name }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-800">
                                    {{ $order->car->brand }} {{ $order->car->model }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-800">
                                    {{ $order->reception_date->format('d.m.Y') }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    @php
                                        $statusColors = [
                                            'draft' => 'bg-gray-100 text-gray-800',
                                            'accepted' => 'bg-blue-100 text-blue-800',
                                            'in_progress' => 'bg-yellow-100 text-yellow-800',
                                            'waiting_parts' => 'bg-orange-100 text-orange-800',
                                            'ready' => 'bg-green-100 text-green-800',
                                            'completed' => 'bg-purple-100 text-purple-800',
                                            'cancelled' => 'bg-red-100 text-red-800'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$order->status] }}">
                                        {{ \App\Models\WorkOrder::getStatuses()[$order->status] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-800">
                                    {{ number_format($order->total_cost, 2) }} ₽
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Нет заказов по выбранным критериям</p>
            @endif
        </div>
    </div>
</div>
@endsection
