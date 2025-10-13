<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <main class="flex-1 p-8 bg-gray-50 dark:bg-background-dark/20 min-h-screen">

        {{-- 🔹 Tarjetas resumen --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-background-dark/50 p-6 rounded-xl border border-primary/20 dark:border-primary/30 shadow-sm">
                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Platos</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalPlatos }}</p>
            </div>

            <div class="bg-white dark:bg-background-dark/50 p-6 rounded-xl border border-primary/20 dark:border-primary/30 shadow-sm">
                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Salas</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalSalas }}</p>
            </div>

            <div class="bg-white dark:bg-background-dark/50 p-6 rounded-xl border border-primary/20 dark:border-primary/30 shadow-sm">
                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Usuarios</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalUsuarios }}</p>
            </div>

            <div class="bg-white dark:bg-background-dark/50 p-6 rounded-xl border border-primary/20 dark:border-primary/30 shadow-sm">
                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Pedidos</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalPedidos }}</p>
            </div>
        </div>

        {{-- 🔹 Total de ventas con gráfico --}}
        <div class="mb-8">
            <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Total Ventas</h3>
            <div class="bg-white dark:bg-background-dark/50 rounded-xl border border-primary/20 dark:border-primary/30 shadow-sm p-6">
                <p class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                    ${{ number_format($totalVentas, 2) }}
                </p>
                <canvas id="salesChart" height="100"></canvas>
            </div>
        </div>

        {{-- 🔹 Tabla de órdenes activas --}}
        <div class="mb-8">
            <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Órdenes Activas</h3>
            <div class="bg-white dark:bg-background-dark/50 rounded-xl border border-primary/20 dark:border-primary/30 overflow-hidden shadow-sm">
                <table class="w-full text-sm text-left">
                    <thead class="bg-primary/5 dark:bg-primary/10 text-gray-600 dark:text-gray-300 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3 font-medium">#</th>
                            <th class="px-6 py-3 font-medium">Mesa</th>
                            <th class="px-6 py-3 font-medium">Empleado</th>
                            <th class="px-6 py-3 font-medium text-center">Fecha</th>
                            <th class="px-6 py-3 font-medium text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-primary/10 dark:divide-primary/20">
                        @forelse($ordenes as $orden)
                            <tr class="hover:bg-primary/5 dark:hover:bg-primary/10">
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">#{{ $orden->id }}</td>
                                <td class="px-6 py-4">Mesa {{ $orden->mesa->numero }}</td>
                                <td class="px-6 py-4">{{ $orden->user->name ?? '—' }}</td>
                                <td class="px-6 py-4 text-center">{{ $orden->fecha_orden}}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($orden->estado === 'En Preparación')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                                    @elseif($orden->estado === 'Lista')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">En Preparación</span>
                                    @elseif($orden->estado === 'Servida')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">Servida</span>
                                    @elseif($orden->estado === 'Completada')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Completada</span>
                                    @elseif($orden->estado === 'Cancelada')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Cancelada</span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Finalizada</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    No hay órdenes activas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 🔹 Estado de Mesas --}}
        <div>
            <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Estado de Mesas</h3>
            <div class="bg-white dark:bg-background-dark/50 rounded-xl border border-primary/20 dark:border-primary/30 overflow-hidden shadow-sm">
                <table class="w-full text-sm text-left">
                    <thead class="bg-primary/5 dark:bg-primary/10 text-gray-600 dark:text-gray-300 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3 font-medium">Mesa</th>
                            <th class="px-6 py-3 font-medium text-center">Capacidad</th>
                            <th class="px-6 py-3 font-medium text-center">Estado</th>
                            <th class="px-6 py-3 font-medium">Orden</th>
                            <th class="px-6 py-3 font-medium">Cliente</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-primary/10 dark:divide-primary/20">
                        @forelse($mesas as $mesa)
                            @php
                                $ordenActiva = $ordenes->firstWhere('id_mesas', $mesa->id);
                            @endphp
                            <tr class="hover:bg-primary/5 dark:hover:bg-primary/10">
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">Mesa {{ $mesa->numero }}</td>
                                <td class="px-6 py-4 text-center">{{ $mesa->capacidad }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($mesa->estado === 'disponible')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-primary/20 text-primary">Disponible</span>
                                    @elseif($mesa->estado === 'ocupada')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">Ocupada</span>
                                    @elseif($mesa->estado === 'reservada')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300">Reservada</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    {{ $ordenActiva ? '#'.$ordenActiva->id : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $ordenActiva && $ordenActiva->usuario ? $ordenActiva->usuario->name : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    No hay mesas registradas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    {{-- 📊 Chart.js --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('salesChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($ventasPorMes->pluck('mes')),
                    datasets: [{
                        label: 'Ventas por mes',
                        data: @json($ventasPorMes->pluck('total')),
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1,
                        borderRadius: 6
                    }]
                },
                options: {
                    scales: { y: { beginAtZero: true } },
                    plugins: { legend: { display: false } }
                }
            });
        </script>
    @endpush
</x-app-layout>
