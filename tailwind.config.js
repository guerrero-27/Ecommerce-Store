import defaultTheme from 'tailwindcss/defaultTheme';

export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
  ],
  theme: {
    extend: {
      colors: {
        paper: '#EFECE3',
        ink: '#23262B',
        accent: '#2F6F5E',
        mustard: '#D4A02A',
        hairline: '#C9C2B2',
      },
      fontFamily: {
        display: ['Fraunces', ...defaultTheme.fontFamily.serif],
        body: ['Inter', ...defaultTheme.fontFamily.sans],
        mono: ['"IBM Plex Mono"', ...defaultTheme.fontFamily.mono],
      },
    },
  },
  plugins: [require('@tailwindcss/forms')],
};