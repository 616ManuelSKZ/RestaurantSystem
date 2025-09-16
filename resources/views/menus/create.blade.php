<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Menú') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('menus.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Imagen -->
                        <div class="mt-4">
                            <label for="imagen" class="block mb-2 text-sm font-medium text-gray-900">Subir imagen</label>

                            <div class="flex flex-col md:flex-row items-start gap-6">
                                <!-- Input de imagen -->
                                <div class="w-full md:w-1/2">
                                    <input 
                                        type="file" name="imagen" id="imagen" accept="image/*" onchange="previewImagen(event)"
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-white-700 dark:border-gray-600 dark:placeholder-gray-400"/>
                                    <x-input-error :messages="$errors->get('imagen')" class="mt-2 text-sm text-red-600" />
                                </div>

                                <!-- Vista previa -->
                                <div class="w-full md:w-1/2">
                                    <img id="preview-imagen" src="#" alt="Vista previa de imagen" class="hidden w-100 h-auto border rounded" />
                                </div>
                            </div>
                        </div>

                        <!-- Nombre -->
                        <div class="mt-4">
                            <x-input-label for="nombre" :value="__('Nombre')" />
                            <x-text-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre')" required autofocus autocomplete="nombre" />
                            <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                        </div>

                        <!-- Descripción -->
                        <div class="mt-4">
                            <x-input-label for="descripcion" :value="__('Descripción')" />
                            <textarea id="descripcion" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="descripcion" required>{{ old('descripcion') }}</textarea>
                            <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
                        </div>

                        <div class="mt-4 flex flex-col md:flex-row gap-4">
                            <!-- Precio -->
                            <div class="w-full md:w-1/2">
                                <x-input-label for="precio" :value="__('Precio')" />
                                <x-text-input id="precio" class="block mt-1 w-full" type="number" step="0.01" name="precio" :value="old('precio')" required autocomplete="precio" />
                                <x-input-error :messages="$errors->get('precio')" class="mt-2" />
                            </div>

                            <!-- Disponibilidad -->
                            <div class="w-full md:w-1/2">
                                <x-input-label for="disponible" :value="__('Disponibilidad')" />
                                <select name="disponible" id="disponible" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="1">Disponible</option>
                                    <option value="0">No Disponible</option>
                                </select>
                                <x-input-error :messages="$errors->get('disponible')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-4 flex flex-col md:flex-row gap-4">
                            <!-- Categoría -->
                            <div class="w-full md:w-1/2">
                                <x-input-label for="id_categoria" :value="__('Categoría')" />
                                <select name="id_categoria" id="id_categoria" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" {{ (isset($categoriaSeleccionada) && $categoriaSeleccionada == $categoria->id) ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-sm mt-1">
                                    ¿No encuentras la categoría? <a href="{{ route('categorias.create') }}" class="text-blue-500 underline">Crear categoría</a>
                                </p>
                                <x-input-error :messages="$errors->get('id_categoria')" class="mt-2" />
                            </div>

                            <!-- Fecha de Creación -->
                            <div class="w-full md:w-1/2">
                                <x-input-label for="fecha_registro" :value="__('Fecha de Creación')" />
                                <x-text-input id="fecha_registro" class="block mt-1 w-full" type="date" name="fecha_registro" :value="old('fecha_registro', date('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('fecha_registro')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Crear Menú') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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