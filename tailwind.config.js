import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'primary': {
                    '50': '#f1f4ff',
                    '100': '#e6ecff',
                    '200': '#d0dcff',
                    '300': '#aabcff',
                    '400': '#7a90ff',
                    '500': '#455bff',
                    '600': '#1f2dff',
                    '700': '#0d1af4',
                    '800': '#0a15cd',
                    '900': '#09108c',
                    '950': '#030d72',
                },
            }
        },
    },

    plugins: [forms, typography],
};
