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
                sans: ['BrittiSans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    primary: '#0066ff',
                    'primary-200': '#99c2ff',
                    dark: '#001f4c',
                },
                pricing: {
                    bg: 'linear-gradient(135deg, #001f4c 0%, #0d1b3e 100%)',
                    text: 'white',
                    card: 'rgba(255, 255, 255, 0.03)',
                    border: 'rgba(153, 194, 255, 0.2)',
                    'hover-border': 'rgba(153, 194, 255, 0.4)',
                    shadow: 'rgba(0, 102, 255, 0.1)',
                    subtitle: '#94a3b8',
                }
            },
            backgroundImage: {
                'tile-pattern': "url('/images/background.png')",
            },
        },
    },

    plugins: [forms],
};
