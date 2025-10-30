<form method="POST" action="{{ route('categorias.update', $categoria) }}">
    @csrf
    @method('PUT')

    <!-- Nombre -->
    <div class="mb-4">
        <label for="nombre" class="block text-gray-700 dark:text-gray-300 font-semibold">Nombre de la
            Categoría <i class="text-red-500">*</i></label>
        <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $categoria->nombre) }}" placeholder="Ingrese el nombre de la categoría"
            class="block mt-2 w-full rounded-xl shadow-sm border-gray-300 dark:border-gray-600 dark:bg-background-dark/70 
                   dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
            required>
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