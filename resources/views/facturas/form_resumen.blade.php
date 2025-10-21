<form action="{{ route('facturas.pdf.resumen') }}" method="GET" class="mb-6 flex flex-wrap gap-4 items-end">
    <div class="w-1/2">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha Inicio</label>
        <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-background-dark/50 dark:border-gray-600 dark:text-white">
    </div>

    <div class="w-1/2">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha Fin</label>
        <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-background-dark/50 dark:border-gray-600 dark:text-white">
    </div>

    <div class="w-full flex gap-2 flex-wrap mt-2">
        <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
            Generar PDF
        </button>
    </div>

    {{-- Botones rápidos --}}
    <div class="mt-4 flex flex-wrap gap-2">
        <a href="{{ route('facturas.pdf.resumen', ['periodo' => 'dia']) }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
            PDF Día
        </a>
        <a href="{{ route('facturas.pdf.resumen', ['periodo' => 'semana']) }}"
           class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
            PDF Semana
        </a>
        <a href="{{ route('facturas.pdf.resumen', ['periodo' => 'mes']) }}"
           class="px-4 py-2 bg-yellow-600 text-white rounded-lg shadow hover:bg-yellow-700 transition">
            PDF Mes
        </a>
        <a href="{{ route('facturas.pdf.resumen', ['periodo' => 'anio']) }}"
           class="px-4 py-2 bg-red-600 text-white rounded-lg shadow hover:bg-red-700 transition">
            PDF Año
        </a>
        <a href="{{ route('facturas.pdf.resumen', ['periodo' => 'general']) }}"
           class="px-4 py-2 bg-gray-600 text-white rounded-lg shadow hover:bg-gray-700 transition">
            PDF Total
        </a>
    </div>
</form>
