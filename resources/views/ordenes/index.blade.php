<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg md:text-2xl font-semibold text-gray-900 dark:text-white">
            {{ __('Órdenes') }}
        </h2>
    </x-slot>

    @php
    $rol = auth()->user()->rol;
    @endphp

    <main class="flex-1 p-4 md:p-8 bg-gray-50 dark:bg-background-dark/20 min-h-screen">

        @if ($rol === 'mesero')
        {{-- Botón Nueva Orden --}}
        <div class="mb-6 md:mb-8 flex justify-start">
            <a href="{{ route('ordenes.create') }}"
                class="inline-flex items-center bg-primary hover:bg-primary/90 text-white font-semibold py-2 px-4 rounded-lg shadow-sm transition">
                + Nueva Orden
            </a>
        </div>
        @endif

        {{-- Tabla de Órdenes para desktop --}}
        <div class="hidden md:block bg-white dark:bg-background-dark/50 rounded-xl border border-primary/20 dark:border-primary/30 overflow-hidden shadow-sm">
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
                            Mesa {{ $orden->mesa->numero ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-gray-800 dark:text-gray-300 text-center">
                            {{ $orden->user->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @switch($orden->estado)
                            @case('En Preparación')
                            <span
                                class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300">En
                                Preparación</span>
                            @break
                            @case('Lista')
                            <span
                                class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">Lista</span>
                            @break
                            @case('Servida')
                            <span
                                class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300">Servida</span>
                            @break
                            @case('Completada')
                            <span
                                class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">Completada</span>
                            @break
                            @case('Cancelada')
                            <span
                                class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">Cancelada</span>
                            @break
                            @default
                            <span
                                class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300">Finalizada</span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 text-center text-gray-700 dark:text-gray-300">
                            <div class="font-semibold text-indigo-600 dark:text-indigo-400">
                                {{ \Carbon\Carbon::parse($orden->fecha_orden)->format('H:i') }}
                            </div>
                            <div class="text-gray-600 dark:text-gray-300 text-sm">
                                {{ \Carbon\Carbon::parse($orden->fecha_orden)->format('d/m/Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right font-semibold text-gray-900 dark:text-white">
                            ${{ number_format($orden->totaliva, 2) }}
                        </td>
                        <td class="px-6 py-4 text-center space-x-2">
                            @if(in_array($rol, ['mesero', 'cocinero']))
                            {{-- Ver --}}
                            <a href="{{ route('ordenes.show', $orden->id) }}"
                                class="inline-block px-3 py-1 text-xs font-semibold rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition">
                                Ver
                            </a>
                            @endif

                            {{-- Editar --}}
                            @if($orden->estado !== 'Completada' && $orden->estado !== 'Cancelada' && $rol === 'mesero')
                            <form action="{{ route('ordenes.edit', $orden->id) }}" method="GET" class="inline-block">
                                <button type="submit"
                                    class="px-3 py-1 text-xs font-semibold rounded-lg bg-yellow-500 hover:bg-yellow-600 text-white shadow-sm transition">
                                    Editar
                                </button>
                            </form>
                            @endif

                            {{-- Completar --}}
                            @if($orden->estado === 'Servida' && $rol === 'mesero')
                            <div x-data="{ modalCompletar: false }" class="inline-block">
                                <button @click="modalCompletar = true"
                                    class="px-3 py-1 text-xs font-semibold rounded-lg bg-green-600 hover:bg-green-700 text-white shadow-sm transition">
                                    Completar
                                </button>

                                {{-- Modal --}}
                                <div x-show="modalCompletar" x-cloak
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                    x-transition>
                                    <div
                                        class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md mx-4 relative">
                                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                                            Confirmar finalización
                                        </h2>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                                            ¿Estás seguro de que deseas marcar esta orden como <span
                                                class="font-semibold">completada</span>?
                                            Esta acción no se puede deshacer.
                                        </p>

                                        <div class="flex justify-end gap-3">
                                            <button @click="modalCompletar = false" type="button"
                                                class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                                Cancelar
                                            </button>

                                            <form action="{{ route('ordenes.finalizar', $orden->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold transition">
                                                    Confirmar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- Facturar --}}
                            @if(!$orden->factura && $orden->estado === 'Completada' && $rol === 'cajero')
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
            {{-- Paginación --}}
            <div class="p-4">
                {{ $ordenes->links() }}
            </div>
        </div>

        {{-- Cards de Órdenes para móviles --}}
        <div class="md:hidden space-y-4">
            @forelse($ordenes as $orden)
                <div class="bg-white dark:bg-background-dark/50 rounded-xl border border-primary/20 dark:border-primary/30 shadow-sm p-4">
                    <div class="flex justify-between items-start mb-3">
                        <span class="font-semibold text-gray-900 dark:text-white">#{{ $orden->id }}</span>
                        @switch($orden->estado)
                        @case('En Preparación')
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300">En Preparación</span>
                        @break
                        @case('Lista')
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">Lista</span>
                        @break
                        @case('Servida')
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300">Servida</span>
                        @break
                        @case('Completada')
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">Completada</span>
                        @break
                        @case('Cancelada')
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">Cancelada</span>
                        @break
                        @default
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300">Finalizada</span>
                        @endswitch
                    </div>
                    <div class="space-y-1 text-sm mb-4">
                        <p><strong>Área:</strong> {{ $orden->mesa->area->nombre ?? 'N/A' }}</p>
                        <p><strong>Mesa:</strong> Mesa {{ $orden->mesa->numero ?? 'N/A' }}</p>
                        <p><strong>Usuario:</strong> {{ $orden->user->name ?? 'N/A' }}</p>
                        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($orden->fecha_orden)->format('d/m/Y H:i') }}</p>
                        <p><strong>Total:</strong> ${{ number_format($orden->totaliva, 2) }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @if(in_array($rol, ['mesero', 'cocinero']))
                        <a href="{{ route('ordenes.show', $orden->id) }}"
                            class="px-3 py-1 text-xs font-semibold rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition">
                            Ver
                        </a>
                        @endif
                        @if($orden->estado !== 'Completada' && $orden->estado !== 'Cancelada' && $rol === 'mesero')
                        <form action="{{ route('ordenes.edit', $orden->id) }}" method="GET" class="inline-block">
                            <button type="submit"
                                class="px-3 py-1 text-xs font-semibold rounded-lg bg-yellow-500 hover:bg-yellow-600 text-white shadow-sm transition">
                                Editar
                            </button>
                        </form>
                        @endif
                        @if($orden->estado === 'Servida' && $rol === 'mesero')
                        <div x-data="{ modalCompletar: false }" class="inline-block">
                            <button @click="modalCompletar = true"
                                class="px-3 py-1 text-xs font-semibold rounded-lg bg-green-600 hover:bg-green-700 text-white shadow-sm transition">
                                Completar
                            </button>
                            {{-- Modal --}}
                            <div x-show="modalCompletar" x-cloak
                                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                x-transition>
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md mx-4 relative">
                                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                                        Confirmar finalización
                                    </h2>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                                        ¿Estás seguro de que deseas marcar esta orden como <span
                                            class="font-semibold">completada</span>?
                                        Esta acción no se puede deshacer.
                                    </p>
                                    <div class="flex justify-end gap-3">
                                        <button @click="modalCompletar = false" type="button"
                                            class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                            Cancelar
                                        </button>
                                        <form action="{{ route('ordenes.finalizar', $orden->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold transition">
                                                Confirmar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(!$orden->factura && $orden->estado === 'Completada' && $rol === 'cajero')
                        <a href="{{ route('facturas.create', $orden->id) }}"
                            class="px-3 py-1 text-xs font-semibold rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm transition">
                            Facturar
                        </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-background-dark/50 rounded-xl border border-primary/20 dark:border-primary/30 shadow-sm p-4 text-center text-gray-500 dark:text-gray-400">
                    No hay órdenes registradas.
                </div>
            @endforelse
            {{-- Paginación --}}
            <div class="mt-6">
                {{ $ordenes->links() }}
            </div>
        </div>
    </main>
</x-app-layout>
