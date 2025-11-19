import type { Metadata } from 'next'
import localFont from 'next/font/local'
import './globals.css'
import { MIMO_COMPANY, MIMO_CONTACT } from '@/lib/constants'
import { APP_VERSION } from '@/lib/version'

/**
 * configuração de fontes usando next/font/local.
 * 
 * fontes devem estar em public/fonts/
 * fallback para system fonts caso fontes não carreguem.
 */
const bueno = localFont({
  src: [
    {
      path: '../public/fonts/bueno-regular.woff2',
      weight: '400',
      style: 'normal',
    },
  ],
  variable: '--font-bueno',
  display: 'swap',
  fallback: ['-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'sans-serif'],
})

const satoshi = localFont({
  src: [
    {
      path: '../public/fonts/satoshi-regular.woff2',
      weight: '400',
      style: 'normal',
    },
  ],
  variable: '--font-satoshi',
  display: 'swap',
  fallback: ['-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'sans-serif'],
})

export const metadata: Metadata = {
  title: {
    default: 'beleza sem padrão | mimo salão',
    template: '%s | mimo salão',
  },
  description: 'salão de beleza inclusivo e acolhedor. serviços personalizados que cabem na sua vida real. agende seu mimo!',
  keywords: ['salão de beleza', 'beleza sem padrão', 'corte de cabelo', 'coloração', 'alongamento de cílios', 'manicure', 'pedicure', 'São Paulo', 'Vila Madalena'],
  generator: `Mimo Site v${APP_VERSION}`,
  openGraph: {
    type: 'website',
    locale: 'pt_BR',
    url: 'https://minhamimo.com.br',
    siteName: 'Mimo Salão',
    title: 'beleza sem padrão | mimo salão',
    description: 'salão de beleza inclusivo e acolhedor. serviços personalizados que cabem na sua vida real.',
  },
  robots: {
    index: true,
    follow: true,
  },
}

/**
 * dados estruturados (schema.org) para seo local.
 */
const structuredData = {
  '@context': 'https://schema.org',
  '@type': 'BeautySalon',
  name: MIMO_COMPANY.name,
  legalName: MIMO_COMPANY.legalName,
  description: 'Salão de beleza inclusivo e acolhedor. Beleza sem padrão, cuidado com carinho.',
  url: 'https://minhamimo.com.br',
  telephone: `+55-${MIMO_CONTACT.whatsapp.slice(2)}`,
  address: {
    '@type': 'PostalAddress',
    streetAddress: MIMO_CONTACT.address.street,
    addressLocality: MIMO_CONTACT.address.city,
    addressRegion: MIMO_CONTACT.address.state.toUpperCase(),
    postalCode: MIMO_CONTACT.address.zipCode,
    addressCountry: 'BR',
  },
  openingHoursSpecification: [
    {
      '@type': 'OpeningHoursSpecification',
      dayOfWeek: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
      opens: MIMO_CONTACT.hours.weekdays.split(' - ')[0],
      closes: MIMO_CONTACT.hours.weekdays.split(' - ')[1],
    },
    {
      '@type': 'OpeningHoursSpecification',
      dayOfWeek: 'Saturday',
      opens: MIMO_CONTACT.hours.saturday.split(' - ')[0],
      closes: MIMO_CONTACT.hours.saturday.split(' - ')[1],
    },
  ],
  priceRange: '$$',
  image: 'https://minhamimo.com.br/images/logo-full.svg',
}

export default function RootLayout({
  children,
}: {
  children: React.ReactNode
}) {
  return (
    <html lang="pt-BR" className={`${bueno.variable} ${satoshi.variable}`}>
      <head>
        {/* Preload fontes críticas */}
        <link
          rel="preload"
          href="/fonts/bueno-regular.woff2"
          as="font"
          type="font/woff2"
          crossOrigin="anonymous"
        />
        <link
          rel="preload"
          href="/fonts/satoshi-regular.woff2"
          as="font"
          type="font/woff2"
          crossOrigin="anonymous"
        />
        {/* Preload hero image com fetchpriority */}
        <link
          rel="preload"
          href="/images/hero-bg.webp"
          as="image"
          fetchPriority="high"
        />
        <script
          type="application/ld+json"
          dangerouslySetInnerHTML={{ __html: JSON.stringify(structuredData) }}
        />
      </head>
      <body className={`${bueno.variable} ${satoshi.variable}`} suppressHydrationWarning>
        {children}
      </body>
    </html>
  )
}

