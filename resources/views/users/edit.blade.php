<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
            Editar Usuario
        </h2>
    </x-slot>

    <main class="flex-1 p-8 bg-gray-50 dark:bg-background-dark/20 min-h-screen">
        <div class="max-w-4xl mx-auto bg-white dark:bg-background-dark/50 shadow-sm rounded-xl border border-primary/20 dark:border-primary/30 p-6">
            <div x-data="editarUsuario({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')">
                <form method="POST" action="{{ route('users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    {{-- Nombre --}}
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 dark:text-gray-300 font-semibold">Nombre</label>
                        <input id="name" name="name" type="text" x-model="nombre" required autofocus
                            autocomplete="name"
                            @blur="verificarCampo('name', nombre)"
                            class="block mt-2 w-full rounded-xl shadow-sm border-gray-300 dark:border-gray-600 
                            dark:bg-background-dark/70 dark:text-white focus:border-indigo-500 focus:ring-indigo-500" />
                        <template x-if="nombreError">
                            <p class="mt-1 text-red-500 text-sm" x-text="nombreError"></p>
                        </template>
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 dark:text-gray-300 font-semibold">Correo Electrónico</label>
                        <input id="email" name="email" type="email" x-model="correo" required
                            autocomplete="username"
                            @blur="verificarCampo('email', correo)"
                            class="block mt-2 w-full rounded-xl shadow-sm border-gray-300 dark:border-gray-600 
                            dark:bg-background-dark/70 dark:text-white focus:border-indigo-500 focus:ring-indigo-500" />
                        <template x-if="correoError">
                            <p class="mt-1 text-red-500 text-sm" x-text="correoError"></p>
                        </template>
                    </div>

                    {{-- Rol --}}
                    <div class="mb-4">
                        <label for="rol" class="block text-gray-700 dark:text-gray-300 font-semibold">Rol</label>
                        <select id="rol" name="rol" x-model="rol"
                            class="block mt-2 w-full rounded-xl shadow-sm border-gray-300 dark:border-gray-600 
                            dark:bg-background-dark/70 dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="administrador">Administrador</option>
                            <option value="mesero">Mesero</option>
                            <option value="cocinero">Cocinero</option>
                            <option value="cajero">Cajero</option>
                        </select>
                    </div>

                    {{-- Contraseña --}}
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 dark:text-gray-300 font-semibold">Contraseña</label>
                        <input id="password" name="password" type="password"
                            x-model="password"
                            @input="validarPassword"
                            autocomplete="new-password"
                            class="block mt-2 w-full rounded-xl shadow-sm border-gray-300 dark:border-gray-600 
                            dark:bg-background-dark/70 dark:text-white focus:border-indigo-500 focus:ring-indigo-500" />
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Dejar en blanco si no deseas cambiar la contraseña</p>
                    </div>

                    {{-- Confirmar Contraseña --}}
                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-gray-700 dark:text-gray-300 font-semibold">Confirmar Contraseña</label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            x-model="password_confirmation"
                            @input="validarPassword"
                            autocomplete="new-password"
                            class="block mt-2 w-full rounded-xl shadow-sm border-gray-300 dark:border-gray-600 
                            dark:bg-background-dark/70 dark:text-white focus:border-indigo-500 focus:ring-indigo-500" />
                        <template x-if="passwordError">
                            <p class="mt-1 text-red-500 text-sm" x-text="passwordError"></p>
                        </template>
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end gap-4 mt-8">
                        <a href="{{ route('users.index') }}" class="px-6 py-2 rounded-xl text-sm font-semibold bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 
                            text-gray-800 dark:text-gray-200 transition">Cancelar</a>
                        <button type="submit" class="px-6 py-2 rounded-xl text-sm font-semibold bg-indigo-600 hover:bg-indigo-700 text-white transition">
                            Actualizar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('editarUsuario', (userId, nombreInit, correoInit) => ({
            nombre: nombreInit,
            correo: correoInit,
            rol: '{{ old('rol', $user->rol) }}',
            nombreError: '',
            correoError: '',
            password: '',
            password_confirmation: '',
            passwordError: '',

            async verificarCampo(campo, valor) {
                if (!valor.trim()) return;

                try {
                    const response = await fetch("{{ route('users.verificarUnico') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                        },
                        body: JSON.stringify({ campo, valor, userId })
                    });

                    const data = await response.json();

                    if (data.existe) {
                        if (campo === 'name') this.nombreError = 'Este nombre de usuario ya está registrado.';
                        if (campo === 'email') this.correoError = 'Este correo electrónico ya está registrado.';
                    } else {
                        if (campo === 'name') this.nombreError = '';
                        if (campo === 'email') this.correoError = '';
                    }
                } catch (error) {
                    console.error('Error al verificar el campo:', error);
                }
            },

            validarPassword() {
                if (this.password || this.password_confirmation) {
                    if (this.password !== this.password_confirmation) {
                        this.passwordError = 'Las contraseñas no coinciden.';
                    } else {
                        this.passwordError = '';
                    }
                } else {
                    this.passwordError = '';
                }
            },

            enviarFormulario() {
                this.validarPassword();

                if (this.nombreError || this.correoError || this.passwordError) {
                    alert('Corrige los errores antes de actualizar el usuario.');
                    return;
                }

                document.querySelector('form').submit();
            }
        }));
    });
    </script>
</x-app-layout>
