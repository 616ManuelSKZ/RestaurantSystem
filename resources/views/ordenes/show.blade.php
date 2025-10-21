<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-text-light dark:text-text-dark leading-tight">
            Orden #{{ $orden->id }} — Mesa {{ $orden->mesa->numero }}
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto py-8 px-6 bg-surface-light dark:bg-surface-dark rounded-2xl shadow-lg border border-border-light dark:border-border-dark transition-colors duration-300">
        {{-- Información principal --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div class="space-y-2 text-base">
                <p class="text-text-light dark:text-text-dark">
                    <strong class="font-semibold text-accent-light dark:text-accent-dark">Área:</strong>
                    {{ $orden->mesa->area->nombre ?? 'No asignada' }}
                </p>
                <p class="text-text-light dark:text-text-dark">
                    <strong class="font-semibold text-accent-light dark:text-accent-dark">Mesero:</strong>
                    {{ $orden->user->name ?? 'No asignado' }}
                </p>
                <p class="text-text-light dark:text-text-dark">
                    <strong class="font-semibold text-accent-light dark:text-accent-dark">Fecha:</strong>
                    {{ \Carbon\Carbon::parse($orden->fecha_orden)->format('d/m/Y H:i') }}
                </p>
            </div>

            {{-- Estado actual --}}
            <div class="text-left md:text-right">
                <p class="text-sm text-placeholder-light dark:text-placeholder-dark mb-1">Estado actual:</p>
                <span class="px-4 py-1 rounded-full text-sm font-semibold shadow-sm
                    @if($orden->estado === 'En Preparación') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                    @elseif($orden->estado === 'Lista') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                    @elseif($orden->estado === 'Servida') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                    @elseif($orden->estado === 'Completada') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                    @elseif($orden->estado === 'Cancelada') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                    @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                    @endif">
                    {{ $orden->estado }}
                </span>
            </div>
        </div>

        {{-- Tabla de platillos --}}
        <h3 class="text-xl font-semibold text-text-light dark:text-text-dark mb-4">🍽️ Platillos de la Orden</h3>

        <div class="overflow-x-auto rounded-xl border border-border-light dark:border-border-dark shadow-sm">
            <table class="w-full text-sm text-left">
                <thead class="bg-muted-light dark:bg-muted-dark text-text-light dark:text-text-dark uppercase text-xs tracking-wide">
                    <tr>
                        <th class="px-4 py-3">Platillo</th>
                        <th class="px-4 py-3 text-center">Cantidad</th>
                        <th class="px-4 py-3 text-center">Precio Unitario</th>
                        <th class="px-4 py-3 text-center">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-light dark:divide-border-dark">
                    @foreach($orden->detalles_orden as $detalle)
                        <tr class="hover:bg-muted-light dark:hover:bg-muted-dark transition-colors duration-200">
                            <td class="px-4 py-3 flex items-center gap-3 text-text-light dark:text-text-dark">
                                @if($detalle->menu)
                                    <img 
                                        src="{{ $detalle->menu->imagen ? asset('storage/'.$detalle->menu->imagen) : asset('images/default-platillo.jpg') }}"
                                        alt="{{ $detalle->menu->nombre }}"
                                        class="w-12 h-12 rounded-lg object-cover shadow-sm"
                                    >
                                    {{ $detalle->menu->nombre }}
                                @else
                                    <img 
                                        src="{{ asset('images/default-platillo.jpg') }}"
                                        alt="Sin imagen"
                                        class="w-12 h-12 rounded-lg object-cover shadow-sm opacity-60"
                                    >
                                    <span class="italic text-gray-400">{{ $detalle->nombre_menu }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center text-text-light dark:text-text-dark">{{ $detalle->cantidad }}</td>
                            <td class="px-4 py-3 text-center text-text-light dark:text-text-dark">${{ number_format($detalle->precio_unitario, 2) }}</td>
                            <td class="px-4 py-3 text-center text-text-light dark:text-text-dark">${{ number_format($detalle->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Totales --}}
        @php
            $subtotal = $orden->detalles_orden->sum('subtotal');
            $impuesto = $subtotal * 0.13;
            $total = $subtotal + $impuesto;
        @endphp

        <div class="mt-8 text-right space-y-1 text-text-light dark:text-text-dark">
            <p><strong>Subtotal:</strong> ${{ number_format($subtotal, 2) }}</p>
            <p><strong>Impuesto (13%):</strong> ${{ number_format($impuesto, 2) }}</p>
            <p class="text-lg font-semibold text-accent-light dark:text-accent-dark"><strong>Total:</strong> ${{ number_format($total, 2) }}</p>
        </div>

        {{-- Botones de acción --}}
        <div class="mt-8 flex flex-col md:flex-row gap-4 justify-end">
            @if($orden->estado === 'En Preparación')
                <form action="{{ route('ordenes.cancelar', $orden->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition shadow">
                        Cancelar Orden
                    </button>
                </form>
                <form action="{{ route('ordenes.actualizarEstado', $orden->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="estado" value="Lista">
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition shadow">
                        Marcar como Lista
                    </button>
                </form>
            @elseif($orden->estado === 'Lista')
                <form action="{{ route('ordenes.cancelar', $orden->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition shadow">
                        Cancelar Orden
                    </button>
                </form>
                <form action="{{ route('ordenes.actualizarEstado', $orden->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="estado" value="Servida">
                    <button type="submit" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-semibold transition shadow">
                        Marcar como Servida
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
