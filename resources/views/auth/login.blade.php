<x-guest-layout>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Iniciar Sesión - Restaurante</title>

        {{-- Tailwind y Fuentes --}}
        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>

        <script>
            tailwind.config = {
                darkMode: "class",
                theme: {
                    extend: {
                        colors: {
                            "primary": "#17cf17",
                            "background-light": "#f6f8f6",
                            "background-dark": "#112111",
                            "text-light": "#112111",
                            "text-dark": "#f6f8f6",
                            "subtle-light": "#e3e8e3",
                            "subtle-dark": "#223122",
                            "placeholder-light": "#6b7a6b",
                            "placeholder-dark": "#9ca89c"
                        },
                        fontFamily: {
                            "display": ["Work Sans", "sans-serif"]
                        },
                        borderRadius: {
                            "DEFAULT": "0.5rem",
                            "lg": "0.75rem",
                            "xl": "1rem",
                            "full": "9999px"
                        },
                    },
                },
            }
        </script>
    </head>

    <body class="bg-background-light dark:bg-background-dark font-display text-text-light dark:text-text-dark">
        <div class="flex items-center justify-center min-h-screen">
            <div class="w-full max-w-md p-8 space-y-8 bg-background-light dark:bg-background-dark shadow-xl rounded-xl">
                
                {{-- Encabezado --}}
                <div class="text-center">
                    <h1 class="text-3xl font-bold tracking-tight">Inicio de Sesión</h1>
                    <p class="mt-2 text-sm text-placeholder-light dark:text-placeholder-dark">
                        Bienvenido de nuevo, por favor ingresa tus credenciales
                    </p>
                </div>

                {{-- Estado de Sesión --}}
                <x-auth-session-status class="mb-4" :status="session('status')" />

                {{-- Formulario --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    {{-- Correo electrónico --}}
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="material-symbols-outlined text-placeholder-light dark:text-placeholder-dark">
                                mail
                            </span>
                        </div>
                        <x-text-input id="email" type="email" name="email" 
                            class="block w-full rounded-lg border-subtle-light dark:border-subtle-dark bg-transparent py-3 pl-10 pr-3 
                            text-text-light dark:text-text-dark placeholder:text-placeholder-light dark:placeholder:text-placeholder-dark 
                            focus:border-primary focus:ring-primary focus:outline-none"
                            placeholder="Correo electrónico"
                            :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-sm" />
                    </div>

                    {{-- Contraseña --}}
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="material-symbols-outlined text-placeholder-light dark:text-placeholder-dark">
                                lock
                            </span>
                        </div>
                        <x-text-input id="password" type="password" name="password"
                            class="block w-full rounded-lg border-subtle-light dark:border-subtle-dark bg-transparent py-3 pl-10 pr-3 
                            text-text-light dark:text-text-dark placeholder:text-placeholder-light dark:placeholder:text-placeholder-dark 
                            focus:border-primary focus:ring-primary focus:outline-none"
                            placeholder="Contraseña"
                            required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-sm" />
                    </div>

                    {{-- Recordarme y enlace --}}
                    <div class="flex items-center justify-between text-sm">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" 
                                class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary"
                                name="remember">
                            <span class="ml-2 text-placeholder-light dark:text-placeholder-dark">Recordarme</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="font-medium text-primary hover:text-primary/80">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>

                    {{-- Botón de inicio --}}
                    <div>
                        <button type="submit"
                            class="flex w-full justify-center rounded-lg bg-primary px-3 py-3 text-base font-semibold text-white 
                            shadow-sm hover:bg-primary/90 focus-visible:outline focus-visible:outline-2 
                            focus-visible:outline-offset-2 focus-visible:outline-primary transition-colors duration-200">
                            Iniciar Sesión
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</x-guest-layout>
