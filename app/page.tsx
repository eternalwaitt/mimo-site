import { Header } from '@/components/layout/header'
import { Footer } from '@/components/layout/footer'
import { HeroManifesto } from '@/components/sections/hero-manifesto'
import dynamic from 'next/dynamic'

// Lazy load AnalyticsPageTracker (não crítico para LCP)
const AnalyticsPageTracker = dynamic(
  () => import('@/components/analytics-page-tracker').then(mod => ({ default: mod.AnalyticsPageTracker })),
  { ssr: true } // SSR mantido para melhor SEO
)

// Lazy load ErrorBoundary (não crítico para LCP)
const ErrorBoundary = dynamic(
  () => import('@/components/error-boundary').then(mod => ({ default: mod.ErrorBoundary })),
  { ssr: true }
)

// Lazy load componentes abaixo do fold para melhorar LCP e reduzir bundle inicial
const TimeEconomy = dynamic(
  () => import('@/components/sections/time-economy').then(mod => ({ default: mod.TimeEconomy })),
  { ssr: true }
)

const ServicesGrid = dynamic(
  () => import('@/components/sections/services-grid').then(mod => ({ default: mod.ServicesGrid })),
  { ssr: true }
)

const MomentoMimo = dynamic(
  () => import('@/components/sections/momento-mimo').then(mod => ({ default: mod.MomentoMimo })),
  { ssr: true }
)

const CTAAgendamento = dynamic(
  () => import('@/components/sections/cta-agendamento').then(mod => ({ default: mod.CTAAgendamento })),
  { ssr: true }
)

/**
 * home page - integra todas as seções na ordem especificada.
 * 
 * 1. Header Fixo
 * 2. Hero + Manifesto
 * 3. Economia de Tempo
 * 4. Serviços Grid
 * 5. #MomentoMIMO
 * 6. CTA Agendamento
 * 7. Footer
 * 
 * Note: Client components are automatically code-split by Next.js.
 */
export default function HomePage() {
  return (
    <>
      <Header isHomepage={true} />
      <AnalyticsPageTracker />
      <main id="main-content">
        <ErrorBoundary>
          <HeroManifesto />
        </ErrorBoundary>
        <ErrorBoundary>
          <TimeEconomy />
        </ErrorBoundary>
        <ErrorBoundary>
          <ServicesGrid />
        </ErrorBoundary>
        <ErrorBoundary>
          <MomentoMimo />
        </ErrorBoundary>
        <ErrorBoundary>
          <CTAAgendamento />
        </ErrorBoundary>
      </main>
      <Footer />
    </>
  )
}

