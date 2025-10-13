<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ __('Órdenes') }}
        </h2>
    </x-slot>

    <main class="flex-1 p-8 bg-gray-50 dark:bg-background-dark/20 min-h-screen">

        {{-- 🔹 Botón Nueva Orden --}}
        <div class="mb-8 flex justify-between items-center">
            <a href="{{ route('ordenes.create') }}"
               class="inline-flex items-center bg-primary hover:bg-primary/90 text-white font-semibold py-2 px-4 rounded-lg shadow-sm transition">
                + Nueva Orden
            </a>
        </div>

        {{-- 🔹 Tabla de Órdenes --}}
        <div class="bg-white dark:bg-background-dark/50 rounded-xl border border-primary/20 dark:border-primary/30 overflow-hidden shadow-sm">
            <table class="w-full text-sm text-left">
                <thead class="bg-primary/5 dark:bg-primary/10 text-gray-600 dark:text-gray-300 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 font-medium text-center">#</th>
                        <th class="px-6 py-3 font-medium text-center">Área</th>
                        <th class="px-6 py-3 font-medium text-center">Mesa</th>
                        <th class="px-6 py-3 font-medium text-center">Usuario</th>
                        <th class="px-6 py-3 font-medium text-center">Estado</th>
                        <th class="px-6 py-3 font-medium text-center">Fecha</th>
                        <th class="px-6 py-3 font-medium text-right">Total</th>
                        <th class="px-6 py-3 font-medium text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-primary/10 dark:divide-primary/20">
                    @forelse($ordenes as $orden)
                        <tr class="hover:bg-primary/5 dark:hover:bg-primary/10 transition">
                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white text-center">
                                #{{ $orden->id }}
                            </td>
                            <td class="px-6 py-4 text-gray-800 dark:text-gray-300 text-center">
                                {{ $orden->mesa->area->nombre ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-gray-800 dark:text-gray-300 text-center">
                                Mesa {{ $orden->mesa->numero }}
                            </td>
                            <td class="px-6 py-4 text-gray-800 dark:text-gray-300 text-center">
                                {{ $orden->user->name }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @switch($orden->estado)
                                    @case('En Preparación')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300">En Preparación</span>
                                        @break
                                    @case('Lista')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">Lista</span>
                                        @break
                                    @case('Servida')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300">Servida</span>
                                        @break
                                    @case('Completada')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">Completada</span>
                                        @break
                                    @case('Cancelada')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">Cancelada</span>
                                        @break
                                    @default
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300">Finalizada</span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4 text-center text-gray-700 dark:text-gray-300">
                                {{ $orden->fecha_orden }}
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-gray-900 dark:text-white">
                                ${{ number_format($orden->detalles_orden->sum('subtotal'), 2) }}
                            </td>
                            <td class="px-6 py-4 text-center space-x-2">
                                {{-- Ver --}}
                                <a href="{{ route('ordenes.show', $orden->id) }}"
                                   class="inline-block px-3 py-1 text-xs font-semibold rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition">
                                    Ver
                                </a>

                                {{-- Editar --}}
                                @if($orden->estado !== 'Completada' && $orden->estado !== 'Cancelada')
                                    <form action="{{ route('ordenes.edit', $orden->id) }}" method="GET" class="inline-block">
                                        <button type="submit"
                                                class="px-3 py-1 text-xs font-semibold rounded-lg bg-yellow-500 hover:bg-yellow-600 text-white shadow-sm transition">
                                            Editar
                                        </button>
                                    </form>
                                @endif

                                {{-- Completar --}}
                                @if($orden->estado === 'Servida')
                                    <form action="{{ route('ordenes.finalizar', $orden->id) }}" method="POST"
                                          class="inline-block"
                                          onsubmit="return confirm('¿Finalizar esta orden?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="px-3 py-1 text-xs font-semibold rounded-lg bg-green-600 hover:bg-green-700 text-white shadow-sm transition">
                                            Completar
                                        </button>
                                    </form>
                                @endif

                                {{-- Facturar --}}
                                @if(!$orden->factura && $orden->estado === 'Completada')
                                    <a href="{{ route('facturas.create', $orden->id) }}"
                                       class="inline-block px-3 py-1 text-xs font-semibold rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm transition">
                                        Facturar
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">
                                No hay órdenes registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</x-app-layout>
