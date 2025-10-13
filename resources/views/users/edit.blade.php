<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
            Editar Usuario
        </h2>
    </x-slot>

    <main class="flex-1 p-8 bg-gray-50 dark:bg-background-dark/20 min-h-screen">

        <div class="max-w-4xl mx-auto bg-white dark:bg-background-dark/50 shadow-sm rounded-xl border border-primary/20 dark:border-primary/30 p-6">

            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PUT')

                {{-- Nombre --}}
                <div class="mb-4">
                    <x-input-label for="name" :value="__('Nombre')" class="text-gray-700 dark:text-gray-300"/>
                    <x-text-input id="name" class="block mt-1 w-full rounded-lg shadow-sm border-gray-300 dark:border-gray-600 dark:bg-background-dark/70 dark:text-white focus:border-indigo-500 focus:ring-indigo-500" 
                                  type="text" name="name" :value="old('name', $user->name)" required autofocus autocomplete="name"/>
                    <x-input-error :messages="$errors->get('name')" class="mt-1 text-red-500 text-sm"/>
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email')" class="text-gray-700 dark:text-gray-300"/>
                    <x-text-input id="email" class="block mt-1 w-full rounded-lg shadow-sm border-gray-300 dark:border-gray-600 dark:bg-background-dark/70 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                                  type="email" name="email" :value="old('email', $user->email)" required autocomplete="username"/>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-sm"/>
                </div>

                {{-- Rol --}}
                <div class="mb-4">
                    <x-input-label for="rol" :value="__('Rol')" class="text-gray-700 dark:text-gray-300"/>
                    <select name="rol" id="rol"
                            class="block mt-1 w-full rounded-lg shadow-sm border-gray-300 dark:border-gray-600 dark:bg-background-dark/70 dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="administrador" {{ old('rol', $user->rol) == 'administrador' ? 'selected' : '' }}>Administrador</option>
                        <option value="mesero" {{ old('rol', $user->rol) == 'mesero' ? 'selected' : '' }}>Mesero</option>
                    </select>
                    <x-input-error :messages="$errors->get('rol')" class="mt-1 text-red-500 text-sm"/>
                </div>

                {{-- Contraseña --}}
                <div class="mb-4">
                    <x-input-label for="password" :value="__('Contraseña')" class="text-gray-700 dark:text-gray-300"/>
                    <x-text-input id="password" class="block mt-1 w-full rounded-lg shadow-sm border-gray-300 dark:border-gray-600 dark:bg-background-dark/70 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                                  type="password" name="password" autocomplete="new-password"/>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500 text-sm"/>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Dejar en blanco si no deseas cambiar la contraseña</p>
                </div>

                {{-- Confirmar Contraseña --}}
                <div class="mb-4">
                    <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" class="text-gray-700 dark:text-gray-300"/>
                    <x-text-input id="password_confirmation" class="block mt-1 w-full rounded-lg shadow-sm border-gray-300 dark:border-gray-600 dark:bg-background-dark/70 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                                  type="password" name="password_confirmation" autocomplete="new-password"/>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-500 text-sm"/>
                </div>

                {{-- Botón --}}
                    <div class="mt-auto flex justify-end gap-4">
                        <a href="{{ route('users.index') }}" 
                        class="px-6 py-2 rounded-lg text-sm font-semibold bg-primary/20 hover:bg-primary/30 text-gray-800 dark:text-gray-200">
                            Cancelar
                        </a>
                        <button type="submit" class="px-6 py-2 rounded-lg text-sm font-semibold bg-primary text-white hover:opacity-90">
                            Guardar Menu
                        </button>
                    </div>

            </form>

        </div>

    </main>
</x-app-layout>
