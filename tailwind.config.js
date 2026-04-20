/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                gray: {
                    950: '#030712',
                },
            },
        },
    },
    plugins: [],
}
