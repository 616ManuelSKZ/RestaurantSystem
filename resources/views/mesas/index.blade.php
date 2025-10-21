<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-text-light dark:text-text-dark leading-tight">
            {{ __('Áreas y Mesas') }}
        </h2>
    </x-slot>

    <div x-data="{ 
            modalAreaCreate: false, 
            filtroArea: '' 
        }" 
        class="max-w-7xl mx-auto py-6 px-6 bg-surface-light dark:bg-surface-dark rounded-2xl shadow-lg border border-border-light dark:border-border-dark transition-colors duration-300">

        {{-- Encabezado: filtro + botón --}}
        <div class="flex justify-between items-center mb-8">
            {{-- Filtro por área --}}
            <div class="flex items-center gap-3 w-1/3">
                <label for="filtroArea" class="text-gray-800 dark:text-gray-300 font-medium">
                    Filtrar Área:
                </label>
                <select id="filtroArea" x-model="filtroArea"
                    class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200 focus:ring-primary focus:border-primary">
                    <option value="">Todas</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Botón agregar área --}}
            <button @click="modalAreaCreate = true"
                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold shadow-sm transition">
                + Agregar Área
            </button>
        </div>

        {{-- Modal crear área --}}
        <div x-show="modalAreaCreate"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
            <div class="bg-white dark:bg-background-dark/100 rounded-lg shadow-lg p-6 w-full max-w-md mx-4 relative">
                <button @click="modalAreaCreate = false"
                    class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200">
                    ✕
                </button>
                <h2 class="text-lg font-semibold mb-4">Crear Área</h2>
                @include('area_mesas.create')
            </div>
        </div>

        {{-- Listado de Áreas --}}
        @foreach ($areas as $area)
            <div 
                x-show="!filtroArea || filtroArea == '{{ $area->id }}'" 
                x-transition
                class="mb-10 border border-primary/20 dark:border-primary/30 rounded-2xl shadow-sm overflow-hidden bg-white dark:bg-background-dark/50">

                {{-- Header del área --}}
                <div class="flex justify-between items-center px-6 py-4 bg-primary/5 dark:bg-primary/10 border-b border-primary/20 dark:border-primary/30">
                    <div class="flex items-center gap-3">
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                            {{ $area->nombre }}
                        </h3>

                        {{-- Botones de acción --}}
                        <div class="flex items-center gap-2">
                            {{-- Editar --}}
                            <div x-data="{ modalAreaEdit: false }">
                                <button @click="modalAreaEdit = true"
                                    class="text-blue-600 hover:text-blue-800 transition"
                                    title="Editar área">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487l1.651 1.65a1.5 1.5 0 010 2.122l-9.193 9.192a4.5 4.5 0 01-1.591 1.02l-3.347 1.116a.75.75 0 01-.948-.948l1.116-3.347a4.5 4.5 0 011.02-1.59l9.193-9.193a1.5 1.5 0 012.122 0z" />
                                    </svg>
                                </button>

                                {{-- Modal editar área --}}
                                <div x-show="modalAreaEdit"
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                    x-cloak x-transition>
                                    <div
                                        class="bg-white dark:bg-background-dark/100 rounded-lg shadow-lg p-6 w-full max-w-md mx-4 relative">
                                        <button @click="modalAreaEdit = false"
                                            class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200">
                                            ✕
                                        </button>
                                        <h2 class="text-lg font-semibold mb-4">Editar Área</h2>
                                        @include('area_mesas.edit', ['areaMesa' => $area])
                                    </div>
                                </div>
                            </div>

                            {{-- Eliminar --}}
                            <div x-data="{ modalAreaDelete: false }" class="inline">
                                <button @click="modalAreaDelete = true"
                                    class="text-red-600 hover:text-red-800 transition"
                                    title="Eliminar área">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                                {{-- Modal eliminar área --}}
                                <div x-show="modalAreaDelete"
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                    x-cloak x-transition>
                                    <div
                                        class="bg-white dark:bg-background-dark/100 rounded-lg shadow-lg p-6 w-full max-w-md mx-4 relative">
                                        <button @click="modalAreaDelete = false"
                                            class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200">
                                            ✕
                                        </button>

                                        <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">
                                            Eliminar Área
                                        </h2>

                                        <p class="text-gray-600 dark:text-gray-300 mb-6">
                                            ¿Estás seguro de que deseas eliminar el área
                                            <span
                                                class="font-semibold text-gray-900 dark:text-gray-100">"{{ $area->nombre }}"</span>?
                                            <br> Esta acción eliminará también sus mesas y no se puede deshacer.
                                        </p>

                                        <form action="{{ route('area_mesas.destroy', $area->id) }}" method="POST"
                                            class="flex justify-end gap-4">
                                            @csrf
                                            @method('DELETE')

                                            <button type="button" @click="modalAreaDelete = false"
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

                    {{-- Botón agregar mesa --}}
                    <a href="{{ route('mesas.create', ['area' => $area->id]) }}"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow-sm transition">
                        + Agregar Mesa
                    </a>
                </div>

                {{-- Tabla de mesas --}}
                @if ($area->mesas->count())
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-primary/5 dark:bg-primary/10 text-gray-600 dark:text-gray-300 uppercase text-xs">
                                <tr>
                                    <th class="px-6 py-3 font-medium text-center">Número</th>
                                    <th class="px-6 py-3 font-medium text-center">Capacidad</th>
                                    <th class="px-6 py-3 font-medium text-center">Estado</th>
                                    <th class="px-6 py-3 font-medium text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-primary/10 dark:divide-primary/20">
                                @foreach ($area->mesas as $mesa)
                                    <tr class="hover:bg-primary/5 dark:hover:bg-primary/10 transition">
                                        <td class="py-3 px-6 text-center text-gray-800 dark:text-gray-200 font-medium">{{ $mesa->numero }}</td>
                                        <td class="py-3 px-6 text-center text-gray-800 dark:text-gray-200">{{ $mesa->capacidad }}</td>
                                        <td class="py-3 px-6 text-center">
                                            @if($mesa->estado === 'disponible')
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300">
                                                    Disponible
                                                </span>
                                            @elseif($mesa->estado === 'ocupada')
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300">
                                                    Ocupada
                                                </span>
                                            @else
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300">
                                                    {{ $mesa->estado }}
                                                </span>
                                            @endif
                                        </td>

                                        <td class="py-3 px-6 text-center space-x-3" x-data="{ modalMesaDelete: false, mesaId: '{{ $mesa->id }}', mesaNombre: '{{ $mesa->numero ?? 'Sin número' }}' }">

                                            {{-- Botón Editar --}}
                                            <a href="{{ route('mesas.edit', $mesa) }}"
                                                class="inline-block px-3 py-1 text-xs font-semibold rounded-lg bg-yellow-500 hover:bg-yellow-600 text-white shadow-sm transition">
                                                Editar
                                            </a>

                                            {{-- Botón Eliminar (abre modal) --}}
                                            <button type="button"
                                                @click="modalMesaDelete = true"
                                                class="inline-block px-3 py-1 text-xs font-semibold rounded-lg bg-red-600 hover:bg-red-700 text-white shadow-sm transition">
                                                Eliminar
                                            </button>

                                            {{-- Modal eliminar mesa --}}
                                            <div x-show="modalMesaDelete"
                                                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                                x-cloak x-transition>
                                                <div
                                                    class="bg-white dark:bg-background-dark/100 rounded-lg shadow-lg p-6 w-full max-w-md mx-4 relative">
                                                    <button @click="modalMesaDelete = false"
                                                        class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200">
                                                        ✕
                                                    </button>

                                                    <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">
                                                        Eliminar Mesa
                                                    </h2>

                                                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                                                        ¿Estás seguro de que deseas eliminar la mesa
                                                        <span class="font-semibold text-gray-900 dark:text-gray-100" x-text="mesaNombre"></span>?
                                                        <br> Esta acción no se puede deshacer.
                                                    </p>

                                                    <form :action="`/mesas/${mesaId}`" method="POST" class="flex justify-end gap-4">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="button" @click="modalMesaDelete = false"
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
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-gray-500 dark:text-gray-400 px-6 py-4">
                        No hay mesas registradas en esta área.
                    </p>
                @endif
            </div>
        @endforeach
    </div>
</x-app-layout>
