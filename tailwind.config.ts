import type { Config } from 'tailwindcss'

const config: Config = {
  content: [
    './pages/**/*.{js,ts,jsx,tsx,mdx}',
    './components/**/*.{js,ts,jsx,tsx,mdx}',
    './app/**/*.{js,ts,jsx,tsx,mdx}',
  ],
  // Otimizações de performance
  corePlugins: {
    // Desabilitar plugins não utilizados se necessário
  },
  theme: {
    extend: {
      colors: {
        'mimo-brown': '#493125',
        'mimo-blue': '#1F2A3E',
        'mimo-blue-hover': '#2A3A52',
        'mimo-neutral-light': '#F4EFEB',
        'mimo-neutral-medium': '#E5DCD3',
        'mimo-gold': '#EFDFAC',
      },
      fontFamily: {
        'bueno': ['var(--font-bueno)', 'sans-serif'],
        'satoshi': ['var(--font-satoshi)', 'sans-serif'],
      },
      spacing: {
        '18': '4.5rem',
        '88': '22rem',
      },
      screens: {
        'xs': '475px',
      },
      transitionDuration: {
        '400': '400ms',
      },
      animation: {
        'fade-in': 'fade-in 0.5s ease-out',
      },
      keyframes: {
        'fade-in': {
          from: {
            opacity: '0',
            transform: 'translateY(10px)',
          },
          to: {
            opacity: '1',
            transform: 'translateY(0)',
          },
        },
      },
    },
  },
  plugins: [],
}

export default config

