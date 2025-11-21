import { ImageWithFallback } from '@/components/ui/image-with-fallback'

/**
 * template de seção otimizada.
 * 
 * performance checklist:
 * ✅ server component quando possível (sem 'use client' desnecessário)
 * ✅ CSS animations em vez de Framer Motion
 * ✅ ImageWithFallback com sizes correto
 * ✅ lazy loading para imagens abaixo do fold
 * ✅ content-visibility: auto para seções grandes
 * 
 * quando usar 'use client':
 * - apenas se precisar de hooks (useState, useEffect)
 * - apenas se precisar de event handlers
 * - apenas se precisar de browser APIs
 * 
 * quando usar Framer Motion:
 * - apenas abaixo do fold
 * - apenas para animações complexas
 * - nunca em componentes acima do fold
 */

type SectionTemplateProps = {
  title: string
  description?: string
}

/**
 * exemplo de seção otimizada.
 * 
 * @param {SectionTemplateProps} props - propriedades da seção
 * @returns {JSX.Element} seção otimizada
 */
export function SectionTemplate({ title, description }: SectionTemplateProps) {
  return (
    <section className="py-20 md:py-32 bg-mimo-neutral-light">
      <div className="container mx-auto px-4 sm:px-6 lg:px-8">
        {/* 
          performance: animações CSS em vez de Framer Motion
          use classes: animate-fade-in-up, animate-fade-in-scale, etc.
        */}
        <div className="text-center mb-12 animate-fade-in-up">
          <h2 className="font-bueno text-4xl md:text-5xl font-bold text-mimo-brown mb-4">
            {title}
          </h2>
          {description && (
            <p className="font-satoshi text-lg text-mimo-blue max-w-2xl mx-auto">
              {description}
            </p>
          )}
        </div>

        {/* 
          performance: imagens devem ter sizes correto
          mobile: use breakpoints apropriados
          desktop: limite tamanho máximo
        */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div className="relative aspect-[4/3] overflow-hidden rounded-xl">
            <ImageWithFallback
              src="/images/example.webp"
              alt="Exemplo"
              width={800}
              height={600}
              sizes="(max-width: 768px) 100vw, 50vw"
              className="object-cover"
            />
          </div>
        </div>
      </div>
    </section>
  )
}

