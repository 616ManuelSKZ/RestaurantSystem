<form action="{{ route('area_mesas.store') }}" method="POST">
    @csrf

    <!-- Nombre del Área -->
    <div class="mb-6">
        <label for="nombre" class="block text-gray-700 dark:text-gray-300 font-semibold">
            Nombre del Área
        </label>
        <input id="nombre" name="nombre" type="text" required value="{{ old('nombre') }}"
            class="block mt-2 w-full rounded-xl shadow-sm border-gray-300 dark:border-gray-600 dark:bg-background-dark/70 
                   dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
        @error('nombre')
        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <!-- Botones -->
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