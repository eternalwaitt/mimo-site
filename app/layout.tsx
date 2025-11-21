import type { Metadata } from 'next'
import localFont from 'next/font/local'
import './globals.css'
import { MIMO_COMPANY, MIMO_CONTACT } from '@/lib/constants'
import { APP_VERSION } from '@/lib/version'
import { AnalyticsProvider } from '@/components/analytics-provider'

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
        {/* DNS prefetch para recursos externos */}
        <link rel="dns-prefetch" href="https://wa.me" />
        <link rel="dns-prefetch" href="https://www.instagram.com" />
        <link rel="dns-prefetch" href="https://www.facebook.com" />
        
        {/* Preconnect para recursos críticos */}
        <link rel="preconnect" href="https://wa.me" crossOrigin="anonymous" />
        
        {/* Note: Hero image preload removed - Next.js Image with priority and fetchPriority handles this automatically */}
        <script
          type="application/ld+json"
          dangerouslySetInnerHTML={{ __html: JSON.stringify(structuredData) }}
        />
      </head>
      <body className={`${bueno.variable} ${satoshi.variable}`} suppressHydrationWarning>
        <AnalyticsProvider />
        {/* Google Analytics 4 */}
        {process.env.NEXT_PUBLIC_GA_MEASUREMENT_ID && (
          <>
            <script
              async
              src={`https://www.googletagmanager.com/gtag/js?id=${process.env.NEXT_PUBLIC_GA_MEASUREMENT_ID}`}
            />
            <script
              dangerouslySetInnerHTML={{
                __html: `
                  window.dataLayer = window.dataLayer || [];
                  function gtag(){dataLayer.push(arguments);}
                  gtag('js', new Date());
                  gtag('config', '${process.env.NEXT_PUBLIC_GA_MEASUREMENT_ID}');
                `,
              }}
            />
          </>
        )}
        {/* Microsoft Clarity */}
        {process.env.NEXT_PUBLIC_CLARITY_PROJECT_ID && (
          <script
            type="text/javascript"
            dangerouslySetInnerHTML={{
              __html: `
                (function(c,l,a,r,i,t,y){
                  c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
                  t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
                  y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
                })(window, document, "clarity", "script", "${process.env.NEXT_PUBLIC_CLARITY_PROJECT_ID}");
              `,
            }}
          />
        )}
        {children}
      </body>
    </html>
  )
}

