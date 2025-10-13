<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ __('Editar Mesa') }}
        </h2>
    </x-slot>

    <main class="flex-1 p-8 bg-gray-50 dark:bg-background-dark/20 min-h-screen">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-background-dark/50 rounded-xl border border-primary/20 dark:border-primary/30 shadow-sm p-6">

                <form action="{{ route('mesas.update', $mesa) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- 🔹 Área --}}
                    <div class="mb-6">
                        <x-input-label for="id_area_mesas" :value="__('Área')" class="text-gray-900 dark:text-gray-400" />
                        <select name="id_area_mesas" id="id_area_mesas"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-600 
                                       focus:border-indigo-500 focus:ring-indigo-500 
                                       rounded-md shadow-sm bg-white dark:bg-background-dark/50 
                                       text-gray-900 dark:text-white">
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}"
                                    {{ old('id_area_mesas', $mesa->id_area_mesas) == $area->id ? 'selected' : '' }}>
                                    {{ $area->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('id_area_mesas')" class="mt-2" />
                    </div>

                    {{-- 🔹 Número de Mesa --}}
                    <div class="mb-6">
                        <x-input-label for="numero" :value="__('Número de Mesa')" class="text-gray-900 dark:text-gray-400" />
                        <x-text-input id="numero" 
                                      name="numero"
                                      type="number"
                                      :value="old('numero', $mesa->numero)"
                                      required
                                      class="block mt-1 w-full border-gray-300 dark:border-gray-600 
                                             focus:border-indigo-500 focus:ring-indigo-500 
                                             rounded-md shadow-sm bg-white dark:bg-background-dark/50 
                                             text-gray-900 dark:text-white" />
                        <x-input-error :messages="$errors->get('numero')" class="mt-2" />
                    </div>

                    {{-- 🔹 Capacidad --}}
                    <div class="mb-6">
                        <x-input-label for="capacidad" :value="__('Capacidad')" class="text-gray-900 dark:text-gray-400" />
                        <x-text-input id="capacidad" 
                                      name="capacidad"
                                      type="number"
                                      :value="old('capacidad', $mesa->capacidad)"
                                      required
                                      class="block mt-1 w-full border-gray-300 dark:border-gray-600 
                                             focus:border-indigo-500 focus:ring-indigo-500 
                                             rounded-md shadow-sm bg-white dark:bg-background-dark/50 
                                             text-gray-900 dark:text-white" />
                        <x-input-error :messages="$errors->get('capacidad')" class="mt-2" />
                    </div>

                    {{-- 🔹 Estado --}}
                    <div class="mb-6">
                        <x-input-label for="estado" :value="__('Estado')" class="text-gray-900 dark:text-gray-400" />
                        <select id="estado" name="estado"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-600 
                                       focus:border-indigo-500 focus:ring-indigo-500 
                                       rounded-md shadow-sm bg-white dark:bg-background-dark/50 
                                       text-gray-900 dark:text-white">
                            <option value="disponible" {{ old('estado', $mesa->estado) == 'disponible' ? 'selected' : '' }}>Disponible</option>
                            <option value="ocupada" {{ old('estado', $mesa->estado) == 'ocupada' ? 'selected' : '' }}>Ocupada</option>
                            <option value="reservada" {{ old('estado', $mesa->estado) == 'reservada' ? 'selected' : '' }}>Reservada</option>
                        </select>
                        <x-input-error :messages="$errors->get('estado')" class="mt-2" />
                    </div>

                    {{-- 🔹 Botones --}}
                    <div class="mt-8 flex justify-end gap-4">
                        <a href="{{ route('mesas.index') }}" 
                           class="px-6 py-2 rounded-lg text-sm font-semibold bg-primary/20 hover:bg-primary/30 
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
