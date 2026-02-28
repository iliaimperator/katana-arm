@extends('layouts.app')

@section('title', 'Отчеты и аналитика')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Отчеты и аналитика</h1>

    <!-- Карточки с быстрым доступом -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <a href="{{ route('reports.general') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition duration-200">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg mr-4">
                    <span class="text-2xl">📊</span>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Общая статистика</h3>
                    <p class="text-gray-600 text-sm">Ключевые показатели бизнеса</p>
                </div>
            </div>
        </a>

        <a href="{{ route('reports.financial') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition duration-200">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg mr-4">
                    <span class="text-2xl">💰</span>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Финансовые отчеты</h3>
                    <p class="text-gray-600 text-sm">Доходы и платежи</p>
                </div>
            </div>
        </a>

        <a href="{{ route('reports.orders') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition duration-200">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg mr-4">
                    <span class="text-2xl">📋</span>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Заказ-наряды</h3>
                    <p class="text-gray-600 text-sm">Статистика по заказам</p>
                </div>
            </div>
        </a>

        <a href="{{ route('reports.services') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition duration-200">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 rounded-lg mr-4">
                    <span class="text-2xl">🛠️</span>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Услуги</h3>
                    <p class="text-gray-600 text-sm">Популярные услуги</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Быстрая статистика -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @php
            $totalOrders = \App\Models\WorkOrder::count();
            $activeOrders = \App\Models\WorkOrder::whereIn('status', ['accepted', 'in_progress', 'waiting_parts'])->count();
            $completedOrders = \App\Models\WorkOrder::where('status', 'completed')->count();
            $totalRevenue = \App\Models\WorkOrder::where('status', 'completed')->sum('final_cost');
        @endphp

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-blue-600">{{ $totalOrders }}</div>
            <div class="text-sm text-gray-600">Всего заказов</div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-orange-600">{{ $activeOrders }}</div>
            <div class="text-sm text-gray-600">Активных заказов</div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-green-600">{{ $completedOrders }}</div>
            <div class="text-sm text-gray-600">Завершенных заказов</div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-purple-600">{{ number_format($totalRevenue, 0, '', ' ') }} ₽</div>
            <div class="text-sm text-gray-600">Общий доход</div>
        </div>
    </div>

    <!-- Последние завершенные заказы -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-xl font-semibold text-gray-800">Последние завершенные заказы</h2>
        </div>
        <div class="p-6">
            @php
                $recentOrders = \App\Models\WorkOrder::with(['car.client'])
                    ->where('status', 'completed')
                    ->orderBy('updated_at', 'desc')
                    ->limit(5)
                    ->get();
            @endphp

            @if($recentOrders->count() > 0)
                <div class="space-y-4">
                    @foreach($recentOrders as $order)
                    <div class="flex justify-between items-center p-4 border border-gray-200 rounded-lg">
                        <div>
                            <div class="font-medium text-gray-800">{{ $order->order_number }}</div>
                            <div class="text-sm text-gray-600">
                                {{ $order->car->client->full_name }} - {{ $order->car->brand }} {{ $order->car->model }}
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-green-600">{{ number_format($order->final_cost, 2) }} ₽</div>
                            <div class="text-sm text-gray-500">{{ $order->updated_at->format('d.m.Y') }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Нет завершенных заказов</p>
            @endif
        </div>
    </div>
</div>
@endsection
