<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ __('Crear Menú') }}
        </h2>
    </x-slot>

    <main class="flex-1 p-8 bg-gray-50 dark:bg-background-dark/20 min-h-screen">

        <div class="max-w-4xl mx-auto">
            <div
                class="bg-white dark:bg-background-dark/50 rounded-xl border border-primary/20 dark:border-primary/30 shadow-sm p-6">

                <form method="POST" action="{{ route('menus.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Imagen -->
                    <div class="mb-6">
                        <label for="imagen" class="block text-gray-700 dark:text-gray-300 font-semibold">
                            Subir imagen
                        </label>

                        <div class="flex flex-col items-center gap-4">
                            <!-- Vista previa centrada arriba -->
                            <div class="w-full flex justify-center">
                                <img id="preview-imagen" src="#" alt="Vista previa de imagen"
                                    class="hidden w-full max-w-sm h-auto border rounded object-cover"/>
                            </div>

                            <!-- Input de imagen a todo el ancho -->
                            <div class="w-full">
                                <input type="file" name="imagen" id="imagen" accept="image/*" onchange="previewImagen(event)"
                                    class="block w-full text-bs text-gray-900 dark:text-gray-400 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-background-dark/50 focus:outline-none"/>
                                @error('imagen')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Nombre -->
                    <div class="mb-6">
                        <label for="nombre"
                            class="block text-gray-700 dark:text-gray-300 font-semibold">Nombre</label>
                        <input id="nombre" name="nombre" type="text" required autofocus autocomplete="nombre"
                            value="{{ old('nombre') }}"
                            class="block mt-2 w-full rounded-xl shadow-sm border-gray-300 dark:border-gray-600 dark:bg-background-dark/70 
                   dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                        @error('nombre')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div class="mb-6">
                        <label for="descripcion"
                            class="block text-gray-700 dark:text-gray-300 font-semibold">Descripción</label>
                        <textarea id="descripcion" name="descripcion" required
                            class="block mt-2 w-full rounded-xl shadow-sm border-gray-300 dark:border-gray-600 dark:bg-background-dark/70 
                   dark:text-white focus:border-indigo-500 focus:ring-indigo-500">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Precio -->
                        <div>
                            <label for="precio"
                                class="block text-gray-700 dark:text-gray-300 font-semibold">Precio</label>
                            <input id="precio" name="precio" type="number" step="0.01" required autocomplete="precio"
                                value="{{ old('precio') }}"
                                class="block mt-2 w-full rounded-xl shadow-sm border-gray-300 dark:border-gray-600 dark:bg-background-dark/70 
                   dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                            @error('precio')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Disponibilidad -->
                        <div>
                            <label for="disponible"
                                class="block text-gray-700 dark:text-gray-300 font-semibold">Disponibilidad</label>
                            <select name="disponible" id="disponible"
                                class="block mt-2 w-full rounded-xl shadow-sm border-gray-300 dark:border-gray-600 dark:bg-background-dark/70 
                   dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="1" {{ old('disponible') == '1' ? 'selected' : '' }}>Disponible</option>
                                <option value="0" {{ old('disponible') == '0' ? 'selected' : '' }}>No Disponible
                                </option>
                            </select>
                            @error('disponible')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Categoría -->
                    <input type="hidden" name="id_categoria" value="{{ $categoriaSeleccionada ?? '' }}">
                    @error('id_categoria')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror

                    <!-- Botones -->
                    <div class="mt-auto flex justify-end gap-4">
                        <a href="{{ route('menus.index') }}"
                            class="px-6 py-2 rounded-lg text-sm font-semibold bg-primary/20 hover:bg-primary/30 text-gray-800 dark:text-gray-200">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="px-6 py-2 rounded-lg text-sm font-semibold bg-primary text-white hover:opacity-90">
                            Guardar Menú
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </main>

    {{-- Vista previa de imagen --}}
    <script>
    function previewImagen(event) {
        const input = event.target;
        const preview = document.getElementById('preview-imagen');
        const file = input.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            preview.src = '#';
            preview.classList.add('hidden');
        }
    }
    </script>

</x-app-layout>