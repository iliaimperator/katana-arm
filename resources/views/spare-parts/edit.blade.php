@extends('layouts.app')

@section('title', 'Редактирование запчасти')

@section('content')
<div class="max-w-4xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Редактирование запчасти</h1>

    <form action="{{ route('spare-parts.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="mb-4">
                <label for="article_number" class="block text-sm font-medium text-gray-700 mb-2">Артикул *:</label>
                <input type="text" name="article_number" id="article_number" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    value="{{ old('article_number') }}"
                    placeholder="Например: ABC-123">
                @error('article_number')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="part_name" class="block text-sm font-medium text-gray-700 mb-2">Наименование *:</label>
                <input type="text" name="part_name" id="part_name" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    value="{{ old('part_name') }}"
                    placeholder="Например: Поршень двигателя">
                @error('part_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Описание:</label>
            <textarea name="description" id="description" rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Описание запчасти">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="mb-4">
                <label for="purchase_price" class="block text-sm font-medium text-gray-700 mb-2">Цена закупки *:</label>
                <input type="number" step="0.01" name="purchase_price" id="purchase_price" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    value="{{ old('purchase_price') }}"
                    placeholder="0.00">
                @error('purchase_price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="selling_price" class="block text-sm font-medium text-gray-700 mb-2">Цена продажи *:</label>
                <input type="number" step="0.01" name="selling_price" id="selling_price" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    value="{{ old('selling_price') }}"
                    placeholder="0.00">
                @error('selling_price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Категория:</label>
                <select name="category" id="category"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Выберите категорию</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
                @error('category')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="mb-4">
                <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-2">Количество на складе *:</label>
                <input type="number" name="stock_quantity" id="stock_quantity" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    value="{{ old('stock_quantity', 0) }}"
                    min="0">
                @error('stock_quantity')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="min_stock" class="block text-sm font-medium text-gray-700 mb-2">Минимальный запас *:</label>
                <input type="number" name="min_stock" id="min_stock" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    value="{{ old('min_stock', 0) }}"
                    min="0">
                @error('min_stock')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-6">
            <label for="supplier" class="block text-sm font-medium text-gray-700 mb-2">Поставщик:</label>
            <input type="text" name="supplier" id="supplier"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                value="{{ old('supplier') }}"
                placeholder="Название поставщика">
            @error('supplier')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('spare-parts.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded transition duration-200">
                Назад к списку
            </a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition duration-200">
                Обновить запчасть
            </button>
        </div>
    </form>
</div>
@endsection
