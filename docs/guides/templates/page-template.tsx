import type { Metadata } from 'next'
import { Header } from '@/components/layout/header'
import { Footer } from '@/components/layout/footer'

/**
 * template de página otimizada.
 * 
 * performance checklist:
 * ✅ server component (sem 'use client' desnecessário)
 * ✅ metadata otimizada para SEO
 * ✅ estrutura Header + Main + Footer
 * ✅ imagens usam next/image com sizes correto
 * ✅ animações usam CSS, não Framer Motion
 * ✅ abaixo do fold: lazy loading
 * 
 * como usar:
 * 1. copie este arquivo para app/[nome-da-pagina]/page.tsx
 * 2. preencha a metadata
 * 3. adicione seu conteúdo no <main>
 * 4. verifique checklist de performance
 * 5. teste: npm run pre-deploy
 */

export const metadata: Metadata = {
  title: 'Título da Página',
  description: 'Descrição otimizada para SEO (150-160 caracteres)',
  // Adicione mais metadata conforme necessário
}

export default function PageTemplate() {
  return (
    <>
      <Header />
      <main id="main-content" className="pt-20">
        {/* 
          performance: seções acima do fold devem ser server components
          use CSS animations, não Framer Motion
        */}
        <section className="relative py-20 md:py-32 bg-mimo-neutral-light">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8">
            <h1 className="font-bueno text-4xl md:text-5xl font-bold text-mimo-brown mb-4">
              Título Principal
            </h1>
            <p className="font-satoshi text-lg text-mimo-blue">
              Conteúdo da página...
            </p>
          </div>
        </section>

        {/* 
          performance: seções abaixo do fold podem usar:
          - content-visibility: auto
          - lazy loading de imagens
          - dynamic imports se necessário
        */}
        <section 
          className="py-20 md:py-32 bg-white"
          style={{ contentVisibility: 'auto' }}
        >
          <div className="container mx-auto px-4 sm:px-6 lg:px-8">
            {/* Conteúdo abaixo do fold */}
          </div>
        </section>
      </main>
      <Footer />
    </>
  )
}

