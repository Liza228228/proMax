import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                peach: {
                    50: '#FFF5F0',
                    100: '#FFE5D9',
                    200: '#FFDAB9',
                    300: '#FFCBA4',
                    400: '#FFB088',
                    500: '#FF9A6C',
                    600: '#FF7F50',
                    700: '#E6693D',
                    800: '#CC532A',
                    900: '#B33D17',
                },
            },
        },
    },

    plugins: [forms],
};
