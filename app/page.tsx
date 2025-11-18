import { Header } from '@/components/layout/header'
import { Footer } from '@/components/layout/footer'
import { HeroManifesto } from '@/components/sections/hero-manifesto'
import { TimeEconomy } from '@/components/sections/time-economy'
import { ServicesGrid } from '@/components/sections/services-grid'
import { MomentoMimo } from '@/components/sections/momento-mimo'
import { CTAAgendamento } from '@/components/sections/cta-agendamento'

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
 */
export default function HomePage() {
  return (
    <>
      <Header />
      <main>
        <HeroManifesto />
        <TimeEconomy />
        <ServicesGrid />
        <MomentoMimo />
        <CTAAgendamento />
      </main>
      <Footer />
    </>
  )
}

