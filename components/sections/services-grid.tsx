import { ServiceCard } from '@/components/ui/service-card'
import { SERVICES } from '@/lib/constants/index'
import { ANIMATION_DELAYS } from '@/lib/ui-constants'

/**
 * grid de serviços - cards grandes com foto dominante.
 * 
 * - nome BUENO, preço sutil
 * - link para subpáginas de cada serviço
 * - animações de entrada escalonadas com CSS (não framer-motion)
 * 
 * @returns {JSX.Element} seção com grid de serviços
 * 
 * @example
 * ```tsx
 * // Usado na home page e página de serviços
 * <ServicesGrid />
 * ```
 */
export function ServicesGrid() {
  return (
    <section className="py-20 md:py-32 bg-white" style={{ contentVisibility: 'auto' }}>
      <div className="container mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-12 animate-fade-in-up">
          <h2 className="font-bueno text-4xl md:text-5xl font-bold text-mimo-brown mb-4">
            Nossos Serviços
          </h2>
          <p className="font-satoshi text-lg text-mimo-blue max-w-2xl mx-auto">
            Tudo que você precisa em um só lugar, com processos ágeis e baixa manutenção
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
          {SERVICES.map((service, index) => (
            <div
              key={service.id}
              className="animate-fade-in-up"
              style={{ 
                animationDelay: `${index * ANIMATION_DELAYS.stagger}s`,
                animationFillMode: 'both'
              }}
            >
              <ServiceCard service={service} />
            </div>
          ))}
        </div>
      </div>
    </section>
  )
}

