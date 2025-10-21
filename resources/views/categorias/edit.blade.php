<form method="POST" action="{{ route('categorias.update', $categoria) }}">
    @csrf
    @method('PUT')

    <!-- Nombre -->
    <div class="mb-4">
        <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nombre de la Categoría</label>
        <input type="text" name="nombre" id="nombre"
            value="{{ old('nombre', $categoria->nombre) }}"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary dark:bg-gray-800 dark:border-gray-700 dark:text-white" required>
    </div>

    {{-- Botones --}}
    <div class="flex justify-end gap-4 mt-6">
        <button type="button" @click="modalCategoriaEdit = false"
            class="px-6 py-2 rounded-lg text-sm font-semibold bg-gray-200 hover:bg-gray-300 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200">
            Cancelar
        </button>
        <button type="submit" class="px-6 py-2 rounded-lg text-sm font-semibold bg-primary text-white hover:opacity-90">
            Guardar
        </button>
    </div>
</form>