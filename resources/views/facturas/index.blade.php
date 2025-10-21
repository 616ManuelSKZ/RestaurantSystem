<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ __('Facturas') }}
        </h2>
    </x-slot>

    @if (session('error'))
        <div class="bg-red-500 text-white px-4 py-2 rounded-lg mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if (session('warning'))
        <div class="bg-yellow-400 text-black px-4 py-2 rounded-lg mb-4">
            {{ session('warning') }}
        </div>
    @endif

    <main class="flex-1 p-8 bg-gray-50 dark:bg-background-dark/20 min-h-screen">

        {{-- Contenedor principal para filtros y botón --}}
        <div class="flex flex-wrap items-end justify-between mb-4 gap-4">

            {{-- Filtros de búsqueda --}}
            <form method="GET" action="{{ route('facturas.index') }}" class="flex flex-wrap gap-4 items-end">
                {{-- Buscar por ID --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">ID</label>
                    <input type="text" name="id" value="{{ request('id') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm dark:bg-background-dark/50 dark:border-gray-600 dark:text-white">
                </div>

                {{-- Buscar por nombre --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre Cliente</label>
                    <input type="text" name="nombre" value="{{ request('nombre') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm dark:bg-background-dark/50 dark:border-gray-600 dark:text-white">
                </div>

                {{-- Buscar por fecha --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha</label>
                    <input type="date" name="fecha" value="{{ request('fecha') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm dark:bg-background-dark/50 dark:border-gray-600 dark:text-white">
                </div>

                {{-- Botones Filtrar/Limpiar --}}
                <div class="flex gap-2">
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                        Filtrar
                    </button>

                    <a href="{{ route('facturas.index') }}"
                    class="px-4 py-2 bg-gray-400 text-white rounded-lg shadow hover:bg-gray-500 transition">
                        Limpiar
                    </a>
                </div>
            </form>

            {{-- Botón Generar Resumen PDF --}}
            <div x-data="{ modalGenerarResumen: false }">
                <button @click="modalGenerarResumen = true"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
                    Generar Resumen PDF
                </button>

                <!-- Modal Generar Resumen PDF -->
                <div x-show="modalGenerarResumen"
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
                    x-cloak x-transition>
                    <div class="bg-white dark:bg-background-dark/100 rounded-lg shadow-lg w-full max-w-md p-6"
                        @click.away="modalGenerarResumen = false" x-transition>
                        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">
                            Generar Resumen de Facturas en PDF
                        </h3>

                        {{-- Incluir formulario desde otro archivo --}}
                        @include('facturas.form_resumen')

                        {{-- Botón cerrar --}}
                        <div class="mt-6 text-right">
                            <button @click="modalGenerarResumen = false"
                                    class="px-4 py-2 bg-gray-400 text-white rounded-lg shadow hover:bg-gray-500 transition">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        {{-- Tabla de Facturas --}}
        <div class="bg-white dark:bg-background-dark/50 rounded-xl border border-primary/20 dark:border-primary/30 overflow-hidden shadow-sm">
            @if ($facturas->isEmpty())
                <p class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">
                    No hay facturas registradas.
                </p>
            @else
                <table class="w-full text-sm text-left">
                    <thead class="bg-primary/5 dark:bg-primary/10 text-gray-600 dark:text-gray-300 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3 font-medium text-center">ID</th>
                            <th class="px-6 py-3 font-medium text-center">Cliente</th>
                            <th class="px-6 py-3 font-medium text-center">Tipo</th>
                            <th class="px-6 py-3 font-medium text-center">Fecha</th>
                            <th class="px-6 py-3 font-medium text-right">Total</th>
                            <th class="px-6 py-3 font-medium text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-primary/10 dark:divide-primary/20">
                        @foreach ($facturas as $factura)
                            <tr class="hover:bg-primary/5 dark:hover:bg-primary/10 transition">
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white text-center">
                                    #{{ $factura->id }}
                                </td>
                                <td class="px-6 py-4 text-gray-800 dark:text-gray-300 text-center">
                                    {{ $factura->nombre_cliente ?? 'Sin cliente' }}
                                </td>
                                <td class="px-6 py-4 text-gray-800 dark:text-gray-300 text-center">
                                    {{ $factura->tipo_factura }}
                                </td>
                                <td class="px-6 py-4 text-center text-gray-700 dark:text-gray-300">
                                    <div class="font-semibold text-indigo-600 dark:text-indigo-400">
                                        {{ \Carbon\Carbon::parse($factura->fecha_emision)->format('H:i:s') }}
                                    </div>
                                    <div class="text-gray-800 dark:text-gray-300 text-sm">
                                        {{ \Carbon\Carbon::parse($factura->fecha_emision)->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900 dark:text-white">
                                    ${{ number_format($factura->total, 2) }}
                                </td>
                                <td class="px-6 py-4 text-center space-x-2">
                                    <a href="{{ route('facturas.show', $factura) }}"
                                       class="inline-block px-3 py-1 text-xs font-semibold rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition">
                                        Ver
                                    </a>
                                    <a href="{{ route('facturas.exportPDF', $factura) }}"
                                       class="inline-block px-3 py-1 text-xs font-semibold rounded-lg bg-red-600 hover:bg-red-700 text-white shadow-sm transition">
                                        PDF
                                    </a>
                                    <a href="{{ route('facturas.exportXML', $factura) }}"
                                       class="inline-block px-3 py-1 text-xs font-semibold rounded-lg bg-yellow-500 hover:bg-yellow-600 text-white shadow-sm transition">
                                        XML
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- Paginación --}}
                <div class="p-4">
                    {{ $facturas->links() }}
                </div>
            @endif
        </div>

    </main>
</x-app-layout>
