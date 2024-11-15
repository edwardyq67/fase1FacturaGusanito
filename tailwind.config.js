/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './src/**/*.{html,js,php}', // Ajusta las rutas según tu estructura de archivos
    './public/index.html', // Agrega rutas adicionales si es necesario
  ],
  theme: {
    extend: {
      colors: {
        verde: {
          '50': '#f0fdf0',
          '100': '#ddfbde',
          '200': '#bdf5bf',
          '300': '#89ec8f',
          '400': '#4eda57',
          '500': '#26c130',
          '600': '#1aa223',
          '700': '#187d1f',
          '800': '#18631d',
          '900': '#16511b',
          '950': '#062d0a',
        },
        gris: {
          '50': '#f7f7f7',
          '100': '#ededed',
          '200': '#d9d9d9',
          '300': '#c8c8c8',
          '400': '#adadad',
          '500': '#999999',
          '600': '#888888',
          '700': '#7b7b7b',
          '800': '#676767',
          '900': '#545454',
          '950': '#363636',
        },
      },
    },
  },
  plugins: [],
}
