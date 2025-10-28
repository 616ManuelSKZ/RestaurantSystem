<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ __('Usuarios') }}
        </h2>
    </x-slot>

        @php
            $role = auth()->user()->rol;
        @endphp

    <main class="flex-1 p-8 bg-gray-50 dark:bg-background-dark/20 min-h-screen">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Botón Crear Usuario --}}
            <div class="mb-6 flex justify-end">
                <a href="{{ route('users.create') }}"
                   class="inline-flex items-center bg-primary hover:bg-primary/90 text-white font-semibold py-2 px-4 rounded-lg shadow-sm transition">
                    + Crear Usuario
                </a>
            </div>

            {{-- Tabla de Usuarios --}}
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
                                    <td class="px-6 py-4 text-center space-x-2"
                                        x-data="{ modalUserDelete: false, userId: '{{ $user->id }}', userName: '{{ $user->name }}' }">

                                        {{-- Botón Editar --}}
                                        <a href="{{ route('users.edit', $user) }}"
                                        class="inline-flex items-center px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg shadow-sm transition">
                                            Editar
                                        </a>

                                        {{-- Botón Eliminar (solo si no es admin o no es el mismo usuario) --}}
                                        @if($role !== 'administrador' || auth()->user()->id !== $user->id)
                                            <button type="button" @click="modalUserDelete = true"
                                                    class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-sm transition">
                                                Eliminar
                                            </button>

                                            {{-- Modal eliminar usuario --}}
                                            <div x-show="modalUserDelete"
                                                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                                x-cloak x-transition>
                                                <div class="bg-white dark:bg-background-dark/100 rounded-lg shadow-lg p-6 w-full max-w-md mx-4 relative">
                                                    <button @click="modalUserDelete = false"
                                                            class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200">
                                                        ✕
                                                    </button>

                                                    <h2 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-100">
                                                        Eliminar Usuario
                                                    </h2>

                                                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                                                        ¿Estás seguro de que deseas eliminar al usuario
                                                        <span class="font-semibold text-gray-900 dark:text-gray-100" x-text="userName"></span>?
                                                        <br> Esta acción no se puede deshacer.
                                                    </p>

                                                    <form :action="`/users/${userId}`" method="POST" class="flex justify-end gap-4">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="button" @click="modalUserDelete = false"
                                                                class="px-6 py-2 rounded-lg text-sm font-semibold bg-gray-200 hover:bg-gray-300 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200">
                                                            Cancelar
                                                        </button>

                                                        <button type="submit"
                                                                class="px-6 py-2 rounded-lg text-sm font-semibold bg-red-600 hover:bg-red-700 text-white shadow-sm transition">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
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
