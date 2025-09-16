<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Menus') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <a href="{{ route('categorias.create') }}" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded mb-6 inline-block">
            Agregar Categoría
        </a>
    </div>

    <div class="py-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach($categorias as $categoria)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $categoria->nombre }}</h3>
                            <a href="{{ route('menus.create', ['categoria' => $categoria->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Agregar Menú
                            </a>
                        </div>

                        @if ($categoria->menus->count())
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border border-gray-200" style="table-layout: fixed;">
                                    <thead>
                                        <tr>
                                            <th class="py-2 px-4 border-b" style="width: 110px; text-align: center;">Foto</th>
                                            <th class="py-2 px-4 border-b" style="width: 120px; text-align: left;">Nombre</th>
                                            <th class="py-2 px-4 border-b" style="width: 200px; text-align: left;">Descripción</th>
                                            <th class="py-2 px-4 border-b" style="width: 80px; text-align: right;">Precio</th>
                                            <th class="py-2 px-4 border-b" style="width: 140px; text-align: center;">Disponibilidad</th>
                                            <th class="py-2 px-4 border-b" style="width: 140px; text-align: center;">Fecha de Creación</th>
                                            <th class="py-2 px-4 border-b" style="width: 120px; text-align: center;">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categoria->menus as $menu)
                                            <tr>
                                                <td class="py-2 px-4 border-b text-center">
                                                    @if ($menu->imagen)
                                                        <img src="{{ asset('storage/' . $menu->imagen) }}" style="width: 100px; height: auto; object-fit: contain;">
                                                    @endif
                                                </td>
                                                <td class="py-2 px-4 border-b">{{ $menu->nombre }}</td>
                                                <td class="py-2 px-4 border-b">{{ $menu->descripcion }}</td>
                                                <td class="py-2 px-4 border-b text-right">{{ $menu->precio }}</td>
                                                <td class="py-2 px-4 border-b text-center">
                                                    @if (Auth::user()?->rol === 'administrador')
                                                        <input type="checkbox"
                                                            data-id="{{ $menu->id }}"
                                                            class="toggle-disponible form-checkbox h-5 w-5 text-green-600"
                                                            {{ $menu->disponible ? 'checked' : '' }}>
                                                    @else
                                                        {{ $menu->disponible ? 'Disponible' : 'No Disponible' }}
                                                    @endif
                                                </td>
                                                <td class="py-2 px-4 border-b text-center">
                                                    {{ $menu->created_at->format('d/m/Y') }}
                                                </td>
                                                <td class="py-2 px-4 border-b text-center">
                                                    <a href="{{ route('menus.edit', $menu) }}" class="inline-block">
                                                        <lord-icon
                                                            src="https://cdn.lordicon.com/cbtlerlm.json"
                                                            trigger="hover"
                                                            colors="primary:#fbbf24"
                                                            style="width:40px;height:40px">
                                                        </lord-icon>
                                                    </a>
                                                    <form action="{{ route('menus.destroy', $menu) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este menú?');">
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
                        @else
                            <p class="text-gray-600">No hay menús registrados en esta categoría.</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const switches = document.querySelectorAll('.toggle-disponible');

            switches.forEach(switchEl => {
                switchEl.addEventListener('change', function () {
                    const menuId = this.getAttribute('data-id');
                    const isChecked = this.checked;

                    fetch("{{ route('menus.toggleDisponibilidad') }}", {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ id: menuId })
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Fallo la actualización');
                        return response.json();
                    })
                    .then(data => {
                        console.log('Estado actualizado:', data.estado ? 'Disponible' : 'No Disponible');
                    })
                    .catch(error => {
                        alert('Error al actualizar disponibilidad.');
                        this.checked = !isChecked; // Revertir cambio si falla
                    });
                });
            });
        });
    </script>

</x-app-layout>
