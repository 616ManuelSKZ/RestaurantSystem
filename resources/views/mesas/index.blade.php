<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-text-light dark:text-text-dark leading-tight">
            {{ __('Áreas y Mesas') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-6 bg-surface-light dark:bg-surface-dark rounded-2xl shadow-lg border border-border-light dark:border-border-dark transition-colors duration-300">

        {{-- 🔹 Botón para agregar nueva área --}}
        <div class="flex justify-end mb-8">
            <a href="{{ route('area_mesas.create') }}"
                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold shadow-sm transition">
                + Agregar Área
            </a>
        </div>

        {{-- 🔹 Listado de Áreas --}}
        @foreach($areas as $area)
            <div class="mb-10 border border-primary/20 dark:border-primary/30 rounded-2xl shadow-sm overflow-hidden bg-white dark:bg-background-dark/50">

                {{-- Header del Área --}}
                <div class="flex justify-between items-center px-6 py-4 bg-primary/5 dark:bg-primary/10 border-b border-primary/20 dark:border-primary/30">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                        {{ $area->nombre }}
                    </h3>
                    <a href="{{ route('area_mesas.edit', $area->id) }}"
                        class="px-3 py-1 text-xs font-semibold rounded-lg bg-yellow-500 hover:bg-yellow-600 text-white shadow-sm transition">
                        Editar Área
                    </a>
                    <form action="{{ route('area_mesas.destroy', $area->id) }}" method="POST"
                        onsubmit="return confirm('¿Deseas eliminar esta área y sus mesas?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-3 py-1 text-xs font-semibold rounded-lg bg-red-600 hover:bg-red-700 text-white shadow-sm transition">
                            Eliminar
                        </button>
                    </form>
                    <a href="{{ route('mesas.create', ['area' => $area->id]) }}"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow-sm transition">
                        + Agregar Mesa
                    </a>
                </div>

                {{-- Tabla de Mesas --}}
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

                                        {{-- Acciones --}}
                                        <td class="py-3 px-6 text-center space-x-3">
                                            <a href="{{ route('mesas.edit', $mesa) }}"
                                                class="inline-block px-3 py-1 text-xs font-semibold rounded-lg bg-yellow-500 hover:bg-yellow-600 text-white shadow-sm transition">
                                                Editar
                                            </a>

                                            <form action="{{ route('mesas.destroy', $mesa) }}" method="POST"
                                                  class="inline-block"
                                                  onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta mesa?');">
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
                        No hay mesas registradas en esta área.
                    </p>
                @endif
            </div>
        @endforeach
    </div>
</x-app-layout>
