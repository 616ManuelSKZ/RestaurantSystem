<form action="{{ route('area_mesas.store') }}" method="POST">
    @csrf

    {{-- Nombre del Área --}}
    <div class="mb-6">
        <x-input-label for="nombre" :value="__('Nombre del Área')" class="block text-sm font-medium text-gray-700 dark:text-gray-200" />
        <x-text-input id="nombre" name="nombre" type="text" required value="{{ old('nombre') }}"
            class="block mt-1 w-full border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm bg-white dark:bg-background-dark/50 text-gray-900 dark:text-white" />
        <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
    </div>

    {{-- Botones --}}
    <div class="flex justify-end gap-4 mt-6">
        <button type="button" @click="modalAreaCreate = false"
            class="px-6 py-2 rounded-lg text-sm font-semibold bg-gray-200 hover:bg-gray-300 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200">
            Cancelar
        </button>
        <button type="submit" class="px-6 py-2 rounded-lg text-sm font-semibold bg-primary text-white hover:opacity-90">
            Guardar
        </button>
    </div>
</form>