<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-text-light dark:text-text-dark leading-tight">
            {{ __('Menús del Restaurante') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-6 bg-surface-light dark:bg-surface-dark rounded-2xl shadow-lg border border-border-light dark:border-border-dark transition-colors duration-300">

        {{-- Botón agregar categoría --}}
        <div x-data="{ modalVisible: false }">
            <div class="flex justify-end mb-8">
                <button @click="modalVisible = true"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold shadow-sm transition">
                    + Agregar Categoría
                </button>
            </div>

            {{-- Modal de agregar categoría --}}
            <div x-show="modalVisible"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
                <div class="bg-white dark:bg-background-dark/50 rounded-lg shadow-lg p-6 w-full max-w-md mx-4">
                    <h2 class="text-lg font-semibold mb-4">Crear Categoria</h2>
                    @include('categorias.create')
                </div>
            </div>
        </div>

        @foreach($categorias as $categoria)
            <div class="mb-10 border border-primary/20 dark:border-primary/30 rounded-2xl shadow-sm overflow-hidden bg-white dark:bg-background-dark/50">
                
                {{-- Header de categoría --}}
                <div class="flex justify-between items-center px-6 py-4 bg-primary/5 dark:bg-primary/10 border-b border-primary/20 dark:border-primary/30">
                    <div class="flex items-center gap-3">
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                            {{ $categoria->nombre }}
                        </h3>

                        {{-- Botones de acción (editar y eliminar) --}}
                        <div class="flex items-center gap-2">
                            {{-- Editar --}}
                            <a href="{{ route('categorias.edit', $categoria->id) }}"
                                class="text-blue-600 hover:text-blue-800 transition"
                                title="Editar categoría">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.862 4.487l1.651 1.65a1.5 1.5 0 010 2.122l-9.193 9.192a4.5 4.5 0 01-1.591 1.02l-3.347 1.116a.75.75 0 01-.948-.948l1.116-3.347a4.5 4.5 0 011.02-1.59l9.193-9.193a1.5 1.5 0 012.122 0z" />
                                </svg>
                            </a>

                            {{-- Eliminar --}}
                            <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST"
                                onsubmit="return confirm('¿Deseas eliminar esta categoría?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-red-600 hover:text-red-800 transition"
                                    title="Eliminar categoría">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Botón agregar menú --}}
                    <a href="{{ route('menus.create', ['categoria' => $categoria->id]) }}"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow-sm transition">
                        + Agregar Menú
                    </a>
                </div>

                {{-- Tabla de menús --}}
                @if ($categoria->menus->count())
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-primary/5 dark:bg-primary/10 text-gray-600 dark:text-gray-300 uppercase text-xs">
                                <tr>
                                    <th class="px-6 py-3 font-medium text-left">Foto</th>
                                    <th class="px-6 py-3 font-medium text-left">Nombre</th>
                                    <th class="px-6 py-3 font-medium text-left">Descripción</th>
                                    <th class="px-6 py-3 font-medium text-right">Precio</th>
                                    <th class="px-6 py-3 font-medium text-center">Disponibilidad</th>
                                    <th class="px-6 py-3 font-medium text-center">Acciones</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-primary/10 dark:divide-primary/20">
                                @foreach ($categoria->menus as $menu)
                                    <tr class="hover:bg-primary/5 dark:hover:bg-primary/10 transition">
                                        {{-- Foto con vista previa --}}
                                        <td class="py-3 px-6 text-center">
                                            @if ($menu->imagen)
                                                <img src="{{ asset('storage/' . $menu->imagen) }}"
                                                    alt="{{ $menu->nombre }}"
                                                    class="w-14 h-14 rounded-lg object-cover cursor-pointer shadow-sm hover:scale-105 transition"
                                                    @click="imagenActual = '{{ asset('storage/' . $menu->imagen) }}'; modalVisible = true;">
                                            @endif
                                        </td>

                                        <td class="py-3 px-6 text-gray-800 dark:text-gray-300 font-medium">{{ $menu->nombre }}</td>
                                        <td class="py-3 px-6 text-gray-800 dark:text-gray-300 truncate">{{ $menu->descripcion }}</td>
                                        <td class="py-3 px-6 text-right font-semibold text-gray-900 dark:text-white">
                                            ${{ number_format($menu->precio, 2) }}
                                        </td>

                                        <td class="py-3 px-6 text-center">
                                            @if (Auth::user()?->rol === 'administrador')
                                                <input type="checkbox"
                                                    data-id="{{ $menu->id }}"
                                                    class="toggle-disponible form-checkbox h-5 w-5 text-green-600 rounded focus:ring-0"
                                                    {{ $menu->disponible ? 'checked' : '' }}>
                                            @else
                                                <span class="text-sm font-semibold {{ $menu->disponible ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                    {{ $menu->disponible ? 'Disponible' : 'No Disponible' }}
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Acciones --}}
                                        <td class="py-3 px-6 text-center space-x-3">
                                            <a href="{{ route('menus.edit', $menu) }}"
                                            class="inline-block px-3 py-1 text-xs font-semibold rounded-lg bg-yellow-500 hover:bg-yellow-600 text-white shadow-sm transition">
                                            Editar
                                            </a>
                                            <form action="{{ route('menus.destroy', $menu) }}" method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('¿Deseas eliminar este menú?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="px-3 py-1 text-xs font-semibold rounded-lg bg-red-600 hover:bg-red-700 text-white shadow-sm transition">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-gray-500 dark:text-gray-400 px-6 py-4">
                        No hay menús registrados en esta categoría.
                    </p>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Modal de previsualización de imagen --}}
    <div x-data="{ modalVisible: false, imagenActual: '' }">
        <div x-show="modalVisible"
             x-transition
             class="fixed inset-0 z-50 bg-black bg-opacity-75 flex items-center justify-center"
             @click.away="modalVisible = false"
             @keydown.escape.window="modalVisible = false">
            <img :src="imagenActual" class="max-h-full max-w-full rounded-xl shadow-lg border-4 border-white">
        </div>
    </div>

    {{-- Script para cambiar disponibilidad --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.toggle-disponible').forEach(el => {
                el.addEventListener('change', function () {
                    const menuId = this.dataset.id;
                    const isChecked = this.checked;

                    fetch("{{ route('menus.toggleDisponibilidad') }}", {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ id: menuId })
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
