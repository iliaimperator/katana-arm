@extends('layouts.app')

@section('title', 'Финансовые отчеты')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Финансовые отчеты</h1>
        <a href="{{ route('reports.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded transition duration-200">
            Назад
        </a>
    </div>

    <!-- Фильтры -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Параметры отчета</h3>
        <form method="GET" action="{{ route('reports.financial') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
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

    <!-- Основные финансовые показатели -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-green-600">{{ number_format($revenue, 0, '', ' ') }} ₽</div>
            <div class="text-sm text-gray-600">Общий доход</div>
            <div class="text-xs text-gray-500 mt-1">За выбранный период</div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-blue-600">{{ number_format($paymentStats['total'], 0, '', ' ') }} ₽</div>
            <div class="text-sm text-gray-600">Всего платежей</div>
            <div class="text-xs text-gray-500 mt-1">Поступило на счет</div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-purple-600">{{ $topServices }}</div>
            <div class="text-sm text-gray-600">Оказано услуг</div>
            <div class="text-xs text-gray-500 mt-1">В завершенных заказах</div>
        </div>
    </div>

    <!-- Статистика по типам платежей -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- По типам платежей -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Платежи по типам</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center">
                        <span class="w-3 h-3 bg-blue-500 rounded-full mr-3"></span>
                        <span class="font-medium text-gray-800">Предоплата</span>
                    </div>
                    <div class="text-lg font-bold text-blue-600">
                        {{ number_format($paymentStats['prepayment'], 2) }} ₽
                    </div>
                </div>

                <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <span class="w-3 h-3 bg-green-500 rounded-full mr-3"></span>
                        <span class="font-medium text-gray-800">Окончательный расчет</span>
                    </div>
                    <div class="text-lg font-bold text-green-600">
                        {{ number_format($paymentStats['final'], 2) }} ₽
                    </div>
                </div>

                <div class="flex justify-between items-center pt-3 border-t">
                    <div class="font-semibold text-gray-800">Итого</div>
                    <div class="text-xl font-bold text-purple-600">
                        {{ number_format($paymentStats['total'], 2) }} ₽
                    </div>
                </div>
            </div>
        </div>

        <!-- По способам оплаты -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Платежи по способам оплаты</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <span class="w-3 h-3 bg-gray-500 rounded-full mr-3"></span>
                        <span class="font-medium text-gray-800">Наличные</span>
                    </div>
                    <div class="text-lg font-bold text-gray-600">
                        {{ number_format($paymentStats['cash'], 2) }} ₽
                    </div>
                </div>

                <div class="flex justify-between items-center p-3 bg-indigo-50 rounded-lg">
                    <div class="flex items-center">
                        <span class="w-3 h-3 bg-indigo-500 rounded-full mr-3"></span>
                        <span class="font-medium text-gray-800">Карта</span>
                    </div>
                    <div class="text-lg font-bold text-indigo-600">
                        {{ number_format($paymentStats['card'], 2) }} ₽
                    </div>
                </div>

                <div class="flex justify-between items-center p-3 bg-teal-50 rounded-lg">
                    <div class="flex items-center">
                        <span class="w-3 h-3 bg-teal-500 rounded-full mr-3"></span>
                        <span class="font-medium text-gray-800">Перевод</span>
                    </div>
                    <div class="text-lg font-bold text-teal-600">
                        {{ number_format($paymentStats['transfer'], 2) }} ₽
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Детализация платежей -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Детализация платежей</h3>
            <p class="text-sm text-gray-600 mt-1">Период: {{ \Carbon\Carbon::parse($startDate)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d.m.Y') }}</p>
        </div>
        <div class="p-6">
            @php
                $payments = \App\Models\Payment::with('order')
                    ->whereBetween('payment_date', [$startDate, $endDate])
                    ->orderBy('payment_date', 'desc')
                    ->get();
            @endphp

            @if($payments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Дата</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Заказ</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Тип</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Способ</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Сумма</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($payments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-800">
                                    {{ $payment->payment_date->format('d.m.Y') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-800">
                                    {{ $payment->order->order_number }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        {{ $payment->type == 'prepayment' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        {{ \App\Models\Payment::getTypes()[$payment->type] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-800">
                                    {{ \App\Models\Payment::getMethods()[$payment->method] }}
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-green-600">
                                    {{ number_format($payment->amount, 2) }} ₽
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-sm font-semibold text-gray-800 text-right">Итого:</td>
                                <td class="px-4 py-3 text-sm font-bold text-green-600">
                                    {{ number_format($payments->sum('amount'), 2) }} ₽
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Нет платежей за выбранный период</p>
            @endif
        </div>
    </div>
</div>
@endsection
