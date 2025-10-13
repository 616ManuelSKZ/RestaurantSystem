<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Áreas de Mesas') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- Mensaje de éxito --}}
        @if(session('success'))
            <div class="mb-4 text-green-600 font-semibold">
                {{ session('success') }}
            </div>
        @endif

        {{-- Formulario de registro --}}
        <form action="{{ route('area_mesas.store') }}" method="POST" class="mb-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="nombre" class="block text-gray-700">Nombre del Área</label>
                    <input type="text" name="nombre" id="nombre"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Ejemplo: SEGUNDO PISO" value="{{ old('nombre') }}" required>
                </div>

                <div>
                    <label for="mesas" class="block text-gray-700">Número de Mesas</label>
                    <input type="number" name="mesas" id="mesas"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Ejemplo: 10" value="{{ old('mesas') }}" required min="1">
                </div>

                <div class="flex items-end">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                        Registrar
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div x-data="{ open: false, areaId: null, areaNombre: '', areaMesas: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="py-1">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        @foreach($areas as $area)
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                                <div class="p-6 bg-white border-b border-gray-200">
                                    {{-- Tabla de mesas de esta área --}}
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full bg-white border border-gray-200" style="table-layout: fixed;">
                                            <thead>
                                                <tr>
                                                    <th class="py-2 px-4 border-b" style="width: 200px; text-align: left;">Nombre Área</th>
                                                    <th class="py-2 px-4 border-b" style="width: 120px; text-align: left;">Mesas</th>
                                                    <th class="py-2 px-4 border-b" style="width: 120px; text-align: center;">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="py-2 px-4 border-b">{{ $area->nombre }}</td>
                                                    <td class="py-2 px-4 border-b text-left">{{ $area->mesas }}</td>
                                                    <td class="py-2 px-4 border-b text-center">
                                                        <button 
                                                            @click="
                                                                open = true;
                                                                areaId = {{ $area->id }};
                                                                areaNombre = '{{ $area->nombre }}';
                                                                areaMesas = {{ $area->mesas }};
                                                            "
                                                            class="inline-block"
                                                        >
                                                            <lord-icon
                                                                src="https://cdn.lordicon.com/cbtlerlm.json"
                                                                trigger="hover"
                                                                colors="primary:#fbbf24"
                                                                style="width:40px;height:40px">
                                                            </lord-icon>
                                                        </button>

                                                        <form action="{{ route('area_mesas.destroy', $area->id) }}" 
                                                            method="POST" 
                                                            class="inline-block" 
                                                            onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta área?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" style="all: unset; cursor: pointer;">
                                                                <lord-icon
                                                                    src="https://cdn.lordicon.com/egqwwrlq.json"
                                                                    trigger="hover"
                                                                    colors="primary:#646e78,secondary:#c71f16"
                                                                    style="width:40px;height:40px">
                                                                </lord-icon>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        @endforeach

                        {{-- Si no hay áreas --}}
                        @if ($areas->isEmpty())
                            <p class="text-gray-600">No hay áreas de mesas registradas.</p>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        {{-- Modal --}}
        <div 
            x-show="open" 
            class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50"
        >
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                <h2 class="text-xl font-semibold mb-4">Editar Área</h2>

                <form :action="'/area_mesas/' + areaId" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700">Nombre del Área</label>
                        <input type="text" name="nombre" x-model="areaNombre"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Número de Mesas</label>
                        <input type="number" name="mesas" x-model="areaMesas"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            min="1" required>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" 
                            @click="open = false"
                            class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
