import type { Metadata } from 'next'
import { Header } from '@/components/layout/header'
import { Footer } from '@/components/layout/footer'
import { ServicesGrid } from '@/components/sections/services-grid'

export const metadata: Metadata = {
  title: 'serviços',
  description: 'Conheça todos os serviços da Mimo: salão, esmalteria, cílios, micropigmentação, estética facial e corporal. Tudo em um só lugar.',
}

/**
 * página de serviços - grid geral.
 * 
 * reutiliza componente ServicesGrid da home.
 */
export default function ServicosPage() {
  return (
    <>
      <Header />
      <main className="pt-20">
        <ServicesGrid />
      </main>
      <Footer />
    </>
  )
}

