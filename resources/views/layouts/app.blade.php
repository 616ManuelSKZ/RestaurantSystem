<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Restaurante') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Scripts -->
    <script src="https://cdn.lordicon.com/lordicon.js"></script>

    <!-- Styles and Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-text-light dark:text-text-dark relative">
    @if (session('success'))
        <div 
            x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 4000)"
            class="fixed top-5 right-5 z-50 flex items-center gap-3 bg-green-600 text-white px-4 py-3 rounded-lg shadow-lg"
        >
            <!-- Icono -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>

            <!-- Mensaje -->
            <span class="text-sm font-medium">{{ session('success') }}</span>

            <!-- Botón cerrar -->
            <button @click="show = false" class="ml-3 hover:text-gray-200">
                ✕
            </button>
        </div>
    @endif
    @if (session('error'))
        <div 
            x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 5000)"
            class="fixed top-5 right-5 z-50 flex items-center gap-3 bg-red-600 text-white px-4 py-3 rounded-lg shadow-lg"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
            <button @click="show = false" class="ml-3 hover:text-gray-200">✕</button>
        </div>
    @endif


    <div class="min-h-screen flex">

        {{-- Sidebar Izquierdo --}}
        <aside class="w-64 bg-background-light dark:bg-background-dark shadow-md flex flex-col text-text-light dark:text-text-dark">
            {{-- Logo / Título --}}
            <div class="p-6 border-b border-subtle-light dark:border-subtle-dark flex items-center space-x-3">
                <i class="fas fa-utensils text-2xl text-primary"></i>
                <h1 class="text-xl font-bold">{{ config('app.name', 'Restaurante') }}</h1>
            </div>

            {{-- Usuario logueado --}}
            <div class="p-4 border-b border-subtle-light dark:border-subtle-dark flex items-center space-x-3">
                <i class="fas fa-user-circle text-2xl text-primary"></i>
                <div>
                    <p class="font-semibold">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-placeholder-light dark:text-placeholder-dark capitalize">{{ Auth::user()->rol }}</p>
                </div>
            </div>

            {{-- Menú --}}
            @php
                $rol = Auth::user()->rol ?? '';
            @endphp

            <nav class="flex-1 p-4 space-y-2" x-data="{ openOrdenes: false, openAjustes: false }">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}"
                   class="block px-4 py-2 rounded-lg hover:bg-subtle-light dark:hover:bg-subtle-dark transition">
                    🏠 Dashboard
                </a>

                <!-- Órdenes -->
                @if(in_array($rol, ['administrador','mesero','cajero']))
                    <button @click="openOrdenes = !openOrdenes"
                        class="w-full flex items-center justify-between px-4 py-2 rounded-lg hover:bg-subtle-light dark:hover:bg-subtle-dark transition">
                        📝 Órdenes
                        <svg :class="{ 'rotate-180': openOrdenes }" class="w-4 h-4 transition-transform"
                             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="openOrdenes" x-collapse class="ml-6 space-y-1">
                        @if(in_array($rol, ['administrador','mesero']))
                            <a href="{{ route('ordenes.create') }}" class="block px-4 py-2 rounded-lg hover:bg-subtle-light dark:hover:bg-subtle-dark">
                                ➕ Nueva Orden
                            </a>
                        @endif
                        @if(in_array($rol, ['administrador','cajero']))
                            <a href="{{ route('ordenes.index') }}" class="block px-4 py-2 rounded-lg hover:bg-subtle-light dark:hover:bg-subtle-dark">
                                📜 Historial Órdenes
                            </a>
                        @endif
                    </div>
                @endif

                <!-- Platillos -->
                @if(in_array($rol, ['administrador','cocinero']))
                    <a href="{{ route('menus.index') }}"
                       class="block px-4 py-2 rounded-lg hover:bg-subtle-light dark:hover:bg-subtle-dark transition">
                        🍽️ Platillos
                    </a>
                @endif

                <!-- Áreas de Mesas -->
                @if($rol === 'administrador')
                    <a href="{{ route('area_mesas.index') }}"
                       class="block px-4 py-2 rounded-lg hover:bg-subtle-light dark:hover:bg-subtle-dark transition">
                        🪑 Áreas de Mesas
                    </a>
                @endif

                <!-- Facturas -->
                @if(in_array($rol, ['administrador','cajero']))
                    <a href="{{ route('facturas.index') }}"
                       class="block px-4 py-2 rounded-lg hover:bg-subtle-light dark:hover:bg-subtle-dark transition">
                        💳 Facturas
                    </a>
                @endif

                <!-- Ajustes -->
                @if($rol === 'administrador')
                    <button @click="openAjustes = !openAjustes"
                        class="w-full flex items-center justify-between px-4 py-2 rounded-lg hover:bg-subtle-light dark:hover:bg-subtle-dark transition">
                        ⚙️ Ajustes
                        <svg :class="{ 'rotate-180': openAjustes }" class="w-4 h-4 transition-transform"
                             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="openAjustes" x-collapse class="ml-6 space-y-1">
                        <a href="{{ route('users.index') }}" class="block px-4 py-2 rounded-lg hover:bg-subtle-light dark:hover:bg-subtle-dark">
                            👥 Usuarios
                        </a>
                    </div>
                @endif
            </nav>

            {{-- Botón de cerrar sesión --}}
            <div class="p-4 border-t border-subtle-light dark:border-subtle-dark">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 rounded-lg hover:bg-subtle-light dark:hover:bg-subtle-dark text-red-600">
                        🚪 Cerrar sesión
                    </button>
                </form>
            </div>
        </aside>

        {{-- Contenido principal --}}
        <div class="flex-1 flex flex-col">
            {{-- Header --}}
            <header class="bg-background-light dark:bg-background-dark shadow">
                <div class="px-6 py-4 text-text-light dark:text-text-dark">
                    {{ $header ?? '' }}
                </div>
            </header>

            {{-- Contenido dinámico --}}
            <main class="flex-1 p-6">
                {{ $slot }}
            </main>

            {{-- Footer --}}
            <footer class="bg-background-light dark:bg-background-dark border-t border-subtle-light dark:border-subtle-dark">
                <div class="px-6 py-4 flex justify-between text-sm text-placeholder-light dark:text-placeholder-dark">
                    <p>&copy; {{ date('Y') }} {{ config('app.name', 'Restaurante') }}. Todos los derechos reservados.</p>
                    <p>Desarrollado por Nobody</p>
                </div>
            </footer>
        </div>
    </div>

    {{-- Botón de modo oscuro (fijo, visible siempre) --}}
    <button onclick="toggleDarkMode()" 
        class="fixed top-4 right-4 p-2 rounded-full bg-subtle-light dark:bg-subtle-dark hover:bg-primary/20 shadow-lg transition z-50">
        <span class="material-symbols-outlined text-text-light dark:text-text-dark">
            dark_mode
        </span>
    </button>

    {{-- Script de modo oscuro --}}
    <script>
        // Detectar modo oscuro del sistema al cargar
        if (localStorage.theme === 'dark' || 
            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }

        // Alternar modo oscuro manualmente
        function toggleDarkMode() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark')
                localStorage.theme = 'light'
            } else {
                document.documentElement.classList.add('dark')
                localStorage.theme = 'dark'
            }
        }
    </script>
</body>
</html>
