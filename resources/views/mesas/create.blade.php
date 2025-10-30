<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ __('Agregar Mesa') }}
        </h2>
    </x-slot>

    <main class="flex-1 p-8 bg-gray-50 dark:bg-background-dark/20 min-h-screen">

        <div class="max-w-4xl mx-auto">
            <div
                class="bg-white dark:bg-background-dark/50 rounded-xl border border-primary/20 dark:border-primary/30 shadow-sm p-6">

                <form action="{{ route('mesas.store') }}" method="POST">
                    @csrf

                    <!-- ID de Área (oculto) -->
                    <input type="hidden" name="id_area_mesas" value="{{ old('id_area_mesas', $areaId) }}">
                    @error('id_area_mesas')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Número de Mesa -->
                    <div class="mb-6">
                        <label for="numero" class="block text-gray-700 dark:text-gray-300 font-semibold">Número de
                            Mesa <i class="text-red-500">*</i></label>
                        <input id="numero" name="numero" type="number" value="{{ old('numero') }}" required class="block mt-2 w-full rounded-xl shadow-sm border-gray-300 dark:border-gray-600 dark:bg-background-dark/70 
                   dark:text-white focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ingrese el número de la mesa">
                        @error('numero')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Capacidad -->
                    <div class="mb-6">
                        <label for="capacidad"
                            class="block text-gray-700 dark:text-gray-300 font-semibold">Capacidad <i class="text-red-500">*</i></label>
                        <input id="capacidad" name="capacidad" type="number" value="{{ old('capacidad') }}" required 
                            class="block mt-2 w-full rounded-xl shadow-sm border-gray-300 dark:border-gray-600 dark:bg-background-dark/70 
                   dark:text-white focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ingrese la capacidad de la mesa">
                        @error('capacidad')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estado -->
                    <div class="mb-6">
                        <label for="estado"
                            class="block text-gray-700 dark:text-gray-300 font-semibold">Estado <i class="text-red-500">*</i></label>
                        <select id="estado" name="estado" class="block mt-2 w-full rounded-xl shadow-sm border-gray-300 dark:border-gray-600 dark:bg-background-dark/70 
                   dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="disponible"
                                {{ old('estado', 'disponible') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                            <option value="ocupada" {{ old('estado') == 'ocupada' ? 'selected' : '' }}>Ocupada</option>
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
                            Guardar Mesa
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </main>
</x-app-layout>