<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ __('Usuarios') }}
        </h2>
    </x-slot>

    <main class="flex-1 p-8 bg-gray-50 dark:bg-background-dark/20 min-h-screen">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- 🔹 Botón Crear Usuario --}}
            <div class="mb-6 flex justify-end">
                <a href="{{ route('users.create') }}"
                   class="inline-flex items-center bg-primary hover:bg-primary/90 text-white font-semibold py-2 px-4 rounded-lg shadow-sm transition">
                    + Crear Usuario
                </a>
            </div>

            {{-- 🔹 Tabla de Usuarios --}}
            <div class="bg-white dark:bg-background-dark/50 rounded-xl border border-primary/20 dark:border-primary/30 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-primary/5 dark:bg-primary/10 text-gray-600 dark:text-gray-300 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3 font-medium text-left">Nombre</th>
                                <th class="px-6 py-3 font-medium text-left">Email</th>
                                <th class="px-6 py-3 font-medium text-center">Rol</th>
                                <th class="px-6 py-3 font-medium text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-primary/10 dark:divide-primary/20">
                            @foreach ($users as $user)
                                <tr class="hover:bg-primary/5 dark:hover:bg-primary/10 transition">
                                    <td class="px-6 py-4 text-gray-800 dark:text-gray-300">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-gray-800 dark:text-gray-300">{{ $user->email }}</td>
                                    <td class="px-6 py-4 text-center text-gray-700 dark:text-gray-300">
                                        @switch($user->rol)
                                            @case('administrador')
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">Administrador</span>
                                                @break
                                            @case('mesero')
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">Mesero</span>
                                                @break
                                            @case('cocinero')
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300">Cocinero</span>
                                                @break
                                            @case('cajero')
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300">Cajero</span>
                                                @break
                                            @default
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900/50 dark:text-gray-300">Desconocido</span>
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4 text-center space-x-2">
                                        {{-- Editar --}}
                                        <a href="{{ route('users.edit', $user) }}"
                                           class="inline-flex items-center px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg shadow-sm transition">
                                            Editar
                                        </a>

                                        {{-- Eliminar --}}
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-sm transition">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        @if($users->isEmpty())
                            <tr>
                                <td colspan="4" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">
                                    No hay usuarios registrados.
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

        </div>
    </main>
</x-app-layout>
