import type { Metadata } from 'next'
import { Header } from '@/components/layout/header'
import { Footer } from '@/components/layout/footer'
import { ImageWithFallback } from '@/components/ui/image-with-fallback'
import { MIMO_COMPANY } from '@/lib/constants/index'

export const metadata: Metadata = {
  title: 'sobre',
  description: 'Conheça a história da Mimo, nossos valores e nossa missão de simplificar o cuidado e devolver tempo.',
  alternates: {
    canonical: 'https://mimo-site.vercel.app/sobre',
  },
  openGraph: {
    title: 'Sobre | Mimo Salão',
    description: 'Conheça a história da Mimo, nossos valores e nossa missão de simplificar o cuidado e devolver tempo.',
    url: 'https://mimo-site.vercel.app/sobre',
    type: 'website',
  },
}

/**
 * página sobre.
 * 
 * - hero com missão da empresa
 * - história em texto narrativo
 * - valores em cards visuais (diversidade, acolhimento, qualidade)
 * - seção de time (placeholder para futuro)
 */
export default function SobrePage() {
  return (
    <>
      <Header />
      <main id="main-content" className="pt-20">
        {/* Hero */}
        <section className="relative h-[50vh] min-h-[300px] flex items-center justify-center bg-mimo-neutral-light">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 className="font-bueno text-5xl md:text-6xl font-bold text-mimo-brown mb-4">
              Sobre a Mimo
            </h1>
            <p className="font-satoshi text-xl text-mimo-blue max-w-2xl mx-auto">
              {MIMO_COMPANY.mission}
            </p>
          </div>
        </section>

        {/* História */}
        <section className="py-16 md:py-24 bg-white">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">
            <h2 className="font-bueno text-4xl font-bold text-mimo-brown mb-8 text-center">
              Nossa História
            </h2>
            <div className="prose prose-lg max-w-none">
              <p className="font-satoshi text-lg text-mimo-blue leading-relaxed mb-6">
                A Mimo nasceu da vontade de criar um espaço diferente. Um lugar onde beleza não é futilidade, mas ferramenta de autonomia.
              </p>
              <p className="font-satoshi text-lg text-mimo-blue leading-relaxed mb-6">
                Oferecemos cabelo, unhas, cílios, sobrancelha, micropigmentação e estética facial/corporal em um só lugar, com processos ágeis e baixa manutenção.
              </p>
              <p className="font-satoshi text-lg text-mimo-blue leading-relaxed">
                Nosso objetivo é simplificar o cuidado, devolver tempo e transformar a relação com a beleza em um ritual leve, consciente e sustentável.
              </p>
            </div>
          </div>
        </section>

        {/* Valores */}
        <section className="py-16 md:py-24 bg-mimo-neutral-light">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 className="font-bueno text-4xl font-bold text-mimo-brown mb-12 text-center">
              Nossos Valores
            </h2>
            <div className="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
              {MIMO_COMPANY.values.map((value, index) => (
                <div
                  key={index}
                  className="bg-white rounded-xl p-6 shadow-md text-center"
                >
                  <h3 className="font-bueno text-2xl font-bold text-mimo-brown mb-3">
                    {value.title}
                  </h3>
                  <p className="font-satoshi text-mimo-blue leading-relaxed">
                    {value.description}
                  </p>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* Time Placeholder */}
        <section className="py-16 md:py-24 bg-white">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 className="font-bueno text-4xl font-bold text-mimo-brown mb-12 text-center">
              Nosso Time
            </h2>
            <p className="font-satoshi text-lg text-mimo-blue text-center max-w-2xl mx-auto">
              Em breve: conheça os profissionais que fazem a Mimo acontecer.
            </p>
          </div>
        </section>
      </main>
      <Footer />
    </>
  )
}

