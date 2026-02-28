@extends('layouts.app')

@section('title', 'Главная панель управления')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-2">Добро пожаловать в АРМ Автосервиса "Катана"!</h1>
    <p class="text-gray-600">Здесь отображается краткая статистика и основные показатели работы автосервиса.</p>
</div>

<!-- Статистика -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    @php
        use App\Models\WorkOrder;
        use App\Models\Client;
        use App\Models\Car;
        use App\Models\Service;

        $totalOrders = WorkOrder::count();
        $activeOrders = WorkOrder::whereIn('status', ['accepted', 'in_progress', 'waiting_parts'])->count();
        $waitingPartsOrders = WorkOrder::where('status', 'waiting_parts')->count();
        $completedOrders = WorkOrder::where('status', 'completed')->count();
        $totalClients = Client::count();
        $totalCars = Car::count();
        $totalServices = Service::count();
        $totalRevenue = WorkOrder::where('status', 'completed')->sum('final_cost');
    @endphp

    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-lg">
                <span class="text-2xl">📋</span>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500">Активные заказы</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $activeOrders }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-lg">
                <span class="text-2xl">✅</span>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500">Завершенные заказы</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $completedOrders }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-100 rounded-lg">
                <span class="text-2xl">⏳</span>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500">Ожидают запчасти</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $waitingPartsOrders }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
        <div class="flex items-center">
            <div class="p-3 bg-purple-100 rounded-lg">
                <span class="text-2xl">💰</span>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500">Общий доход</h3>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($totalRevenue, 0, '', ' ') }} ₽</p>
            </div>
        </div>
    </div>
</div>

<!-- Дополнительная статистика -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-indigo-100 rounded-lg">
                <span class="text-2xl">👥</span>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500">Всего клиентов</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $totalClients }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-teal-100 rounded-lg">
                <span class="text-2xl">🚗</span>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500">Автомобилей</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $totalCars }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-orange-100 rounded-lg">
                <span class="text-2xl">🛠️</span>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500">Услуг в каталоге</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $totalServices }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Быстрые действия -->
<div class="bg-white rounded-lg shadow p-6 mb-8">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Быстрые действия</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('work-orders.create') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition duration-200">
            <span class="text-2xl mr-3">📋</span>
            <div>
                <div class="font-medium text-gray-800">Создать заказ</div>
                <div class="text-sm text-gray-500">Новый заказ-наряд</div>
            </div>
        </a>

        <a href="{{ route('clients.create') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 hover:border-green-300 transition duration-200">
            <span class="text-2xl mr-3">👥</span>
            <div>
                <div class="font-medium text-gray-800">Добавить клиента</div>
                <div class="text-sm text-gray-500">Новый клиент</div>
            </div>
        </a>

        <a href="{{ route('services.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition duration-200">
            <span class="text-2xl mr-3">🛠️</span>
            <div>
                <div class="font-medium text-gray-800">Управление услугами</div>
                <div class="text-sm text-gray-500">Каталог услуг</div>
            </div>
        </a>

        <a href="{{ route('reports.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-orange-50 hover:border-orange-300 transition duration-200">
            <span class="text-2xl mr-3">📊</span>
            <div>
                <div class="font-medium text-gray-800">Отчеты</div>
                <div class="text-sm text-gray-500">Статистика и аналитика</div>
            </div>
        </a>
    </div>
</div>

<!-- Последние активные заказы -->
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Активные заказы</h2>
            <a href="{{ route('work-orders.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Все заказы →
            </a>
        </div>
    </div>
    <div class="p-6">
        @php
            $activeOrdersList = WorkOrder::with(['car.client'])
                ->whereIn('status', ['accepted', 'in_progress', 'waiting_parts'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        @endphp

        @if($activeOrdersList->count() > 0)
            <div class="space-y-4">
                @foreach($activeOrdersList as $order)
                <div class="flex justify-between items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="font-medium text-gray-800">{{ $order->order_number }}</div>
                            @php
                                $statusColors = [
                                    'accepted' => 'bg-blue-100 text-blue-800',
                                    'in_progress' => 'bg-yellow-100 text-yellow-800',
                                    'waiting_parts' => 'bg-orange-100 text-orange-800'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$order->status] }}">
                                {{ \App\Models\WorkOrder::getStatuses()[$order->status] }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ $order->car->client->full_name }} - {{ $order->car->brand }} {{ $order->car->model }}
                        </div>
                        @if($order->problem_description)
                            <div class="text-sm text-gray-500 mt-1">
                                {{ Str::limit($order->problem_description, 100) }}
                            </div>
                        @endif
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-bold text-gray-800">{{ number_format($order->total_cost, 2) }} ₽</div>
                        <div class="text-sm text-gray-500">{{ $order->created_at->format('d.m.Y') }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <div class="text-gray-400 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Нет активных заказов</h3>
                <p class="text-gray-500 mb-4">Все заказы завершены или находятся в других статусах.</p>
                <a href="{{ route('work-orders.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition duration-200 inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Создать первый заказ
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
