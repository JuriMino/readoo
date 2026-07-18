import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

const brand = {
    green:'#16a34a',
    blue: '#2D5BE3',
    orange:'#E8510A',
};

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            maxWidth: {
                '8xl' : '88rem', // 1408px（7xl=1280pxと全幅の中間)
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: brand.orange,
                secondary: brand.blue,
                book: brand.green,
                knowledge: brand.blue,
                action: brand.orange,
                base: '#FAF6EC', // 背景クリーム色
            },
        },
    },

    plugins: [forms],
};
