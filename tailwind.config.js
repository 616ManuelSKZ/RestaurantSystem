// tailwind.config.js
import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'

export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class', // ✅ importante

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Colores personalizados (ajusta a tu gusto)
                background: {
                    light: '#f8fafc',
                    dark: '#1e293b',
                },
                text: {
                    light: '#1f2937',
                    dark: '#f8fafc',
                },
                subtle: {
                    light: '#e5e7eb',
                    dark: '#374151',
                },
                placeholder: {
                    light: '#9ca3af',
                    dark: '#6b7280',
                },
                primary: '#3b82f6', // azul
            },
        },
    },

    plugins: [forms],
}
