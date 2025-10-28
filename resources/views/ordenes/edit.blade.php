<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg md:text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Orden') }}
        </h2>
    </x-slot>

    <div class="min-h-screen flex" x-data="{ sidebarOpen: false }">
        {{-- Aside: Menú de platillos --}}
        <aside
            :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
            class="fixed md:relative inset-y-0 left-0 z-40 w-80 md:w-80 bg-background-light dark:bg-background-dark border-r border-primary/20 dark:border-primary/30 p-4 flex flex-col transform transition-transform duration-300 ease-in-out md:translate-x-0">

            {{-- Buscador --}}
            <div class="mb-4 relative flex-shrink-0">
                <input type="text" id="buscar-platillo" placeholder="Buscar platillo..."
                    class="w-full pl-10 pr-4 py-2 rounded-lg bg-primary/10 dark:bg-primary/20 text-gray-800 dark:text-gray-200 placeholder-primary/70 dark:placeholder-primary/60 border-none focus:ring-2 focus:ring-primary">
                <span class="absolute left-3 top-2.5 text-primary">
                    🔍
                </span>
            </div>
            <div id="resultados-busqueda" class="mb-4 hidden">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Resultados de búsqueda</h3>
                <div id="lista-resultados" class="grid grid-cols-1 sm:grid-cols-2 gap-4"></div>
            </div>

            {{-- Pestañas de categorías --}}
            <div class="mb-4 flex-shrink-0 overflow-x-auto scrollbar-thin scrollbar-thumb-primary/50 scrollbar-track-transparent">
                <ul class="flex space-x-2 text-sm font-medium min-w-max">
                    @foreach($categorias as $index => $categoria)
                        <li>
                            <button type="button" 
                                onclick="openTab({{ $index }})"
                                id="tab-btn-{{ $index }}"
                                class="px-3 py-1 rounded-lg border-b-2 border-transparent hover:border-primary focus:outline-none whitespace-nowrap">
                                {{ $categoria->nombre }}
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Contenedor con scroll para los grids --}}
            <div class="flex-1 overflow-y-auto">
                {{-- Contenedores de contenido por categoría --}}
                @foreach($categorias as $index => $categoria)
                    <div id="tab-content-{{ $index }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4 {{ $index === 0 ? '' : 'hidden' }}">
                        @foreach($categoria->menus as $menu)
                            <div 
                                class="group cursor-pointer bg-white dark:bg-gray-800 rounded-lg shadow p-2 hover:ring-2 hover:ring-primary transition"
                                style="display: flex; flex-direction: column; align-items: center;"
                                onclick="addPlatillo({{ $menu->id }}, '{{ $menu->nombre }}', {{ $menu->precio }})"
                            >
                                <div 
                                    class="aspect-square w-full rounded-lg bg-cover bg-center"
                                    style="background-image: url('{{ $menu->imagen ? asset('storage/'.$menu->imagen) : '/images/default-platillo.jpg' }}');"
                                ></div>
                                
                                <p class="mt-2 text-sm font-medium text-center text-gray-800 dark:text-gray-200 group-hover:text-primary">
                                    {{ $menu->nombre }}<br>
                                    ${{ number_format($menu->precio, 2) }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </aside>

        {{-- Overlay para móviles --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            class="fixed inset-0 z-30 bg-black bg-opacity-50 md:hidden"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
        </div>

        {{-- Main: Formulario de orden --}}
        <main class="flex-1 flex flex-col p-4 md:p-6 overflow-y-auto md:ml-0">
            {{-- Botón hamburguesa para móviles --}}
            <button @click="sidebarOpen = !sidebarOpen" class="md:hidden mb-4 p-2 rounded-lg hover:bg-subtle-light dark:hover:bg-subtle-dark self-start">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-4">Editar Orden</h2>

            <form action="{{ route('ordenes.update', $orden->id) }}" method="POST"
                  class="bg-background-light dark:bg-background-dark rounded-xl border border-primary/20 dark:border-primary/30 shadow-sm flex-1 flex flex-col p-4 md:p-6">
                @csrf
                @method('PUT')

                {{-- Área y Mesa --}}
                <div class="flex flex-col md:flex-row gap-4 mb-4">
                    <div class="flex-1">
                        <label for="id_area_mesas" class="block mb-1 text-gray-700 dark:text-gray-200">Área de mesas</label>
                        <select name="id_area_mesas" id="id_area_mesas"
                            class="w-full border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm bg-white dark:bg-background-dark/50 text-gray-900 dark:text-white" required>
                            <option value="">-- Selecciona un área --</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}"
                                    {{ $orden->mesa->id_area_mesas == $area->id ? 'selected' : '' }}>
                                    {{ $area->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-1">
                        <label for="id_mesas" class="block mb-1 text-gray-700 dark:text-gray-200">Mesa disponible</label>
                        <select name="id_mesas" id="id_mesas"
                            class="w-full border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm bg-white dark:bg-background-dark/50 text-gray-900 dark:text-white" required>
                            <option value="{{ $orden->mesa->id }}">Mesa {{ $orden->mesa->numero }}</option>
                        </select>
                    </div>
                </div>

                {{-- Platillos seleccionados --}}
                <div class="mb-4 flex-1">
                    <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">Platillos seleccionados</h3>
                    <div id="platillos-container" class="space-y-2 mb-4">
                        @foreach($orden->detalles_orden as $index => $detalle)
                            <div class="flex gap-2 items-center" data-precio="{{ $detalle->menu->precio }}">
                                <input type="hidden" name="platillos[{{ $index }}][id_menu]" value="{{ $detalle->menu->id }}">
                                <span class="flex-1">{{ $detalle->menu->nombre }} - ${{ number_format($detalle->menu->precio, 2) }}</span>
                                <input type="number" name="platillos[{{ $index }}][cantidad]" 
                                       value="{{ $detalle->cantidad }}" min="1"
                                       class="w-20 border-gray-300 rounded-md bg-white text-black dark:bg-white dark:text-black"
                                       onchange="actualizarResumen()">
                                <button type="button" onclick="this.parentElement.remove(); actualizarResumen();" class="px-2 py-1 text-red-500">X</button>
                            </div>
                        @endforeach
                    </div>

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
                <div class="mt-auto flex flex-col sm:flex-row justify-end gap-4">
                    <a href="{{ route('ordenes.index') }}" 
                       class="px-6 py-2 rounded-lg text-sm font-semibold bg-primary/20 hover:bg-primary/30 text-gray-800 dark:text-gray-200 text-center">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2 rounded-lg text-sm font-semibold bg-primary text-white hover:opacity-90">
                        Actualizar Orden
                    </button>
                </div>
            </form>
        </main>
    </div>

    {{-- Scripts --}}
    <script>
        let count = {{ count($orden->detalles_orden) }};
        const platillosSeleccionados = {}; // Guarda los platillos ya agregados

        function actualizarResumen() {
            let subtotal = 0;
            document.querySelectorAll('#platillos-container > div').forEach(div => {
                const precio = parseFloat(div.dataset.precio);
                const cantidad = parseInt(div.querySelector('.cantidad').value);
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

            // Si el platillo ya está en la lista, solo aumenta la cantidad
            if (platillosSeleccionados[id]) {
                const inputCantidad = document.querySelector(`#platillo-${id} .cantidad`);
                inputCantidad.value = parseInt(inputCantidad.value) + 1;
                actualizarResumen();
                return;
            }

            // Marcar como seleccionado
            platillosSeleccionados[id] = true;

            const html = `
                <div id="platillo-${id}" class="flex gap-2 items-center" data-precio="${precio}">
                    <input type="hidden" name="platillos[${count}][id_menu]" value="${id}">
                    <span class="flex-1">${nombre} - $${precio.toFixed(2)}</span>

                    <div class="flex items-center border rounded-md overflow-hidden">
                        <button type="button" onclick="cambiarCantidad(${id}, -1)" class="px-2 py-1 bg-gray-200 dark:bg-gray-600">−</button>
                        <input type="text" readonly name="platillos[${count}][cantidad]" 
                            value="1" class="cantidad w-12 text-center bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        <button type="button" onclick="cambiarCantidad(${id}, 1)" class="px-2 py-1 bg-gray-200 dark:bg-gray-600">+</button>
                    </div>

                    <button type="button" onclick="eliminarPlatillo(${id})" class="px-2 py-1 text-red-500">✕</button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            count++;
            actualizarResumen();
        }

        function cambiarCantidad(id, cambio) {
            const input = document.querySelector(`#platillo-${id} .cantidad`);
            let nuevaCantidad = parseInt(input.value) + cambio;
            if (nuevaCantidad < 1) return; // No permitir cantidades menores a 1
            input.value = nuevaCantidad;
            actualizarResumen();
        }

        function eliminarPlatillo(id) {
            document.getElementById(`platillo-${id}`).remove();
            delete platillosSeleccionados[id]; // Permite volver a agregarlo
            actualizarResumen();
        }

        // Cargar mesas según el área seleccionada (manteniendo funcionalidad anterior)
        document.getElementById('id_area_mesas').addEventListener('change', function() {
            const areaId = this.value;
            const mesaSelect = document.getElementById('id_mesas');
            mesaSelect.innerHTML = '<option value="">Cargando mesas...</option>';

            if (areaId) {
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

        // Marcar los platillos que ya existen en la orden (del backend)
        @foreach($orden->detalles_orden as $detalle)
            platillosSeleccionados[{{ $detalle->menu->id }}] = true;
        @endforeach

        // Convertir los inputs actuales en controles con +/-
        document.querySelectorAll('#platillos-container > div').forEach(div => {
            const id = div.querySelector('input[type=hidden]').value;
            const cantidadInput = div.querySelector('input[type=number]');
            const cantidad = cantidadInput.value;
            const precio = div.dataset.precio;
            const nombre = div.querySelector('span').innerText.split(' - ')[0];

            // Reemplazar contenido con formato actualizado
            div.outerHTML = `
                <div id="platillo-${id}" class="flex gap-2 items-center" data-precio="${precio}">
                    <input type="hidden" name="platillos[${id}][id_menu]" value="${id}">
                    <span class="flex-1">${nombre} - $${parseFloat(precio).toFixed(2)}</span>

                    <div class="flex items-center border rounded-md overflow-hidden">
                        <button type="button" onclick="cambiarCantidad(${id}, -1)" class="px-2 py-1 bg-gray-200 dark:bg-gray-600">−</button>
                        <input type="text" readonly name="platillos[${id}][cantidad]" 
                            value="${cantidad}" class="cantidad w-12 text-center bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        <button type="button" onclick="cambiarCantidad(${id}, 1)" class="px-2 py-1 bg-gray-200 dark:bg-gray-600">+</button>
                    </div>

                    <button type="button" onclick="eliminarPlatillo(${id})" class="px-2 py-1 text-red-500">✕</button>
                </div>
            `;
        });

        actualizarResumen();

        function openTab(index) {
            @foreach($categorias as $i => $categoria)
                document.getElementById('tab-content-{{ $i }}').classList.add('hidden');
                document.getElementById('tab-btn-{{ $i }}').classList.remove('border-primary', 'font-semibold');
            @endforeach
            document.getElementById('tab-content-' + index).classList.remove('hidden');
            document.getElementById('tab-btn-' + index).classList.add('border-primary', 'font-semibold');
        }

        // 🔎 Búsqueda de platillos con AJAX
        const inputBusqueda = document.getElementById('buscar-platillo');
        const contenedorResultados = document.getElementById('resultados-busqueda');
        const listaResultados = document.getElementById('lista-resultados');
        let timeoutBusqueda = null;

        inputBusqueda.addEventListener('input', function() {
            const query = this.value.trim();

            // Siempre ocultar el contenedor y limpiar la lista al inicio de cada input
            contenedorResultados.classList.add('hidden');
            listaResultados.innerHTML = '';

            if (query.length === 0) {
                clearTimeout(timeoutBusqueda); // Limpiar cualquier timeout pendiente
                return;
            }

            clearTimeout(timeoutBusqueda);
            timeoutBusqueda = setTimeout(() => {
                fetch(`/menus/buscar?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        listaResultados.innerHTML = '';

                        if (data.length === 0) {
                            listaResultados.innerHTML = `<p class="text-gray-500 dark:text-gray-400 col-span-2">Sin resultados</p>`;
                            contenedorResultados.classList.remove('hidden');
                            return;
                        }

                        contenedorResultados.classList.remove('hidden');

                        data.forEach(menu => {
                            const imagen = menu.imagen 
                                ? `/storage/${menu.imagen}` 
                                : '/images/default-platillo.jpg';

                            const div = document.createElement('div');
                            div.className = 'group cursor-pointer bg-white dark:bg-gray-800 rounded-lg shadow p-2 hover:ring-2 hover:ring-primary transition';
                            div.style.display = 'flex';
                            div.style.flexDirection = 'column';
                            div.style.alignItems = 'center';
                            div.innerHTML = `
                                <div class="aspect-square w-full rounded-lg bg-cover bg-center"
                                    style="background-image: url('${imagen}')"></div>
                                <p class="mt-2 text-sm font-medium text-center text-gray-800 dark:text-gray-200 group-hover:text-primary">
                                    ${menu.nombre}<br>$${parseFloat(menu.precio).toFixed(2)}
                                </p>
                            `;
                            div.addEventListener('click', () => {
                                addPlatillo(menu.id, menu.nombre, parseFloat(menu.precio)); // ✅ se pasa precio numérico
                            });

                            listaResultados.appendChild(div);
                        });
                    })
                    .catch(err => {
                        console.error('Error al buscar platillos:', err);
                    });
            }, 400);
        });
    </script>
</x-app-layout>