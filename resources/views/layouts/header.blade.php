<!-- Хедер -->
<header class="bg-white shadow-sm border-b">
    <div class="flex justify-between items-center py-4 px-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                @yield('title', 'Автосервис "Катана"')
            </h2>
        </div>
        <div class="flex items-center space-x-4">
            <!-- Кнопка выхода -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-600 hover:text-gray-900 text-sm font-medium bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded transition duration-200">
                    Выйти
                </button>
            </form>
        </div>
    </div>
</header>
