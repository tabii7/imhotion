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
                sidebar: {
                    bg: '#0a1428',
                    text: '#8fa8cc',
                    active: '#001f4c',
                    hover: 'rgba(127, 167, 225, 0.1)',
                },
                dashboard: {
                    bg: '#f8fafc',
                    panel: '#ffffff',
                    muted: '#64748b',
                    text: '#0f172a',
                    line: '#e2e8f0',
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
            width: {
                '70': '17.5rem', // 280px
            },
            margin: {
                '70': '17.5rem', // 280px
            },
        },
    },

    plugins: [forms],
};
