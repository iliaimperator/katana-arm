@extends('layouts.app')

@section('title', 'Общая статистика')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Общая статистика</h1>
        <a href="{{ route('reports.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded transition duration-200">
            Назад
        </a>
    </div>

    <!-- Основная статистика -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-blue-600">{{ $stats['total_clients'] }}</div>
            <div class="text-sm text-gray-600">Клиентов</div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-green-600">{{ $stats['total_cars'] }}</div>
            <div class="text-sm text-gray-600">Автомобилей</div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-purple-600">{{ $stats['total_orders'] }}</div>
            <div class="text-sm text-gray-600">Всего заказов</div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-orange-600">{{ number_format($stats['total_revenue'], 0, '', ' ') }} ₽</div>
            <div class="text-sm text-gray-600">Общий доход</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-indigo-600">{{ $stats['active_orders'] }}</div>
            <div class="text-sm text-gray-600">Активных заказов</div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-teal-600">{{ $stats['completed_orders'] }}</div>
            <div class="text-sm text-gray-600">Завершенных заказов</div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-red-600">{{ $stats['total_services'] }}</div>
            <div class="text-sm text-gray-600">Услуг в каталоге</div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-yellow-600">{{ number_format($stats['avg_order_value'], 0, '', ' ') }} ₽</div>
            <div class="text-sm text-gray-600">Средний чек</div>
        </div>
    </div>

    <!-- Статистика по месяцам -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Статистика по месяцам ({{ date('Y') }} год)</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Месяц</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Заказов</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Доход</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Средний чек</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($monthlyStats as $month => $data)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-800">{{ $data['month_name'] }}</td>
                        <td class="px-4 py-3 text-sm text-gray-800">{{ $data['orders'] }}</td>
                        <td class="px-4 py-3 text-sm text-gray-800">{{ number_format($data['revenue'], 0, '', ' ') }} ₽</td>
                        <td class="px-4 py-3 text-sm text-gray-800">
                            {{ $data['orders'] > 0 ? number_format($data['revenue'] / $data['orders'], 0, '', ' ') : 0 }} ₽
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- <!-- Графики (можно добавить позже с Chart.js) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Заказы по месяцам</h3>
            <div class="h-64 flex items-center justify-center text-gray-500">
                <!-- Здесь будет график -->
                График заказов (можно подключить Chart.js)
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Доход по месяцам</h3>
            <div class="h-64 flex items-center justify-center text-gray-500">
                <!-- Здесь будет график -->
                График дохода (можно подключить Chart.js)
            </div>
        </div>
    </div> --}}
</div>
@endsection
