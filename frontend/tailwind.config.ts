import type { Config } from 'tailwindcss'

export default {
  content: [],
  theme: {
    extend: {
      colors: {
        coffee: {
          50:  '#fdf8f0',
          100: '#f9edda',
          200: '#f2d9b0',
          300: '#e8bf7d',
          400: '#dc9f48',
          500: '#d18528',
          600: '#b86a1e',
          700: '#99511a',
          800: '#7c421a',
          900: '#663818',
          950: '#3a1d0b',
        },
      },
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
      },
    },
  },
  plugins: [],
} satisfies Config
