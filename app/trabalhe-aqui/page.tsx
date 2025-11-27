import type { Metadata } from 'next'
import { Header } from '@/components/layout/header'
import { Footer } from '@/components/layout/footer'
import { JobCard } from '@/components/ui/job-card'
import { Button } from '@/components/ui/button'
import { JOB_OPENINGS, JOB_BENEFITS, MIMO_COMPANY, MIMO_CONTACT, getWhatsAppContactUrl } from '@/lib/constants/index'

export const metadata: Metadata = {
  title: 'trabalhe aqui',
  description: 'Venha fazer parte da equipe Mimo! Estamos sempre em busca de profissionais talentosos e apaixonados por beleza e bem-estar.',
  alternates: {
    canonical: 'https://mimo-site.vercel.app/trabalhe-aqui',
  },
  openGraph: {
    title: 'Trabalhe Aqui | Mimo Salão',
    description: 'Venha fazer parte da equipe Mimo! Estamos sempre em busca de profissionais talentosos e apaixonados por beleza e bem-estar.',
    url: 'https://mimo-site.vercel.app/trabalhe-aqui',
    type: 'website',
  },
}

/**
 * página trabalhe aqui - carreiras completa.
 * 
 * - hero: missão da empresa + descrição breve sobre cultura/valores
 * - vagas em aberto: grid de cards (JobCard)
 * - por que trabalhar na Mimo: seção com benefícios
 * - como se candidatar: instruções + contatos (email/WhatsApp)
 * - CTA final: "Tem dúvidas? Fale com a gente"
 * - server component com metadata para SEO
 */
export default function TrabalheAquiPage() {
  return (
    <>
      <Header />
      <main className="pt-20">
        {/* Hero */}
        <section className="relative h-[50vh] min-h-[300px] flex items-center justify-center bg-mimo-neutral-light">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 className="font-bueno text-5xl md:text-6xl font-bold text-mimo-brown mb-4">
              Trabalhe Aqui
            </h1>
            <p className="font-satoshi text-xl text-mimo-blue max-w-2xl mx-auto">
              {MIMO_COMPANY.mission}
            </p>
          </div>
        </section>

        {/* Vagas em Aberto */}
        <section className="py-16 md:py-24 bg-white">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 className="font-bueno text-4xl font-bold text-mimo-brown mb-12 text-center">
              Vagas em Aberto
            </h2>
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
              {JOB_OPENINGS.map((job) => (
                <JobCard key={job.id} job={job} />
              ))}
            </div>
          </div>
        </section>

        {/* Por que Trabalhar na Mimo */}
        <section className="py-16 md:py-24 bg-mimo-neutral-light">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 className="font-bueno text-4xl font-bold text-mimo-brown mb-12 text-center">
              Por que Trabalhar na Mimo?
            </h2>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
              {JOB_BENEFITS.map((benefit, index) => (
                <div
                  key={index}
                  className="bg-white rounded-xl p-6 shadow-md"
                >
                  <h3 className="font-bueno text-xl font-bold text-mimo-brown mb-2">
                    {benefit.title}
                  </h3>
                  <p className="font-satoshi text-mimo-blue leading-relaxed">
                    {benefit.description}
                  </p>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* Como se Candidatar */}
        <section className="py-16 md:py-24 bg-white">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl">
            <h2 className="font-bueno text-4xl font-bold text-mimo-brown mb-8 text-center">
              Como se Candidatar
            </h2>
            <div className="bg-mimo-neutral-light rounded-xl p-8 space-y-6">
              <p className="font-satoshi text-lg text-mimo-blue leading-relaxed">
                Para se candidatar, envie seu currículo e uma carta de apresentação contando um pouco sobre você e por que gostaria de trabalhar na Mimo.
              </p>

              <div className="space-y-4">
                <div>
                  <h3 className="font-bueno text-lg font-bold text-mimo-brown mb-2">
                    Por Email
                  </h3>
                  <a
                    href={`mailto:${MIMO_CONTACT.email}?subject=Candidatura - Trabalhe Conosco`}
                    className="font-satoshi text-mimo-blue hover:text-mimo-brown transition-colors"
                  >
                    {MIMO_CONTACT.email}
                  </a>
                </div>

                <div>
                  <h3 className="font-bueno text-lg font-bold text-mimo-brown mb-2">
                    Ou pelo WhatsApp
                  </h3>
                  <p className="font-satoshi text-mimo-blue mb-3">
                    Entre em contato conosco para saber mais sobre oportunidades disponíveis.
                  </p>
                  <Button
                    variant="primary"
                    href={getWhatsAppContactUrl()}
                    external
                    className="text-xl px-10 py-5"
                  >
                    Falar no WhatsApp
                  </Button>
                </div>
              </div>

              <div className="pt-4 border-t border-mimo-neutral-medium">
                <p className="font-satoshi text-sm text-mimo-brown">
                  <strong>O que incluímos no processo:</strong> análise do currículo, entrevista presencial e período de experiência quando aplicável.
                </p>
              </div>
            </div>
          </div>
        </section>

        {/* CTA Final */}
        <section className="py-16 md:py-24 bg-mimo-neutral-light">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p className="font-satoshi text-lg text-mimo-blue mb-6">
              Tem dúvidas sobre o processo seletivo ou quer saber mais sobre trabalhar na Mimo?
            </p>
            <Button
              variant="primary"
              href={getWhatsAppContactUrl()}
              external
              className="text-xl px-10 py-5"
            >
              Falar com a gente
            </Button>
          </div>
        </section>
      </main>
      <Footer />
    </>
  )
}

