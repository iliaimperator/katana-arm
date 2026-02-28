@extends('layouts.app')

@section('title', 'Каталог запчастей')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-800">Каталог запчастей</h1>
    <a href="{{ route('spare-parts.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition duration-200">
        + Добавить запчасть
    </a>
</div>

@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Артикул</th>
                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Наименование</th>
                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Категория</th>
                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Цена закупки</th>
                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Цена продажи</th>
                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">На складе</th>
                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Действия</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach ($parts as $part)
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 text-sm font-mono text-gray-800">{{ $part->article_number }}</td>
                <td class="py-3 px-4 text-sm text-gray-800">{{ $part->part_name }}</td>
                <td class="py-3 px-4 text-sm text-gray-800">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $part->category ?? 'Не указана' }}
                    </span>
                </td>
                <td class="py-3 px-4 text-sm text-gray-800">{{ number_format($part->purchase_price, 2) }} ₽</td>
                <td class="py-3 px-4 text-sm text-gray-800">{{ number_format($part->selling_price, 2) }} ₽</td>
                <td class="py-3 px-4 text-sm text-gray-800">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                        {{ $part->stock_quantity <= $part->min_stock ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                        {{ $part->stock_quantity }} шт.
                    </span>
                </td>
                <td class="py-3 px-4 text-sm">
                    <a href="{{ route('spare-parts.edit', $part->part_id) }}" class="text-blue-600 hover:text-blue-800 mr-3">Редактировать</a>
                    <form action="{{ route('spare-parts.destroy', $part->part_id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Удалить эту запчасть?')">Удалить</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Статистика по складу -->
<div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
    <div class="bg-white rounded-lg shadow p-4">
        <div class="text-sm text-gray-500">Всего запчастей</div>
        <div class="text-2xl font-bold text-gray-800">{{ $parts->count() }}</div>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <div class="text-sm text-gray-500">Общая стоимость</div>
        <div class="text-2xl font-bold text-gray-800">
            {{ number_format($parts->sum(function($part) { return $part->purchase_price * $part->stock_quantity; }), 2) }} ₽
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <div class="text-sm text-gray-500">Низкий запас</div>
        <div class="text-2xl font-bold text-red-600">
            {{ $parts->where('stock_quantity', '<=', \DB::raw('min_stock'))->count() }}
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <div class="text-sm text-gray-500">Категорий</div>
        <div class="text-2xl font-bold text-gray-800">
            {{ $parts->pluck('category')->unique()->filter()->count() }}
        </div>
    </div>
</div>
@endsection
