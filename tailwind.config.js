import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.jsx',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
                display: ['"Chakra Petch"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                dex: {
                    50: '#e6f9ff',
                    100: '#c4f1fd',
                    200: '#7fe3fb',
                    300: '#38cff2',
                    400: '#0eb6e2',
                    500: '#0099c9',
                    600: '#017aa6',
                    700: '#066187',
                    800: '#0b4e6e',
                    900: '#0e415c',
                },
            },
        },
    },

    plugins: [],
};
