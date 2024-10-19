/** @type {import('tailwindcss').Config} */
export default {
  content: ['./index.html', './src/**/*.{js,ts,jsx,tsx}'],
  theme: {
    common:{
      gutter: '60px',
    },
    container: {
      center: true,
      padding: {
        DEFAULT: '1rem',
      }
    },
    extend: {
      colors: {
        primary: '#3eaf7c',
        dark: '#333333'
      }
    },
    fontFamily: {
      display: ['Roboto']
    }
  },
  plugins: []
}
