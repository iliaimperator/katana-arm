<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'АРМ Автосервис "Катана"')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .sidebar {
            background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 100%);
        }

        .nav-active {
            background-color: #3b82f6;
            border-left: 4px solid #60a5fa;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Сайдбар -->
        <div class="sidebar w-64 text-white flex flex-col">
            <!-- Логотип -->
            <div class="p-6 border-b border-blue-700">
                <h1 class="text-xl font-bold">Автосервис "Катана"</h1>
                <p class="text-sm text-blue-200 mt-1">АРМ Администратора</p>
            </div>

            <!-- Навигация -->
            <nav class="flex-1 mt-4">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center py-3 px-6 transition duration-200 {{ request()->routeIs('dashboard') ? 'nav-active' : 'hover:bg-blue-600' }}">
                    <span class="mr-3 text-lg">📊</span>
                    <span class="font-medium">Главная панель</span>
                </a>

                <div class="px-6 py-2 text-xs font-semibold text-blue-300 uppercase tracking-wider mt-4">
                    Справочники
                </div>

                <a href="{{ route('services.index') }}"
                    class="flex items-center py-3 px-6 transition duration-200 {{ request()->routeIs('services.*') ? 'nav-active' : 'hover:bg-blue-600' }}">
                    <span class="mr-3">🛠️</span>
                    <span>Управление услугами</span>
                </a>

                <a href="{{ route('spare-parts.index') }}"
                    class="flex items-center py-3 px-6 transition duration-200 {{ request()->routeIs('spare-parts.*') ? 'nav-active' : 'hover:bg-blue-600' }}">
                    <span class="mr-3">🔧</span>
                    <span>Каталог запчастей</span>
                </a>

                <div class="px-6 py-2 text-xs font-semibold text-blue-300 uppercase tracking-wider mt-4">
                    Основные операции
                </div>

                <a href="{{ route('clients.index') }}"
                    class="flex items-center py-3 px-6 transition duration-200 {{ request()->routeIs('clients.*') || request()->routeIs('cars.*') ? 'nav-active' : 'hover:bg-blue-600' }}">
                    <span class="mr-3">👥</span>
                    <span>Клиенты и автомобили</span>
                </a>

                <a href="{{ route('work-orders.index') }}"
                    class="flex items-center py-3 px-6 transition duration-200 {{ request()->routeIs('work-orders.*') ? 'nav-active' : 'hover:bg-blue-600' }}">
                    <span class="mr-3">📋</span>
                    <span>Заказ-наряды</span>
                </a>



                <a href="{{ route('reports.index') }}"
                    class="flex items-center py-3 px-6 transition duration-200 {{ request()->routeIs('reports.*') ? 'nav-active' : 'hover:bg-blue-600' }}">
                    <span class="mr-3">📈</span>
                    <span>Отчеты и аналитика</span>
                </a>
            </nav>

            <!-- Информация о пользователе -->
            <div class="p-4 border-t border-blue-700 bg-blue-800">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center mr-3">
                        <span
                            class="text-white font-semibold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-white">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-blue-300">{{ Auth::user()->email }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Основной контент -->
        <div class="flex-1 flex flex-col">
            <!-- Хедер -->
            <header class="bg-white shadow-sm border-b">
                <div class="flex justify-between items-center py-4 px-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">
                            @yield('title', 'Автосервис "Катана"')
                        </h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="text-gray-600 hover:text-gray-900 text-sm font-medium bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded transition duration-200">
                                Выйти
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Главный контент -->
            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>
