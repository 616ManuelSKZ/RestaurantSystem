<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Categorias') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div>
                        <a href="{{ route('categorias.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
                            Crear Categoría
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200" style="table-layout: fixed;">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b text-center" style="width: 15%;">ID</th>
                                    <th class="py-2 px-4 border-b text-left" style="width: 55%;">Nombre</th>
                                    <th class="py-2 px-4 border-b text-center" style="width: 30%;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categorias as $categoria)
                                    <tr>
                                        <td class="py-2 px-4 border-b text-center align-middle">{{ $categoria->id }}</td>
                                        <td class="py-2 px-4 border-b align-middle">{{ $categoria->nombre }}</td>
                                        <td class="py-2 px-4 border-b text-center align-middle">
                                            <!-- Editar -->
                                            <a href="{{ route('categorias.edit', $categoria) }}" class=" inline-block">
                                                <lord-icon
                                                    src="https://cdn.lordicon.com/cbtlerlm.json"
                                                    trigger="hover"
                                                    colors="primary:#fbbf24"
                                                    style="width:40px;height:40px">
                                                </lord-icon>
                                            </a>

                                            <!-- Eliminar -->
                                            <form action="{{ route('categorias.destroy', $categoria) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta categoría?');">
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>