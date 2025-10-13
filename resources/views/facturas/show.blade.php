<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
            Factura #{{ $factura->id }}
        </h2>
    </x-slot>

    <main class="flex-1 p-8 bg-gray-50 dark:bg-background-dark/20 min-h-screen">

        <div class="bg-white dark:bg-background-dark/50 shadow-sm rounded-xl border border-primary/20 dark:border-primary/30 p-6">
            
            {{-- 🔹 Información General --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <p><strong class="text-gray-700 dark:text-gray-300">Cliente:</strong> <span class="text-gray-900 dark:text-white">{{ $factura->user->name ?? 'Sin cliente' }}</span></p>
                <p><strong class="text-gray-700 dark:text-gray-300">Mesa:</strong> <span class="text-gray-900 dark:text-white">{{ $factura->orden->mesa->numero ?? 'Sin mesa' }}</span></p>
                <p><strong class="text-gray-700 dark:text-gray-300">Fecha de emisión:</strong> <span class="text-gray-900 dark:text-white">{{ $factura->fecha_emision }}</span></p>
                <p><strong class="text-gray-700 dark:text-gray-300">Tipo de factura:</strong> <span class="text-gray-900 dark:text-white">{{ $factura->tipo_factura }}</span></p>
            </div>

            {{-- 🔹 Detalles de la factura --}}
            <h3 class="mt-4 mb-2 font-semibold text-gray-800 dark:text-gray-200">Detalles de la factura</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="bg-primary/5 dark:bg-primary/10 text-gray-600 dark:text-gray-300 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3 font-medium text-center">Producto</th>
                            <th class="px-6 py-3 font-medium text-center">Cantidad</th>
                            <th class="px-6 py-3 font-medium text-right">Precio Unitario</th>
                            <th class="px-6 py-3 font-medium text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-primary/10 dark:divide-primary/20">
                        @foreach ($factura->detalles_factura as $detalle)
                            <tr class="hover:bg-primary/5 dark:hover:bg-primary/10 transition">
                                <td class="px-6 py-4 text-gray-800 dark:text-gray-300">{{ $detalle->nombre_menu }}</td>
                                <td class="px-6 py-4 text-center text-gray-700 dark:text-gray-300">{{ $detalle->cantidad }}</td>
                                <td class="px-6 py-4 text-right text-gray-800 dark:text-gray-300">${{ number_format($detalle->precio_unitario, 2) }}</td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900 dark:text-white">
                                    ${{ number_format($detalle->precio_menu * $detalle->cantidad, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- 🔹 Total --}}
            <p class="mt-6 text-right font-semibold text-lg text-gray-900 dark:text-white">
                Total: ${{ number_format($factura->total, 2) }}
            </p>

            {{-- 🔹 Botones de descarga --}}
            <div class="mt-4 flex gap-4 justify-end">
                <a href="{{ route('facturas.exportPDF', $factura) }}" 
                   class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-sm transition">
                    Descargar PDF
                </a>
                <a href="{{ route('facturas.exportXML', $factura) }}" 
                   class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg shadow-sm transition">
                    Descargar XML
                </a>
            </div>
        </div>

    </main>
</x-app-layout>
