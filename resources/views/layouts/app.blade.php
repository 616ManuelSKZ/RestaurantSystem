<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Restaurante') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
     <script src="https://cdn.lordicon.com/lordicon.js"></script>

    <!-- Styles and Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-800">
    <div class="min-h-screen flex">

        {{-- Sidebar Izquierdo --}}
        <aside class="w-64 bg-white shadow-md flex flex-col">
            <div class="p-6 border-b">
                <h1 class="text-xl font-bold text-gray-700">{{ config('app.name', 'Restaurante') }}</h1>
            </div>
            
            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700">🏠 Dashboard</a>
                <a href="{{ route('menus.index') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700">🍽️ Platillos</a>
                <a href="{{ route('ordenes.index') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700">📝 Órdenes</a>
                <a href="{{ route('facturas.index') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700">💳 Facturas</a>
                <a href="{{ route('users.index') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700">👥 Usuarios</a>
            </nav>

            <div class="p-4 border-t">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 rounded-lg hover:bg-gray-100 text-red-600">
                        🚪 Cerrar sesión
                    </button>
                </form>
            </div>
        </aside>

        {{-- Contenido principal --}}
        <div class="flex-1 flex flex-col">
            
            {{-- Header (opcional, como barra superior) --}}
            @isset($header)
                <header class="bg-white shadow">
                    <div class="px-6 py-4">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            {{-- Contenido --}}
            <main class="flex-1 p-6">
                {{ $slot }}
            </main>

            {{-- Footer --}}
            <footer class="bg-white border-t">
                <div class="px-6 py-4 flex justify-between text-sm text-gray-600">
                    <p>&copy; {{ date('Y') }} {{ config('app.name', 'Restaurante') }}. Todos los derechos reservados.</p>
                    <p>Desarrollado por Nobody</p>
                </div>
            </footer>
        </div>
    </div>
</body>
</html>
