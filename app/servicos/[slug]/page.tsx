import type { Metadata } from 'next'
import { notFound } from 'next/navigation'
import { Header } from '@/components/layout/header'
import { Footer } from '@/components/layout/footer'
import { SERVICES, getWhatsAppBookingUrl, MIMO_COMPANY, MIMO_CONTACT } from '@/lib/constants/index'
import { ServiceContent } from './service-content'
import type { Service } from '@/lib/types'

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

  const ogImage = service.image.startsWith('/')
    ? `https://mimo-site.vercel.app${service.image}`
    : service.image

  return {
    title: `${service.title} | Mimo Salão`,
    description: service.shortDescription || service.description,
    keywords: [
      service.title.toLowerCase(),
      'mimo salão',
      'beleza sem padrão',
      'são paulo',
      'vila madalena',
      ...(service.benefits || []).map((b) => b.toLowerCase()),
    ],
    alternates: {
      canonical: `https://mimo-site.vercel.app/servicos/${slug}`,
    },
    openGraph: {
      title: `${service.title} | Mimo Salão`,
      description: service.shortDescription || service.description,
      url: `https://mimo-site.vercel.app/servicos/${slug}`,
      type: 'website',
      locale: 'pt_BR',
      siteName: 'Mimo Salão',
      images: [
        {
          url: ogImage,
          width: 1200,
          height: 630,
          alt: service.imageAlt,
        },
      ],
    },
    twitter: {
      card: 'summary_large_image',
      title: `${service.title} | Mimo Salão`,
      description: service.shortDescription || service.description,
      images: [ogImage],
    },
  }
}

function generateServiceStructuredData(service: Service) {
  const serviceImage = service.image.startsWith('/')
    ? `https://mimo-site.vercel.app${service.image}`
    : service.image

  return {
    '@context': 'https://schema.org',
    '@type': 'Service',
    name: service.title,
    description: service.description,
    provider: {
      '@type': 'BeautySalon',
      name: MIMO_COMPANY.name,
      address: {
        '@type': 'PostalAddress',
        streetAddress: MIMO_CONTACT.address.street,
        addressLocality: MIMO_CONTACT.address.city,
        addressRegion: MIMO_CONTACT.address.state,
        postalCode: MIMO_CONTACT.address.zipCode,
        addressCountry: 'BR',
      },
      telephone: `+55-${MIMO_CONTACT.whatsapp.slice(2)}`,
      url: 'https://mimo-site.vercel.app',
    },
    image: serviceImage,
    offers: {
      '@type': 'Offer',
      price: service.price,
      priceCurrency: 'BRL',
    },
    areaServed: {
      '@type': 'City',
      name: MIMO_CONTACT.address.city,
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

  const structuredData = generateServiceStructuredData(service)

  return (
    <>
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: JSON.stringify(structuredData) }}
      />
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
