<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg md:text-2xl font-semibold text-text-light dark:text-text-dark leading-tight">
            {{ __('Menús del Restaurante') }}
        </h2>
    </x-slot>

    @php
    $rol = auth()->user()->rol;
    @endphp

    <div x-data="{ 
            modalCategoriaCreate: false, 
            filtroCategoria: '' 
        }"
        class="max-w-7xl mx-auto py-4 md:py-6 px-4 md:px-6 bg-surface-light dark:bg-surface-dark rounded-2xl shadow-lg border border-border-light dark:border-border-dark transition-colors duration-300">

        {{-- Encabezado: filtro + botón --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 md:mb-8 gap-4">

            {{-- Filtro por categoría --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full md:w-1/3">
                <label for="filtroCategoria" class="text-gray-800 dark:text-gray-300 font-medium">
                    Filtrar Categoría:
                </label>
                <select id="filtroCategoria" x-model="filtroCategoria"
                    class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200 focus:ring-primary focus:border-primary">
                    <option value="">Todas</option>
                    @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->nombre }}">{{ $categoria->nombre }}</option>
                    @endforeach
                </select>
            </div>

            @if($rol === 'administrador')
            {{-- Botón agregar categoría --}}
            <button @click="modalCategoriaCreate = true"
                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold shadow-sm transition w-full md:w-auto">
                + Agregar Categoría
            </button>
            @endif
        </div>

        @if($rol === 'administrador')
        {{-- Modal de agregar categoría --}}
        <div x-show="modalCategoriaCreate"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
            <div class="bg-white dark:bg-background-dark/100 rounded-lg shadow-lg p-6 w-full max-w-md mx-4 relative">
                <button @click="modalCategoriaCreate = false"
                    class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200">
                    ✕
                </button>
                <h2 class="text-2xl font-semibold mb-4">Crear Categoría</h2>
                @include('categorias.create')
            </div>
        </div>
        @endif

        {{-- Listado de categorías --}}
        @foreach ($categorias as $categoria)
        <div x-show="!filtroCategoria || filtroCategoria == '{{ $categoria->nombre }}'" x-transition
            class="mb-8 md:mb-10 border border-primary/20 dark:border-primary/30 rounded-2xl shadow-sm overflow-hidden bg-white dark:bg-background-dark/50">

            @if($rol === 'administrador')
            {{-- Header de categoría --}}
            <div
                class="flex flex-col md:flex-row justify-between items-start md:items-center px-4 md:px-6 py-4 bg-primary/5 dark:bg-primary/10 border-b border-primary/20 dark:border-primary/30 gap-4">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                    <h3 class="text-lg md:text-xl font-semibold text-gray-800 dark:text-gray-100">
                        {{ $categoria->nombre }}
                    </h3>

                    {{-- Botones de acción --}}
                    <div class="flex items-center gap-2">
                        {{-- Editar --}}
                        <div x-data="{ modalCategoriaEdit: false }">
                            <button @click="modalCategoriaEdit = true"
                                class="text-blue-600 hover:text-blue-800 transition" title="Editar categoría">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.862 4.487l1.651 1.65a1.5 1.5 0 010 2.122l-9.193 9.192a4.5 4.5 0 01-1.591 1.02l-3.347 1.116a.75.75 0 01-.948-.948l1.116-3.347a4.5 4.5 0 011.02-1.59l9.193-9.193a1.5 1.5 0 012.122 0z" />
                                </svg>
                            </button>

                            {{-- Modal editar --}}
                            <div x-show="modalCategoriaEdit"
                                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                x-cloak x-transition>
                                <div
                                    class="bg-white dark:bg-background-dark/100 rounded-lg shadow-lg p-6 w-full max-w-md mx-4 relative">
                                    <button @click="modalCategoriaEdit = false"
                                        class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200">
                                        ✕
                                    </button>
                                    <h2 class="text-2xl font-semibold mb-4">Editar Categoría</h2>
                                    @include('categorias.edit', ['categoria' => $categoria])
                                </div>
                            </div>
                        </div>

                        {{-- Eliminar --}}
                        <div x-data="{ modalCategoriaDelete: false }" class="inline">
                            <button @click="modalCategoriaDelete = true"
                                class="text-red-600 hover:text-red-800 transition" title="Eliminar categoría">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>

                            {{-- Modal eliminar --}}
                            <div x-show="modalCategoriaDelete"
                                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                x-cloak x-transition>
                                <div
                                    class="bg-white dark:bg-background-dark/100 rounded-lg shadow-lg p-6 w-full max-w-md mx-4 relative">
                                    <button @click="modalCategoriaDelete = false"
                                        class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200">
                                        ✕
                                    </button>

                                    <h2 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-100">
                                        Eliminar Categoría
                                    </h2>

                                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                                        ¿Estás seguro de que deseas eliminar la categoría
                                        <span
                                            class="font-semibold text-gray-900 dark:text-gray-100">"{{ $categoria->nombre }}"</span>?
                                        <br> Esta acción no se puede deshacer.
                                    </p>

                                    <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST"
                                        class="flex justify-end gap-4">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button" @click="modalCategoriaDelete = false"
                                            class="px-6 py-2 rounded-lg text-sm font-semibold bg-gray-200 hover:bg-gray-300 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200">
                                            Cancelar
                                        </button>

                                        <button type="submit"
                                            class="px-6 py-2 rounded-lg text-sm font-semibold bg-red-600 hover:bg-red-700 text-white shadow-sm transition">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Botón agregar menú --}}
                <a href="{{ route('menus.create', ['categoria' => $categoria->id]) }}"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow-sm transition w-full md:w-auto text-center">
                    + Agregar Menú
                </a>
            </div>
            @endif

            {{-- Tabla de menús --}}
            @if ($categoria->menus->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-primary/5 dark:bg-primary/10 text-gray-600 dark:text-gray-300 uppercase text-xs">
                        <tr>
                            <th class="px-4 md:px-6 py-3 font-medium text-left">Foto</th>
                            <th class="px-4 md:px-6 py-3 font-medium text-left">Nombre</th>
                            <th class="px-4 md:px-6 py-3 font-medium text-left">Descripción</th>
                            <th class="px-4 md:px-6 py-3 font-medium text-right">Precio</th>
                            <th class="px-4 md:px-6 py-3 font-medium text-center">Disponibilidad</th>
                            <th class="px-4 md:px-6 py-3 font-medium text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-primary/10 dark:divide-primary/20">
                        @foreach ($categoria->menus as $menu)
                        <tr class="hover:bg-primary/5 dark:hover:bg-primary/10 transition">
                            {{-- Foto con vista previa --}}
                            <td class="py-3 px-4 md:px-6 text-center">
                                @if ($menu->imagen)
                                <img src="{{ asset('storage/' . $menu->imagen) }}" alt="{{ $menu->nombre }}"
                                    class="w-12 md:w-14 h-12 md:h-14 rounded-lg object-cover cursor-pointer shadow-sm hover:scale-105 transition"
                                    @click="imagenActual = '{{ asset('storage/' . $menu->imagen) }}'; modalVisible = true;">
                                @else
                                <div class="w-12 md:w-14 h-12 md:h-14 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center cursor-pointer shadow-sm hover:scale-105 transition"
                                    @click="imagenActual = '/images/no-image.png'; modalVisible = true;">
                                    <span class="text-gray-400 dark:text-gray-500 italic text-xs md:text-sm">
                                        Sin imagen
                                    </span>
                                </div>
                                @endif
                            </td>

                            <td class="py-3 px-4 md:px-6 text-gray-800 dark:text-gray-300 font-medium">{{ $menu->nombre }}</td>
                            <td class="py-3 px-4 md:px-6 text-gray-800 dark:text-gray-300 w-48 whitespace-normal break-words">{{ $menu->descripcion }}</td>
                            <td class="py-3 px-4 md:px-6 text-right font-semibold text-gray-900 dark:text-white">
                                ${{ number_format($menu->precio, 2) }}
                            </td>

                            <td class="py-3 px-4 md:px-6 text-center">
                                @if (Auth::user()?->rol === 'administrador')
                                <input type="checkbox" data-id="{{ $menu->id }}"
                                    class="toggle-disponible form-checkbox h-4 md:h-5 w-4 md:w-5 text-green-600 rounded focus:ring-0"
                                    {{ $menu->disponible ? 'checked' : '' }}>
                                @else
                                <span
                                    class="text-xs md:text-sm font-semibold {{ $menu->disponible ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $menu->disponible ? 'Disponible' : 'No Disponible' }}
                                </span>
                                @endif
                            </td>

                            @if($rol === 'administrador')
                            {{-- Acciones --}}
                            <td class="py-3 px-4 md:px-6 text-center space-x-2 md:space-x-3">
                                {{-- Botón editar --}}
                                <a href="{{ route('menus.edit', $menu) }}"
                                    class="inline-block px-2 md:px-3 py-1 text-xs font-semibold rounded-lg bg-yellow-500 hover:bg-yellow-600 text-white shadow-sm transition">
                                    Editar
                                </a>

                                {{-- Botón eliminar con modal --}}
                                <div x-data="{ modalEliminar: false }" class="inline-block">
                                    <button @click="modalEliminar = true"
                                        class="px-2 md:px-3 py-1 text-xs font-semibold rounded-lg bg-red-600 hover:bg-red-700 text-white shadow-sm transition">
                                        Eliminar
                                    </button>

                                    {{-- Modal de confirmación --}}
                                    <div x-show="modalEliminar" x-cloak
                                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                        x-transition>
                                        <div
                                            class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 md:p-6 w-full max-w-md mx-4 relative">
                                            {{-- Encabezado --}}
                                            <div class="flex items-center gap-3 mb-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 md:h-6 w-5 md:w-6 text-red-600"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 9v2m0 4h.01M5.93 19a9 9 0 1112.14 0H5.93z" />
                                                </svg>
                                                <h2 class="text-lg md:text-2xl font-semibold text-gray-800 dark:text-gray-200">
                                                    Confirmar eliminación
                                                </h2>
                                            </div>

                                            {{-- Mensaje --}}
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                                                ¿Estás seguro de que deseas eliminar el menú
                                                <span
                                                    class="font-semibold text-gray-800 dark:text-gray-100">"{{ $menu->nombre }}"</span>?
                                                Esta acción no se puede deshacer.
                                            </p>

                                            {{-- Botones --}}
                                            <div class="flex justify-end gap-3">
                                                <button @click="modalEliminar = false" type="button"
                                                    class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                                    Cancelar
                                                </button>

                                                <form action="{{ route('menus.destroy', $menu) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-semibold transition">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-center text-gray-500 dark:text-gray-400 px-4 md:px-6 py-4">
                No hay menús registrados en esta categoría.
            </p>
            @endif
        </div>
        @endforeach
    </div>

    {{-- Modal de previsualización de imagen --}}
    <div x-data="{ modalVisible: false, imagenActual: '' }">
        <div x-show="modalVisible" x-transition
            class="fixed inset-0 z-50 bg-black bg-opacity-75 flex items-center justify-center"
            @click.away="modalVisible = false" @keydown.escape.window="modalVisible = false">
            <img :src="imagenActual" class="max-h-full max-w-full rounded-xl shadow-lg border-4 border-white">
        </div>
    </div>

    {{-- Script para cambiar disponibilidad --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toggle-disponible').forEach(el => {
            el.addEventListener('change', function() {
                const menuId = this.dataset.id;
                const isChecked = this.checked;

                fetch("{{ route('menus.toggleDisponibilidad') }}", {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id: menuId
                        })
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Error al actualizar');
                        return response.json();
                    })
                    .catch(() => {
                        alert('Error al actualizar disponibilidad.');
                        this.checked = !isChecked;
                    });
            });
        });
    });
    </script>
</x-app-layout>