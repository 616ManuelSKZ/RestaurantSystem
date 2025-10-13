<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ __('Editar Menú') }}
        </h2>
    </x-slot>

    <main class="flex-1 p-8 bg-gray-50 dark:bg-background-dark/20 min-h-screen">

        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-background-dark/50 rounded-xl border border-primary/20 dark:border-primary/30 shadow-sm p-6">

                <form method="POST" action="{{ route('menus.update', $menu) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- 🔹 Imagen --}}
                    <div class="mb-6">
                        <label for="imagen" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Subir imagen</label>
                        <div class="flex flex-col md:flex-row items-start gap-6">
                            <div class="w-full md:w-1/2">
                                <input 
                                    type="file" name="imagen" id="imagen" accept="image/*" onchange="previewImagen(event)"
                                    class="block w-full text-sm text-gray-900 dark:text-gray-400 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-background-dark/50 focus:outline-none"
                                />
                                <x-input-error :messages="$errors->get('imagen')" class="mt-2 text-sm text-red-600" />
                            </div>
                            <div class="w-full md:w-1/2">
                                <img 
                                    id="preview-imagen" 
                                    src="{{ $menu->imagen ? asset('storage/' . $menu->imagen) : '#' }}" 
                                    alt="Vista previa de imagen" 
                                    class="{{ $menu->imagen ? '' : 'hidden' }} w-full h-auto border rounded" 
                                />
                            </div>
                        </div>
                    </div>

                    {{-- 🔹 Nombre --}}
                    <div class="mb-6">
                        <x-input-label for="nombre" :value="__('Nombre')" class="text-gray-900 dark:text-gray-400" />
                        <x-text-input 
                            id="nombre" 
                            class="block mt-1 w-full border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm bg-white dark:bg-background-dark/50 text-gray-900 dark:text-white" 
                            type="text" name="nombre" 
                            :value="old('nombre', $menu->nombre)" 
                            required autofocus autocomplete="nombre" 
                        />
                        <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                    </div>

                    {{-- 🔹 Descripción --}}
                    <div class="mb-6">
                        <x-input-label for="descripcion" :value="__('Descripción')" class="text-gray-900 dark:text-gray-400" />
                        <textarea 
                            id="descripcion" 
                            class="block mt-1 w-full border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm bg-white dark:bg-background-dark/50 text-gray-900 dark:text-white" 
                            name="descripcion" 
                            required
                        >{{ old('descripcion', $menu->descripcion) }}</textarea>
                        <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
                    </div>

                    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Precio --}}
                        <div>
                            <x-input-label for="precio" :value="__('Precio')" class="text-gray-900 dark:text-gray-400" />
                            <x-text-input 
                                id="precio" 
                                class="block mt-1 w-full border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm bg-white dark:bg-background-dark/50 text-gray-900 dark:text-white" 
                                type="number" step="0.01" name="precio" 
                                :value="old('precio', $menu->precio)" required autocomplete="precio" 
                            />
                            <x-input-error :messages="$errors->get('precio')" class="mt-2" />
                        </div>

                        {{-- Disponibilidad --}}
                        <div>
                            <x-input-label for="disponible" :value="__('Disponibilidad')" class="text-gray-900 dark:text-gray-400" />
                            <select 
                                name="disponible" 
                                id="disponible" 
                                class="block mt-1 w-full border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm bg-white dark:bg-background-dark/50 text-gray-900 dark:text-white"
                            >
                                <option value="1" {{ old('disponible', $menu->disponible) == 1 ? 'selected' : '' }}>Disponible</option>
                                <option value="0" {{ old('disponible', $menu->disponible) == 0 ? 'selected' : '' }}>No Disponible</option>
                            </select>
                            <x-input-error :messages="$errors->get('disponible')" class="mt-2" />
                        </div>
                    </div>

                    {{-- Categoría --}}
                    <input type="hidden" name="id_categoria" value="{{ old('id_categoria', $menu->id_categoria) }}" />
                    <x-input-error :messages="$errors->get('id_categoria')" class="mt-2" />

                    {{-- Botones --}}
                    <div class="flex justify-end mt-6 gap-4">
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

    {{-- 📸 Vista previa de imagen --}}
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
