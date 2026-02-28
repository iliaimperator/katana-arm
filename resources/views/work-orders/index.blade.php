@extends('layouts.app')

@section('title', 'Заказ-наряды')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Заказ-наряды</h1>
        <a href="{{ route('work-orders.create') }}"
            class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition duration-200">
            + Создать заказ-наряд
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Фильтры по статусу -->
    <div class="mb-6 bg-white rounded-lg shadow p-4">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('work-orders.index') }}"
                class="px-3 py-1 rounded-full text-sm font-medium {{ !request('status') ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Все
            </a>
            @foreach (\App\Models\WorkOrder::getStatuses() as $key => $status)
                <a href="{{ route('work-orders.index', ['status' => $key]) }}"
                    class="px-3 py-1 rounded-full text-sm font-medium {{ request('status') == $key ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    {{ $status }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">№ Заказа</th>
                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Клиент / Автомобиль</th>
                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Дата приема</th>
                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Статус</th>
                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Стоимость</th>
                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Действия</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 text-sm font-mono text-gray-800">
                            {{ $order->order_number }}
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-800">
                            <div class="font-medium">{{ $order->car->client->full_name }}</div>
                            <div class="text-xs text-gray-500">
                                {{ $order->car->brand }} {{ $order->car->model }}
                                ({{ $order->car->license_plate }})
                            </div>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-800">
                            {{ $order->reception_date ? $order->reception_date->format('d.m.Y') : 'Не указана' }}
                        </td>
                        <td class="py-3 px-4 text-sm">
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
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$order->status] }}">
                                {{ \App\Models\WorkOrder::getStatuses()[$order->status] }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-800">
                            <div class="font-semibold">{{ number_format($order->total_cost, 2) }} ₽</div>
                            <div class="text-xs text-gray-500">
                                Предоплата: {{ number_format($order->prepayment_amount, 2) }} ₽
                            </div>
                        </td>
                        <td class="py-3 px-4 text-sm">
                            <a href="{{ route('work-orders.show', $order->order_id) }}"
                                class="text-blue-600 hover:text-blue-800 mr-3">Просмотр</a>
                            <!-- Новая кнопка печати -->
                            <a href="{{ route('work-orders.print', $order->order_id) }}" target="_blank"
                                class="text-purple-600 hover:text-purple-800 mr-3">
                                Печать
                            </a>
                            <a href="{{ route('work-orders.edit', $order->order_id) }}"
                                class="text-green-600 hover:text-green-800 mr-3">Редактировать</a>
                            <form action="{{ route('work-orders.destroy', $order->order_id) }}" method="POST"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800"
                                    onclick="return confirm('Удалить этот заказ-наряд?')">Удалить</button>
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
            <div class="text-sm text-gray-500">Всего заказов</div>
            <div class="text-2xl font-bold text-gray-800">{{ $orders->count() }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">В работе</div>
            <div class="text-2xl font-bold text-yellow-600">{{ $orders->where('status', 'in_progress')->count() }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Ожидают запчасти</div>
            <div class="text-2xl font-bold text-orange-600">{{ $orders->where('status', 'waiting_parts')->count() }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Общая стоимость</div>
            <div class="text-2xl font-bold text-gray-800">{{ number_format($orders->sum('total_cost'), 2) }} ₽</div>
        </div>
    </div>
@endsection
