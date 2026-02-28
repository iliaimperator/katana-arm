@extends('layouts.app')

@section('title', 'Статистика по услугам')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Статистика по услугам</h1>
        <a href="{{ route('reports.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded transition duration-200">
            Назад
        </a>
    </div>

    <!-- Фильтры -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Параметры отчета</h3>
        <form method="GET" action="{{ route('reports.services') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
            <div class="flex items-end">
                <button type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition duration-200">
                    Сформировать отчет
                </button>
            </div>
        </form>
    </div>

    <!-- Сводная информация -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-blue-600">{{ $popularServices->count() }}</div>
            <div class="text-sm text-gray-600">Услуг оказано</div>
            <div class="text-xs text-gray-500 mt-1">Разных наименований</div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-green-600">
                {{ number_format($popularServices->sum('total_revenue'), 0, '', ' ') }} ₽
            </div>
            <div class="text-sm text-gray-600">Общий доход с услуг</div>
            <div class="text-xs text-gray-500 mt-1">За выбранный период</div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-purple-600">{{ $popularServices->sum('total_quantity') }}</div>
            <div class="text-sm text-gray-600">Всего оказано услуг</div>
            <div class="text-xs text-gray-500 mt-1">Суммарное количество</div>
        </div>
    </div>

    <!-- Популярные услуги -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Популярные услуги</h3>
            <p class="text-sm text-gray-600 mt-1">Рейтинг услуг по количеству оказаний</p>
        </div>
        <div class="p-6">
            @if($popularServices->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Услуга</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Тип</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Кол-во раз</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Общее кол-во</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Доход</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Средний чек</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($popularServices as $service)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">
                                    {{ $service->service_name }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ $service->service_type }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-800">
                                    {{ $service->usage_count }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-800">
                                    {{ $service->total_quantity }}
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-green-600">
                                    {{ number_format($service->total_revenue, 2) }} ₽
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-800">
                                    {{ number_format($service->total_revenue / $service->usage_count, 2) }} ₽
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Нет данных об оказанных услугах за выбранный период</p>
            @endif
        </div>
    </div>

    <!-- Статистика по типам услуг -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Статистика по типам услуг</h3>
            <p class="text-sm text-gray-600 mt-1">Группировка по категориям услуг</p>
        </div>
        <div class="p-6">
            @if($serviceTypes->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Тип услуги</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Кол-во оказаний</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Доход</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Доля в доходах</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @php
                                $totalRevenue = $serviceTypes->sum('total_revenue');
                            @endphp
                            @foreach($serviceTypes as $type)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">
                                    {{ $type->service_type }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-800">
                                    {{ $type->usage_count }}
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-green-600">
                                    {{ number_format($type->total_revenue, 2) }} ₽
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-800">
                                    @if($totalRevenue > 0)
                                        {{ number_format(($type->total_revenue / $totalRevenue) * 100, 1) }}%
                                    @else
                                        0%
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-800">Итого</td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-800">
                                    {{ $serviceTypes->sum('usage_count') }}
                                </td>
                                <td class="px-4 py-3 text-sm font-bold text-green-600">
                                    {{ number_format($totalRevenue, 2) }} ₽
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-800">100%</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Нет данных по типам услуг за выбранный период</p>
            @endif
        </div>
    </div>
</div>
@endsection
