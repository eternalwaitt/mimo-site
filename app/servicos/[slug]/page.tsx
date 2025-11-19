import type { Metadata } from 'next'
import { notFound } from 'next/navigation'
import { Header } from '@/components/layout/header'
import { Footer } from '@/components/layout/footer'
import { SERVICES, getWhatsAppBookingUrl } from '@/lib/constants'
import { ServiceContent } from './service-content'

type Props = {
  params: Promise<{ slug: string }>
}

/**
 * página individual de serviço.
 * 
 * - server component com metadata dinâmica
 * - gera páginas estáticas para todos os serviços
 * - hero visual com overlay
 * - descrição destacada
 * - procedimentos em grid (se houver)
 * - benefícios em cards visuais
 * - portfolio em grid destacado (se houver)
 * - CTA duplo (agendar + conhecer outros serviços)
 * - mensagem WhatsApp personalizada por serviço
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
    title: `${service.title} | Mimo Salão`,
    description: service.description,
    alternates: {
      canonical: `https://mimo-site.vercel.app/servicos/${slug}`,
    },
    openGraph: {
      title: `${service.title} | Mimo Salão`,
      description: service.description,
      url: `https://mimo-site.vercel.app/servicos/${slug}`,
      type: 'website',
    },
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
      <ServiceContent service={service} whatsappUrl={whatsappUrl} />
      <Footer />
    </>
  )
}

export async function generateStaticParams() {
  return SERVICES.map((service) => ({
    slug: service.slug,
  }))
}
