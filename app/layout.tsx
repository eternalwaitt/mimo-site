import type { Metadata } from 'next'
import localFont from 'next/font/local'
import Script from 'next/script'
import './globals.css'
import { MIMO_COMPANY, MIMO_CONTACT } from '@/lib/constants/index'
import { APP_VERSION } from '@/lib/version'
import dynamic from 'next/dynamic'

// Analytics apenas se configurado e não desabilitado - dynamic import para não bloquear bundle inicial
const AnalyticsProvider = process.env.DISABLE_ANALYTICS !== 'true' && process.env.NEXT_PUBLIC_PLAUSIBLE_DOMAIN
  ? dynamic(() => import('@/components/analytics-provider').then(mod => ({ default: mod.AnalyticsProvider })), {
      ssr: true, // SSR permitido - componente é leve
    })
  : () => null

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
  display: 'optional', // Optional para não bloquear renderização - fallback mostra imediatamente
  preload: true, // Preload para melhorar LCP
  fallback: ['-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'sans-serif'],
  adjustFontFallback: false, // Não ajustar fallback para melhor performance
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
  display: 'optional', // Optional para não bloquear renderização - fallback mostra imediatamente
  preload: true, // Preload para melhorar LCP
  fallback: ['-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'sans-serif'],
  adjustFontFallback: false, // Não ajustar fallback para melhor performance
})

export const metadata: Metadata = {
  title: {
    default: 'beleza sem padrão | mimo salão',
    template: '%s | mimo salão',
  },
  description: 'salão de beleza inclusivo e acolhedor. serviços personalizados que cabem na sua vida real. agende seu mimo!',
  keywords: ['salão de beleza', 'beleza sem padrão', 'corte de cabelo', 'coloração', 'alongamento de cílios', 'manicure', 'pedicure', 'São Paulo', 'Vila Madalena'],
  generator: `Mimo Site v${APP_VERSION}`,
  alternates: {
    canonical: 'https://mimo-site.vercel.app',
  },
  openGraph: {
    type: 'website',
    locale: 'pt_BR',
    url: 'https://mimo-site.vercel.app',
    siteName: 'Mimo Salão',
    title: 'beleza sem padrão | mimo salão',
    description: 'salão de beleza inclusivo e acolhedor. serviços personalizados que cabem na sua vida real.',
    images: [
      {
        url: 'https://mimo-site.vercel.app/images/hero-bg.webp',
        width: 1920,
        height: 1080,
        alt: 'Mimo Salão - Beleza sem padrão',
      },
    ],
  },
  twitter: {
    card: 'summary_large_image',
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
        {/* Favicon */}
        <link rel="icon" href="/favicon.ico" sizes="any" />
        <link rel="icon" href="/images/MIMO Icon.svg" type="image/svg+xml" />
        
        {/* DNS prefetch para recursos externos (não críticos) */}
        <link rel="dns-prefetch" href="https://wa.me" />
        <link rel="dns-prefetch" href="https://www.instagram.com" />
        <link rel="dns-prefetch" href="https://www.facebook.com" />
        
        {/* Preconnect para Plausible - apenas se configurado e não desabilitado */}
        {process.env.DISABLE_ANALYTICS !== 'true' && process.env.NEXT_PUBLIC_PLAUSIBLE_DOMAIN && (
          <link rel="dns-prefetch" href="https://plausible.io" />
        )}
        
        {/* Preload hero image mobile para melhorar LCP - crítico para performance */}
        {/* Mobile first: carregar versão menor primeiro (28KB vs 135KB) */}
        {/* Nota: Warning sobre preload não usado pode aparecer se next/image usar srcset diferente,
            mas o preload ainda ajuda o browser a priorizar o recurso */}
        <link
          rel="preload"
          as="image"
          href="/images/hero-bg-mobile.webp"
          type="image/webp"
          fetchPriority="high"
          media="(max-width: 768px)"
        />
        <link
          rel="preload"
          as="image"
          href="/images/hero-bg.webp"
          type="image/webp"
          fetchPriority="high"
          media="(min-width: 769px)"
        />
        <script
          type="application/ld+json"
          dangerouslySetInnerHTML={{ __html: JSON.stringify(structuredData) }}
        />
      </head>
      <body className={`${bueno.variable} ${satoshi.variable}`} suppressHydrationWarning>
        <AnalyticsProvider />
        {/* Plausible Analytics - carregado com lazyOnload para não bloquear FCP/LCP */}
        {process.env.DISABLE_ANALYTICS !== 'true' && process.env.NEXT_PUBLIC_PLAUSIBLE_DOMAIN && (
          <Script
            defer
            data-domain={process.env.NEXT_PUBLIC_PLAUSIBLE_DOMAIN}
            src="https://plausible.io/js/script.js"
            strategy="lazyOnload"
          />
        )}
        {children}
      </body>
    </html>
  )
}

