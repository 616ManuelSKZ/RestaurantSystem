<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ __('Editar Mesa') }}
        </h2>
    </x-slot>

    <main class="flex-1 p-8 bg-gray-50 dark:bg-background-dark/20 min-h-screen">
        <div class="max-w-4xl mx-auto">
            <div
                class="bg-white dark:bg-background-dark/50 rounded-xl border border-primary/20 dark:border-primary/30 shadow-sm p-6">

                <form action="{{ route('mesas.update', $mesa) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Área -->
                    <div class="mb-6">
                        <label for="id_area_mesas" class="block text-gray-700 dark:text-gray-300 font-semibold">
                            Área <i class="text-red-500">*</i>
                        </label>
                        <select name="id_area_mesas" id="id_area_mesas" class="block mt-2 w-full rounded-xl shadow-sm border-gray-300 dark:border-gray-600 dark:bg-background-dark/70 
                   dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach ($areas as $area)
                            <option value="{{ $area->id }}"
                                {{ old('id_area_mesas', $mesa->id_area_mesas) == $area->id ? 'selected' : '' }}>
                                {{ $area->nombre }}
                            </option>
                            @endforeach
                        </select>
                        @error('id_area_mesas')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Número de Mesa -->
                    <div class="mb-6">
                        <label for="numero" class="block text-gray-700 dark:text-gray-300 font-semibold">
                            Número de Mesa <i class="text-red-500">*</i>
                        </label>
                        <input id="numero" name="numero" type="number" value="{{ old('numero', $mesa->numero) }}"
                            required class="block mt-2 w-full rounded-xl shadow-sm border-gray-300 dark:border-gray-600 dark:bg-background-dark/70 
                   dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                        @error('numero')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Capacidad -->
                    <div class="mb-6">
                        <label for="capacidad" class="block text-gray-700 dark:text-gray-300 font-semibold">
                            Capacidad <i class="text-red-500">*</i>
                        </label>
                        <input id="capacidad" name="capacidad" type="number"
                            value="{{ old('capacidad', $mesa->capacidad) }}" required class="block mt-2 w-full rounded-xl shadow-sm border-gray-300 dark:border-gray-600 dark:bg-background-dark/70 
                   dark:text-white focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ingrese la capacidad de la mesa">
                        @error('capacidad')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estado -->
                    <div class="mb-6">
                        <label for="estado" class="block text-gray-700 dark:text-gray-300 font-semibold">
                            Estado <i class="text-red-500">*</i>
                        </label>
                        <select id="estado" name="estado" class="block mt-2 w-full rounded-xl shadow-sm border-gray-300 dark:border-gray-600 dark:bg-background-dark/70 
                   dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="disponible"
                                {{ old('estado', $mesa->estado) == 'disponible' ? 'selected' : '' }}>Disponible</option>
                            <option value="ocupada" {{ old('estado', $mesa->estado) == 'ocupada' ? 'selected' : '' }}>
                                Ocupada</option>
                        </select>
                        @error('estado')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="mt-8 flex justify-end gap-4">
                        <a href="{{ route('mesas.index') }}" class="px-6 py-2 rounded-lg text-sm font-semibold bg-primary/20 hover:bg-primary/30 
                  text-gray-800 dark:text-gray-200 transition">
                            Cancelar
                        </a>

                        <button type="submit"
                            class="px-6 py-2 rounded-lg text-sm font-semibold bg-primary text-white hover:opacity-90 transition">
                            Actualizar Mesa
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </main>
</x-app-layout>