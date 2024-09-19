/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'black-3': '#393939', 
        'black-2': '#21272A', 
        'black-5': '#555555', 
        'black-d': '#DDDDDD', 
        'light-pink': '#F38181', 
        'btn-pink': '#BA5053',
        'btn-h-pink': '#832f33',
        'card-bg': 'rgba(255, 255, 255, 0.40)',
        'site-bg': '#FFF0EF'
      },
      fontFamily: {
        roboto: ['Roboto', 'sans-serif'],
      },
      backgroundImage: {
        'gradient-custom': 'linear-gradient(90deg, #FBEDBC 0%, rgba(251, 237, 188, 0.50) 100%)',
      },
    },
  },
  variants: {
    extend: {
      backgroundColor: ['peer-checked'],
      borderColor: ['peer-checked'],
    },
  },
  plugins: [],
}
