import { CelebrityCard } from '@/components/ui/celebrity-card'
import { CELEBRITIES } from '@/lib/constants'

/**
 * seção #MomentoMIMO - grid de celebridades e influencers.
 * 
 * - grid responsivo de cards de celebridades
 * - estilo editorial (não testemunhal genérico)
 * - thumbnails com links diretos para Instagram (sem iframes)
 * - animações de entrada escalonadas com CSS
 * - section is below fold to prevent LCP interference
 * - server component para melhor performance
 * 
 * @returns {JSX.Element} seção com grid de celebridades
 */
export function MomentoMimo() {
  return (
    <section className="py-20 md:py-32 bg-mimo-neutral-light" style={{ contentVisibility: 'auto' }}>
      <div className="container mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-12 animate-fade-in-up">
          <h2 className="font-bueno text-4xl md:text-5xl font-bold text-mimo-brown mb-4">
            #MomentoMIMO
          </h2>
          <p className="font-satoshi text-lg text-mimo-blue max-w-2xl mx-auto">
            Influencers e pessoas incríveis que confiam na Mimo para seus momentos especiais
          </p>
        </div>

        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
          {CELEBRITIES.map((celebrity, index) => (
            <div
              key={celebrity.id}
              className="animate-fade-in-scale"
              style={{ 
                animationDelay: `${index * 0.1}s`,
                animationFillMode: 'both'
              }}
            >
              <CelebrityCard celebrity={celebrity} />
            </div>
          ))}
        </div>
      </div>
    </section>
  )
}

