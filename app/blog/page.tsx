import type { Metadata } from 'next'
import { Header } from '@/components/layout/header'
import { Footer } from '@/components/layout/footer'

export const metadata: Metadata = {
  title: 'blog',
  description: 'Dicas, tendências e conteúdo sobre beleza, bem-estar e cuidados pessoais.',
}

/**
 * página blog - estrutura preparada (fase 2).
 * 
 * - placeholder para futura implementação
 * - layout editorial planejado (não lista de posts)
 * - categorias planejadas: Dicas, Tendências, Bem-estar
 */
export default function BlogPage() {
  return (
    <>
      <Header />
      <main className="pt-20">
        <section className="relative h-[50vh] min-h-[300px] flex items-center justify-center bg-mimo-neutral-light">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 className="font-bueno text-5xl md:text-6xl font-bold text-mimo-brown mb-4">
              Blog
            </h1>
            <p className="font-satoshi text-xl text-mimo-blue max-w-2xl mx-auto">
              Em breve: conteúdo sobre beleza, tendências e bem-estar
            </p>
          </div>
        </section>
      </main>
      <Footer />
    </>
  )
}

