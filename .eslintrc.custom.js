/**
 * ESLint rules customizadas para performance.
 * 
 * estas rules ajudam a prevenir problemas comuns de performance:
 * - Framer Motion acima do fold
 * - Imagens sem sizes attribute
 * - Client components desnecessários
 * - Imports de bibliotecas pesadas no layout
 */

module.exports = {
  rules: {
    // Prevenir Framer Motion em componentes acima do fold
    'no-restricted-imports': [
      'error',
      {
        paths: [
          {
            name: 'framer-motion',
            message:
              'Framer Motion deve ser usado apenas abaixo do fold ou para animações complexas. Use CSS animations para acima do fold. Veja: docs/ADDING-NEW-PAGES.md',
          },
        ],
        patterns: [
          {
            group: ['framer-motion/*'],
            message:
              'Framer Motion deve ser usado apenas abaixo do fold. Use CSS animations para acima do fold.',
          },
        ],
      },
    ],

    // Avisar sobre 'use client' desnecessário
    // Nota: Esta rule precisa ser implementada via plugin customizado
    // Por enquanto, documentamos no comentário
  },
}

