import type { Metadata } from 'next'
import { notFound } from 'next/navigation'
import { Header } from '@/components/layout/header'
import { Footer } from '@/components/layout/footer'
import { Button } from '@/components/ui/button'
import { ImageWithFallback } from '@/components/ui/image-with-fallback'
import { SERVICES, getWhatsAppBookingUrl } from '@/lib/constants'

type Props = {
  params: Promise<{ slug: string }>
}

/**
 * página individual de serviço.
 * 
 * hero com foto do serviço.
 * "A partir de R$X" visível.
 * 3-5 bullets benefícios.
 * portfolio integrado (antes/depois com contexto).
 * CTA: Agendar esse serviço específico.
 * SEO: metadata por serviço, schema Service.
 */
export async function generateMetadata({ params }: Props): Promise<Metadata> {
  const { slug } = await params
  const service = SERVICES.find((s) => s.slug === slug)

  if (!service) {
    return {
      title: 'Serviço não encontrado',
    }
  }

  return {
    title: service.title,
    description: service.description,
  }
}

export default async function ServicoPage({ params }: Props) {
  const { slug } = await params
  const service = SERVICES.find((s) => s.slug === slug)

  if (!service) {
    notFound()
  }

  const whatsappMessage = `Olá, vim pelo site e tenho interesse no serviço de ${service.title}. Gostaria de agendar.`
  const whatsappUrl = getWhatsAppBookingUrl().replace(
    encodeURIComponent('Olá, vim pelo site e queria agendar um horário'),
    encodeURIComponent(whatsappMessage)
  )

  return (
    <>
      <Header />
      <main className="pt-20">
        {/* Hero */}
        <section className="relative h-[60vh] min-h-[400px] flex items-center justify-center overflow-hidden">
          <div className="absolute inset-0 bg-mimo-brown/30 z-10" />
          <ImageWithFallback
            src={service.image}
            alt={service.imageAlt}
            fill
            sizes="100vw"
            className="object-cover"
            priority
          />
          <div className="relative z-20 text-center text-white container mx-auto px-4 sm:px-6 lg:px-8">
            <h1 className="font-bueno text-5xl md:text-6xl font-bold mb-4">
              {service.title}
            </h1>
            <p className="font-satoshi text-xl md:text-2xl">
              {service.price}
            </p>
          </div>
        </section>

        {/* Content */}
        <section className="py-16 md:py-24 bg-white">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">
            <div className="prose prose-lg max-w-none">
              <p className="font-satoshi text-lg text-mimo-blue leading-relaxed mb-8">
                {service.description}
              </p>

              <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-6">
                Benefícios
              </h2>
              <ul className="space-y-3 mb-12">
                {service.benefits.map((benefit, index) => (
                  <li key={index} className="font-satoshi text-mimo-blue flex items-start">
                    <span className="text-mimo-gold mr-3 text-xl">•</span>
                    <span>{benefit}</span>
                  </li>
                ))}
              </ul>

              {/* Portfolio Placeholder */}
              {service.portfolio && service.portfolio.length > 0 && (
                <div className="mb-12">
                  <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-6">
                    Portfolio
                  </h2>
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {service.portfolio.map((item, index) => (
                      <div key={index} className="relative aspect-square rounded-lg overflow-hidden">
                        <ImageWithFallback
                          src={item.image}
                          alt={item.imageAlt}
                          fill
                          sizes="(max-width: 768px) 100vw, 50vw"
                          className="object-cover"
                        />
                        {item.context && (
                          <div className="absolute bottom-0 left-0 right-0 bg-black/60 text-white p-4">
                            <p className="font-satoshi text-sm">{item.context}</p>
                          </div>
                        )}
                      </div>
                    ))}
                  </div>
                </div>
              )}

              {/* CTA */}
              <div className="text-center">
                <Button
                  variant="primary"
                  href={whatsappUrl}
                  external
                  className="text-xl px-10 py-5"
                >
                  Agendar {service.title}
                </Button>
              </div>
            </div>
          </div>
        </section>
      </main>
      <Footer />
    </>
  )
}

export async function generateStaticParams() {
  return SERVICES.map((service) => ({
    slug: service.slug,
  }))
}

