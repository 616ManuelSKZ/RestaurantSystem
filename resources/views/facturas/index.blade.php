<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ __('Facturas') }}
        </h2>
    </x-slot>

    <main class="flex-1 p-8 bg-gray-50 dark:bg-background-dark/20 min-h-screen">

        {{-- 🔹 Tabla de Facturas --}}
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
                                    {{ $factura->tipo_factura }}
                                </td>
                                <td class="px-6 py-4 text-center text-gray-700 dark:text-gray-300">
                                    {{ $factura->fecha_emision }}
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
            @endif
        </div>

    </main>
</x-app-layout>
