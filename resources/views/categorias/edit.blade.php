<form method="POST" action="{{ route('categorias.update', $categoria) }}">
    @csrf
    @method('PUT')

    <!-- Nombre -->
    <div class="mt-4">
        <x-input-label for="nombre" :value="__('Nombre')" />
        <x-text-input id="nombre" class="block mt-1 w-full" type="text" name="nombre"
            :value="old('nombre', $categoria->nombre)" required autofocus autocomplete="nombre" />
        <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
    </div>

    {{-- Botones --}}
    <div class="flex justify-end gap-4 mt-6">
        <button type="button" @click="modalVisibleEdit = false"
            class="px-6 py-2 rounded-lg text-sm font-semibold bg-gray-200 hover:bg-gray-300 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200">
            Cancelar
        </button>
        <button type="submit" class="px-6 py-2 rounded-lg text-sm font-semibold bg-primary text-white hover:opacity-90">
            Guardar
        </button>
    </div>
</form>