<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nueva Orden') }}
        </h2>
    </x-slot>

    <div class="flex h-screen overflow-hidden">
        {{-- Aside: Menú de platillos --}}
        <aside class="w-80 bg-background-light dark:bg-background-dark border-r border-primary/20 dark:border-primary/30 p-4 overflow-y-auto">
    
            {{-- Buscador --}}
            <div class="mb-4 relative">
                <input type="text" placeholder="Buscar platillo..."
                    class="w-full pl-10 pr-4 py-2 rounded-lg bg-primary/10 dark:bg-primary/20 text-gray-800 dark:text-gray-200 placeholder-primary/70 dark:placeholder-primary/60 border-none focus:ring-2 focus:ring-primary">
                <span class="absolute left-3 top-2.5 text-primary">
                    🔍
                </span>
            </div>

            {{-- Pestañas de categorías --}}
            <div class="mb-4">
                <ul class="flex space-x-2 text-sm font-medium">
                    @foreach($categorias as $index => $categoria)
                        <li>
                            <button type="button" 
                                onclick="openTab({{ $index }})"
                                id="tab-btn-{{ $index }}"
                                class="px-3 py-1 rounded-lg border-b-2 border-transparent hover:border-primary focus:outline-none">
                                {{ $categoria->nombre }}
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Contenedor de platillos por categoría --}}
            @foreach($categorias as $index => $categoria)
                <div id="tab-content-{{ $index }}" class="{{ $index != 0 ? 'hidden' : '' }}">
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($categoria->menus as $menu)
                            <div class="group cursor-pointer" onclick="addPlatillo({{ $menu->id }}, '{{ $menu->nombre }}', {{ $menu->precio }})">
                                <div class="aspect-square w-full rounded-lg bg-cover bg-center"
                                    style="background-image: url('{{ $menu->imagen ? asset('storage/'.$menu->imagen) : 'https://via.placeholder.com/150' }}')">
                                </div>

                                <p class="mt-2 text-sm font-medium text-center text-gray-800 dark:text-gray-200 group-hover:text-primary">
                                    {{ $menu->nombre }}<br>${{ number_format($menu->precio, 2) }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </aside>

        {{-- Main: Formulario de orden --}}
        <main class="flex-1 flex flex-col p-6 overflow-y-auto">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Nueva Orden</h2>
            <p class="text-lg text-gray-500 dark:text-gray-400 mb-6">Selecciona área, mesa y platillos</p>

            <form action="{{ route('ordenes.store') }}" method="POST" class="bg-background-light dark:bg-background-dark rounded-xl border border-primary/20 dark:border-primary/30 shadow-sm flex-1 flex flex-col p-6">
                @csrf

                {{-- Área y Mesa --}}
                <div class="flex gap-4 mb-4">
                    <div class="flex-1">
                        <label for="id_area_mesas" class="block mb-1 text-gray-700 dark:text-gray-200">Área de mesas</label>
                        <select name="id_area_mesas" id="id_area_mesas" class="w-full border-gray-300 rounded-md bg-white text-black dark:bg-white dark:text-black" required>
                            <option value="">-- Selecciona un área --</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-1">
                        <label for="id_mesas" class="block mb-1 text-gray-700 dark:text-gray-200">Mesa disponible</label>
                        <select name="id_mesas" id="id_mesas" class="w-full border-gray-300 rounded-md bg-white text-black dark:bg-white dark:text-black" required>
                            <option value="">-- Primero selecciona un área --</option>
                        </select>
                    </div>
                </div>

                {{-- Platillos seleccionados --}}
                <div class="mb-4 flex-1">
                    <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">Platillos seleccionados</h3>
                    <div id="platillos-container" class="space-y-2 mb-4"></div>

                    {{-- Resumen de orden --}}
                    <div id="resumen-orden" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 border border-gray-200 dark:border-gray-700">
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Resumen de Orden</h4>
                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            <div class="flex justify-between py-2 text-gray-700 dark:text-gray-300">
                                <span>Subtotal</span>
                                <span id="subtotal">$0.00</span>
                            </div>
                            <div class="flex justify-between py-2 text-gray-700 dark:text-gray-300">
                                <span>Impuesto (13%)</span>
                                <span id="impuesto">$0.00</span>
                            </div>
                            <div class="flex justify-between py-2 font-semibold text-gray-900 dark:text-white">
                                <span>Total</span>
                                <span id="total">$0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="mt-auto flex justify-end gap-4">
                    <a href="{{ route('ordenes.index') }}" 
                       class="px-6 py-2 rounded-lg text-sm font-semibold bg-primary/20 hover:bg-primary/30 text-gray-800 dark:text-gray-200">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2 rounded-lg text-sm font-semibold bg-primary text-white hover:opacity-90">
                        Guardar Orden
                    </button>
                </div>
            </form>
        </main>
    </div>

    {{-- Scripts --}}
    <script>
        let count = 0;

        function actualizarResumen() {
            let subtotal = 0;
            document.querySelectorAll('#platillos-container > div').forEach(div => {
                const precio = parseFloat(div.dataset.precio);
                const cantidad = parseInt(div.querySelector('input[type=number]').value);
                subtotal += precio * cantidad;
            });
            const impuesto = subtotal * 0.13;
            const total = subtotal + impuesto;

            document.getElementById('subtotal').innerText = `$${subtotal.toFixed(2)}`;
            document.getElementById('impuesto').innerText = `$${impuesto.toFixed(2)}`;
            document.getElementById('total').innerText = `$${total.toFixed(2)}`;
        }

        function addPlatillo(id, nombre, precio) {
            const container = document.getElementById('platillos-container');
            const html = `
                <div class="flex gap-2 items-center" data-precio="${precio}">
                    <input type="hidden" name="platillos[${count}][id_menu]" value="${id}">
                    <span class="flex-1">${nombre} - $${precio.toFixed(2)}</span>
                    <input type="number" name="platillos[${count}][cantidad]" value="1" min="1" class="w-20 border-gray-300 rounded-md bg-white text-black dark:bg-white dark:text-black" onchange="actualizarResumen()">
                    <button type="button" onclick="this.parentElement.remove(); actualizarResumen();" class="px-2 py-1 text-red-500">X</button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            count++;
            actualizarResumen();
        }

        // Cargar mesas según área seleccionada
        document.getElementById('id_area_mesas').addEventListener('change', function() {
            const areaId = this.value;
            const mesaSelect = document.getElementById('id_mesas');
            mesaSelect.innerHTML = '<option value="">Cargando mesas...</option>';

            if(areaId) {
                fetch(`/areas/${areaId}/mesas-disponibles`)
                    .then(res => res.json())
                    .then(data => {
                        mesaSelect.innerHTML = '<option value="">-- Selecciona una mesa --</option>';
                        data.forEach(mesa => {
                            mesaSelect.innerHTML += `<option value="${mesa.id}">Mesa ${mesa.numero} (${mesa.capacidad} personas)</option>`;
                        });
                    });
            }
        });

        function openTab(index) {
            const tabs = @json($categorias->pluck('id')->toArray());
            @foreach($categorias as $i => $categoria)
                document.getElementById('tab-content-{{ $i }}').classList.add('hidden');
                document.getElementById('tab-btn-{{ $i }}').classList.remove('border-primary', 'font-semibold');
            @endforeach
            document.getElementById('tab-content-' + index).classList.remove('hidden');
            document.getElementById('tab-btn-' + index).classList.add('border-primary', 'font-semibold');
        }
    </script>
</x-app-layout>
