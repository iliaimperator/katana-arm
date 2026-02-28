<!-- Сайдбар -->
<div class="sidebar w-64 text-white flex flex-col">
    <!-- Логотип -->
    <div class="p-6 border-b border-blue-700">
        <h1 class="text-xl font-bold">Автосервис "Катана"</h1>
        <p class="text-sm text-blue-200 mt-1">АРМ Администратора</p>
    </div>

    <!-- Навигация -->
    <nav class="flex-1 mt-4">
        <!-- Главная -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center py-3 px-6 transition duration-200 {{ request()->routeIs('dashboard') ? 'active' : 'hover:bg-blue-600' }}">
            <span class="mr-3 text-lg">📊</span>
            <span class="font-medium">Главная панель</span>
        </a>

        <!-- Раздел: Справочники -->
        <div class="px-6 py-2 text-xs font-semibold text-blue-300 uppercase tracking-wider mt-4">
            Справочники
        </div>

        <!-- Услуги -->
        <a href="{{ route('services.index') }}"
           class="flex items-center py-3 px-6 transition duration-200 {{ request()->routeIs('services.*') ? 'active' : 'hover:bg-blue-600' }}">
            <span class="mr-3">🛠️</span>
            <span>Управление услугами</span>
        </a>

        <!-- Запчасти -->
        <a href="#" class="flex items-center py-3 px-6 text-blue-200 hover:bg-blue-600 hover:text-white transition duration-200">
            <span class="mr-3">🔧</span>
            <span>Каталог запчастей</span>
        </a>

        <!-- Раздел: Основные операции -->
        <div class="px-6 py-2 text-xs font-semibold text-blue-300 uppercase tracking-wider mt-4">
            Основные операции
        </div>

        <!-- Клиенты -->
        <a href="#" class="flex items-center py-3 px-6 text-blue-200 hover:bg-blue-600 hover:text-white transition duration-200">
            <span class="mr-3">👥</span>
            <span>Клиенты и автомобили</span>
        </a>

        <!-- Заказ-наряды -->
        <a href="#" class="flex items-center py-3 px-6 text-blue-200 hover:bg-blue-600 hover:text-white transition duration-200">
            <span class="mr-3">📋</span>
            <span>Заказ-наряды</span>
        </a>

        <!-- Платежи -->
        <a href="#" class="flex items-center py-3 px-6 text-blue-200 hover:bg-blue-600 hover:text-white transition duration-200">
            <span class="mr-3">💰</span>
            <span>Управление платежами</span>
        </a>

        <!-- Отчеты -->
        <a href="#" class="flex items-center py-3 px-6 text-blue-200 hover:bg-blue-600 hover:text-white transition duration-200">
            <span class="mr-3">📈</span>
            <span>Отчеты и аналитика</span>
        </a>
    </nav>

    <!-- Информация о пользователе -->
    <div class="p-4 border-t border-blue-700 bg-blue-800">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center mr-3">
                <span class="text-white font-semibold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
            </div>
            <div>
                <div class="text-sm font-medium text-white">{{ Auth::user()->name }}</div>
                <div class="text-xs text-blue-300">{{ Auth::user()->email }}</div>
            </div>
        </div>
    </div>
</div>
