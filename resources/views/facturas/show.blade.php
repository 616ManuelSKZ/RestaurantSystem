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
                <p>
                    <strong class="text-gray-700 dark:text-gray-300">Cliente:</strong> 
                    <span class="text-gray-900 dark:text-white">{{ $factura->nombre_cliente ?? 'Sin cliente' }}</span>
                </p>
                <p>
                    <strong class="text-gray-700 dark:text-gray-300">NIT / ID Fiscal:</strong> 
                    <span class="text-gray-900 dark:text-white">{{ $factura->nit_cliente ?? 'N/A' }}</span>
                </p>
                <p>
                    <strong class="text-gray-700 dark:text-gray-300">Dirección:</strong> 
                    <span class="text-gray-900 dark:text-white">{{ $factura->direccion_cliente ?? 'N/A' }}</span>
                </p>
                <p>
                    <strong class="text-gray-700 dark:text-gray-300">Teléfono:</strong> 
                    <span class="text-gray-900 dark:text-white">{{ $factura->telefono_cliente ?? 'N/A' }}</span>
                </p>
                <p>
                    <strong class="text-gray-700 dark:text-gray-300">Mesa:</strong> 
                    <span class="text-gray-900 dark:text-white">{{ $factura->orden->mesa->numero ?? 'Sin mesa' }}</span>
                </p>
                <p>
                    <strong class="text-gray-700 dark:text-gray-300">Mesero:</strong> 
                    <span class="text-gray-900 dark:text-white">{{ $factura->orden->user->name ?? 'Sin mesero' }}</span>
                </p>
                <p>
                    <strong class="text-gray-700 dark:text-gray-300">Fecha de emisión:</strong> 
                    <span class="text-gray-900 dark:text-white">{{ $factura->fecha_emision ?? 'N/A' }}</span>
                </p>
                <p>
                    <strong class="text-gray-700 dark:text-gray-300">Tipo de factura:</strong> 
                    <span class="text-gray-900 dark:text-white">{{ $factura->tipo_factura ?? 'N/A' }}</span>
                </p>
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
                            <th class="px-6 py-3 font-medium text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-primary/10 dark:divide-primary/20">
                        @foreach ($factura->orden->detalles_orden as $detalle)
                            <tr class="hover:bg-primary/5 dark:hover:bg-primary/10 transition">
                                <td class="px-6 py-4 text-gray-800 dark:text-gray-300">{{ $detalle->nombre_menu ?? 'Sin nombre' }}</td>
                                <td class="px-6 py-4 text-center text-gray-700 dark:text-gray-300">{{ $detalle->cantidad }}</td>
                                <td class="px-6 py-4 text-right text-gray-800 dark:text-gray-300">
                                    ${{ number_format($detalle->precio_unitario, 2) }}
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900 dark:text-white">
                                    ${{ number_format($detalle->subtotal, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- 🔹 Totales --}}
            <div class="mt-6 text-right">
                <p class="text-gray-700 dark:text-gray-300">Subtotal: ${{ number_format($factura->subtotal, 2) }}</p>
                <p class="text-gray-700 dark:text-gray-300">Impuestos (13%): ${{ number_format($factura->impuestos, 2) }}</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">Total: ${{ number_format($factura->totaliva, 2) }}</p>
            </div>

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
