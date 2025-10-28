<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
            Crear Factura
        </h2>
    </x-slot>

    <main class="flex-1 p-8 bg-gray-50 dark:bg-background-dark/20 min-h-screen">

        <div
            class="max-w-4xl mx-auto bg-white dark:bg-background-dark/50 shadow-sm rounded-xl border border-primary/20 dark:border-primary/30 p-6">

            <form action="{{ route('facturas.store') }}" method="POST">
                @csrf

                <input type="hidden" name="id_orden" value="{{ $orden->id }}">
                <input type="hidden" name="id_users" value="{{ auth()->user()->id }}">

                {{-- Tipo de factura --}}
                <div class="mb-6">
                    <label for="tipo_factura" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                        Tipo de Factura
                    </label>
                    <select name="tipo_factura" id="tipo_factura"
                        class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white dark:bg-background-dark/70 text-gray-900 dark:text-gray-100">
                        <option value="">-- Selecciona un tipo --</option>
                        <option value="Consumidor Final">Consumidor Final</option>
                        <option value="Crédito Fiscal">Crédito Fiscal</option>
                    </select>
                    @error('tipo_factura')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Datos fiscales del cliente --}}
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2 mt-6">
                    Datos Fiscales del Cliente
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nombre_cliente"
                            class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">Nombre / Razón
                            Social</label>
                        <input type="text" name="nombre_cliente" id="nombre_cliente" value="{{ old('nombre_cliente') }}"
                            class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm px-3 py-2 bg-white dark:bg-background-dark/70 text-gray-900 dark:text-gray-100">
                        @error('nombre_cliente')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nit_cliente" class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">NIT /
                            ID Fiscal</label>
                        <input type="text" name="nit_cliente" id="nit_cliente" value="{{ old('nit_cliente') }}"
                            class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm px-3 py-2 bg-white dark:bg-background-dark/70 text-gray-900 dark:text-gray-100">
                        @error('nit_cliente')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="direccion_cliente"
                            class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">Dirección</label>
                        <input type="text" name="direccion_cliente" id="direccion_cliente"
                            value="{{ old('direccion_cliente') }}"
                            class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm px-3 py-2 bg-white dark:bg-background-dark/70 text-gray-900 dark:text-gray-100">
                        @error('direccion_cliente')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="telefono_cliente"
                            class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">Teléfono</label>
                        <input type="text" name="telefono_cliente" id="telefono_cliente"
                            value="{{ old('telefono_cliente') }}"
                            class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm px-3 py-2 bg-white dark:bg-background-dark/70 text-gray-900 dark:text-gray-100">
                        @error('telefono_cliente')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Detalles de la orden --}}
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">
                        Detalles de la Orden #{{ $orden->id }}
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left border-collapse">
                            <thead
                                class="bg-primary/5 dark:bg-primary/10 text-gray-600 dark:text-gray-300 uppercase text-xs">
                                <tr>
                                    <th class="px-6 py-3 font-medium text-center">Producto</th>
                                    <th class="px-6 py-3 font-medium text-center">Cantidad</th>
                                    <th class="px-6 py-3 font-medium text-right">Precio Unitario</th>
                                    <th class="px-6 py-3 font-medium text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-primary/10 dark:divide-primary/20">
                                @foreach ($orden->detalles_orden as $detalle)
                                <tr class="hover:bg-primary/5 dark:hover:bg-primary/10 transition">
                                    <td class="px-6 py-4 text-gray-800 dark:text-gray-300">
                                        {{ $detalle->nombre_menu ?? 'Sin nombre' }}</td>
                                    <td class="px-6 py-4 text-center text-gray-700 dark:text-gray-300">
                                        {{ $detalle->cantidad }}</td>
                                    <td class="px-6 py-4 text-right text-gray-800 dark:text-gray-300">
                                        ${{ number_format($detalle->precio_unitario, 2) }}</td>
                                    <td class="px-6 py-4 text-right font-semibold text-gray-900 dark:text-white">
                                        ${{ number_format($detalle->subtotal, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-primary/5 dark:bg-primary/10">
                                    <td colspan="3" class="text-right font-semibold px-6 py-3">Subtotal:</td>
                                    <td class="px-6 py-3 text-right font-semibold text-gray-900 dark:text-white">
                                        ${{ number_format($orden->subtotal, 2) }}
                                    </td>
                                </tr>
                                <tr class="bg-primary/5 dark:bg-primary/10">
                                    <td colspan="3" class="text-right font-semibold px-6 py-3">Impuestos (13%):</td>
                                    <td class="px-6 py-3 text-right font-semibold text-gray-900 dark:text-white">
                                        ${{ number_format($orden->impuestos, 2) }}
                                    </td>
                                </tr>
                                <tr class="bg-primary/5 dark:bg-primary/10">
                                    <td colspan="3" class="text-right font-semibold px-6 py-3">Total:</td>
                                    <td class="px-6 py-3 text-right font-semibold text-gray-900 dark:text-white">
                                        ${{ number_format($orden->totaliva, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Botón Generar Factura --}}
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm transition">
                        Generar Factura
                    </button>
                </div>

            </form>

        </div>

    </main>
</x-app-layout>