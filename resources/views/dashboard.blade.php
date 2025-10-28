<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg md:text-2xl font-semibold text-gray-900 dark:text-white">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @php
        $rol = auth()->user()->rol;
    @endphp

    <main class="flex-1 p-4 md:p-8 bg-gray-50 dark:bg-background-dark/20 min-h-screen">

        {{-- Tarjetas resumen --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
            <div class="bg-white dark:bg-background-dark/50 p-4 md:p-6 rounded-xl border border-primary/20 dark:border-primary/30 shadow-sm">
                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Platos</p>
                <p class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalPlatos }}</p>
            </div>

            <div class="bg-white dark:bg-background-dark/50 p-4 md:p-6 rounded-xl border border-primary/20 dark:border-primary/30 shadow-sm">
                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Salas</p>
                <p class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalSalas }}</p>
            </div>

            <div class="bg-white dark:bg-background-dark/50 p-4 md:p-6 rounded-xl border border-primary/20 dark:border-primary/30 shadow-sm">
                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Usuarios</p>
                <p class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalUsuarios }}</p>
            </div>

            <div class="bg-white dark:bg-background-dark/50 p-4 md:p-6 rounded-xl border border-primary/20 dark:border-primary/30 shadow-sm">
                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Pedidos</p>
                <p class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalPedidos }}</p>
            </div>
        </div>

        @if($rol === 'administrador' || $rol === 'cajero')
            {{-- Total de ventas con gráfico --}}
            <div class="mb-6 md:mb-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2">
                    <h3 class="text-lg md:text-xl font-semibold text-gray-900 dark:text-white">Total Ventas</h3>
                    <select id="filtroVentas" class="w-full sm:w-auto rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-3 py-1 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="dia" selected>Hoy</option>
                        <option value="semana">Esta semana</option>
                        <option value="mes">Este mes</option>
                        <option value="anio">Este año</option>
                        <option value="total">Total</option>
                    </select>
                </div>

                <div class="bg-white dark:bg-background-dark/50 rounded-xl border border-primary/20 dark:border-primary/30 shadow-sm p-4 md:p-6">
                    <p id="totalVentas" class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white mb-4">
                        ${{ number_format($totalVentas, 2) }}
                    </p>
                    <canvas id="salesChart" height="100"></canvas>
                </div>
            </div>
        @endif

        {{-- Tabla de órdenes activas --}}
        <div class="mb-6 md:mb-8">
            <h3 class="text-lg md:text-xl font-semibold mb-4 text-gray-900 dark:text-white">Órdenes Activas</h3>
            <div class="bg-white dark:bg-background-dark/50 rounded-xl border border-primary/20 dark:border-primary/30 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left min-w-[600px]">
                        <thead class="bg-primary/5 dark:bg-primary/10 text-gray-600 dark:text-gray-300 uppercase text-xs">
                            <tr>
                                <th class="px-4 md:px-6 py-3 font-medium">#</th>
                                <th class="px-4 md:px-6 py-3 font-medium">Mesa</th>
                                <th class="px-4 md:px-6 py-3 font-medium">Empleado</th>
                                <th class="px-4 md:px-6 py-3 font-medium text-center">Fecha</th>
                                <th class="px-4 md:px-6 py-3 font-medium text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-primary/10 dark:divide-primary/20">
                            @forelse($ordenes as $orden)
                                <tr class="hover:bg-primary/5 dark:hover:bg-primary/10">
                                    <td class="px-4 md:px-6 py-4 font-semibold text-gray-900 dark:text-white">#{{ $orden->id }}</td>
                                    <td class="px-4 md:px-6 py-4">Mesa {{ $orden->mesa->numero ?? '---' }}</td>
                                    <td class="px-4 md:px-6 py-4">{{ $orden->user->name ?? '—' }}</td>
                                    <td class="px-4 md:px-6 py-4 text-center">
                                        <div class="font-semibold text-indigo-600 dark:text-indigo-400">
                                            {{ \Carbon\Carbon::parse($orden->fecha_orden)->format('H:i') }}
                                        </div>
                                        <div class="text-gray-800 dark:text-gray-300 text-sm">
                                            {{ \Carbon\Carbon::parse($orden->fecha_orden)->format('d/m/Y') }}
                                        </div>
                                    </td>
                                    <td class="px-4 md:px-6 py-4 text-center">
                                        @if($orden->estado === 'En Preparación')
                                            <span class="px-2 md:px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300">En Preparación</span>
                                        @elseif($orden->estado === 'Lista')
                                            <span class="px-2 md:px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">Lista</span>
                                        @elseif($orden->estado === 'Servida')
                                            <span class="px-2 md:px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300">Servida</span>
                                        @elseif($orden->estado === 'Completada')
                                            <span class="px-2 md:px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">Completada</span>
                                        @elseif($orden->estado === 'Cancelada')
                                            <span class="px-2 md:px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">Cancelada</span>
                                        @else
                                            <span class="px-2 md:px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300">Finalizada</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 md:px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No hay órdenes activas
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Estado de Mesas --}}
        <div>
            <h3 class="text-lg md:text-xl font-semibold mb-4 text-gray-900 dark:text-white">Estado de Mesas</h3>
            <div class="bg-white dark:bg-background-dark/50 rounded-xl border border-primary/20 dark:border-primary/30 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left min-w-[600px]">
                        <thead class="bg-primary/5 dark:bg-primary/10 text-gray-600 dark:text-gray-300 uppercase text-xs">
                            <tr>
                                <th class="px-4 md:px-6 py-3 font-medium">Mesa</th>
                                <th class="px-4 md:px-6 py-3 font-medium text-center">Capacidad</th>
                                <th class="px-4 md:px-6 py-3 font-medium text-center">Estado</th>
                                @if($rol === 'mesero' || $rol === 'cocinero')
                                    <th class="px-4 md:px-6 py-3 font-medium">Orden</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-primary/10 dark:divide-primary/20">
                            @forelse($mesas as $mesa)
                                @php
                                    $ordenActiva = $ordenes->firstWhere('id_mesas', $mesa->id);
                                @endphp
                                <tr class="hover:bg-primary/5 dark:hover:bg-primary/10">
                                    <td class="px-4 md:px-6 py-4 font-semibold text-gray-900 dark:text-white">Mesa {{ $mesa->numero ?? 'N/A' }}</td>
                                    <td class="px-4 md:px-6 py-4 text-center">{{ $mesa->capacidad ?? 'N/A' }}</td>
                                    <td class="px-4 md:px-6 py-4 text-center">
                                        @if($mesa->estado === 'disponible')
                                            <span class="px-2 md:px-3 py-1 text-xs font-semibold rounded-full bg-primary/20 text-primary">Disponible</span>
                                        @elseif($mesa->estado === 'ocupada')
                                            <span class="px-2 md:px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">Ocupada</span>
                                        @elseif($mesa->estado === 'reservada')
                                            <span class="px-2 md:px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300">Reservada</span>
                                        @endif
                                    </td>
                                    @if($rol === 'mesero' || $rol === 'cocinero')
                                        <td class="px-4 md:px-6 py-4">
                                            @if($mesa->estado === 'ocupada' && $ordenActiva)
                                                <a href="{{ route('ordenes.show', $ordenActiva->id) }}" class="text-indigo-600 hover:underline font-semibold dark:text-indigo-400">
                                                    Ver Orden #{{ $ordenActiva->id }}
                                                </a>
                                            @else
                                                <span class="text-gray-500 dark:text-gray-400">---</span>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 md:px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No hay mesas registradas
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>

    {{-- Chart.js --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Inicialización del gráfico
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($ventasPorMesChart->pluck('mes')),
                datasets: [{
                    label: 'Ventas por mes',
                    data: @json($ventasPorMesChart->pluck('total')),
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } },
                plugins: { legend: { display: false } }
            }
        });

        // Actualizar total al cambiar el select
        const filtro = document.getElementById('filtroVentas');
        const totalVentasEl = document.getElementById('totalVentas');

        filtro.addEventListener('change', function() {
            let valor = this.value;
            let total = 0;

            switch(valor) {
                case 'dia':
                    total = {{ $ventasPorDia }};
                    break;
                case 'semana':
                    total = {{ $ventasPorSemana }};
                    break;
                case 'mes':
                    total = {{ $ventasPorMes }};
                    break;
                case 'anio':
                    total = {{ $ventasPorAnio }};
                    break;
                case 'total':
                    total = {{ $totalVentas }};
                    break;
            }

            totalVentasEl.textContent = `$${total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        });
    </script>
    @endpush

</x-app-layout>
