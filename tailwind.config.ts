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
        'fade-in-up': 'fade-in-up 0.6s ease-out',
        'fade-in-scale': 'fade-in-scale 0.5s ease-out',
        'hero-image-scale': 'hero-image-scale 1.2s ease-out',
        'hero-content-fade': 'hero-content-fade 0.8s ease-out 0.2s both',
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
        'fade-in-up': {
          from: {
            opacity: '0',
            transform: 'translateY(30px)',
          },
          to: {
            opacity: '1',
            transform: 'translateY(0)',
          },
        },
        'fade-in-scale': {
          from: {
            opacity: '0',
            transform: 'scale(0.9)',
          },
          to: {
            opacity: '1',
            transform: 'scale(1)',
          },
        },
        'hero-image-scale': {
          from: {
            transform: 'scale(1.1)',
          },
          to: {
            transform: 'scale(1)',
          },
        },
        'hero-content-fade': {
          from: {
            opacity: '0',
            transform: 'translateY(30px)',
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

