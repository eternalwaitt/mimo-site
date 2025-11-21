import type { Metadata } from 'next'
import { notFound } from 'next/navigation'
import Link from 'next/link'
import { Header } from '@/components/layout/header'
import { Footer } from '@/components/layout/footer'
import { Button } from '@/components/ui/button'
import { JOB_OPENINGS, MIMO_CONTACT, getWhatsAppJobApplicationUrl } from '@/lib/constants/index'

type Props = {
  params: Promise<{ slug: string }>
}

export async function generateStaticParams() {
  return JOB_OPENINGS.map((job) => ({
    slug: job.slug,
  }))
}

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  const { slug } = await params
  const job = JOB_OPENINGS.find((j) => j.slug === slug)

  if (!job) {
    return {
      title: 'Vaga não encontrada',
    }
  }

  return {
    title: `${job.title} - Trabalhe Aqui | Mimo`,
    description: job.description,
  }
}

export default async function JobPage({ params }: Props) {
  const { slug } = await params
  const job = JOB_OPENINGS.find((j) => j.slug === slug)

  if (!job) {
    notFound()
  }

  const whatsappUrl = getWhatsAppJobApplicationUrl(job.title)
  const emailUrl = `mailto:${MIMO_CONTACT.email}?subject=Candidatura - ${job.title}`

  return (
    <>
      <Header />
      <main className="pt-20">
        {/* Hero */}
        <section className="relative py-16 md:py-24 bg-mimo-neutral-light">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8">
            <Link
              href="/trabalhe-aqui"
              className="inline-flex items-center gap-2 font-satoshi text-mimo-blue hover:text-mimo-brown transition-colors mb-6"
            >
              <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
              </svg>
              Voltar para vagas
            </Link>
            <div className="max-w-4xl">
              <span className="inline-block rounded-full bg-mimo-brown text-white px-4 py-2 font-satoshi text-sm font-medium mb-4">
                {job.area}
              </span>
              <h1 className="font-bueno text-5xl md:text-6xl font-bold text-mimo-brown mb-6">
                {job.title}
              </h1>
              <p className="font-satoshi text-xl text-mimo-blue leading-relaxed">
                {job.description}
              </p>
            </div>
          </div>
        </section>

        {/* Descrição Completa */}
        {job.fullDescription && (
          <section className="py-16 md:py-24 bg-white">
            <div className="container mx-auto px-4 sm:px-6 lg:px-8">
              <div className="max-w-4xl">
                <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-6">
                  Sobre a Vaga
                </h2>
                <div className="font-satoshi text-lg text-mimo-blue leading-relaxed whitespace-pre-line">
                  {job.fullDescription}
                </div>
              </div>
            </div>
          </section>
        )}

        {/* Responsabilidades */}
        {job.responsibilities && job.responsibilities.length > 0 && (
          <section className="py-16 md:py-24 bg-mimo-neutral-light">
            <div className="container mx-auto px-4 sm:px-6 lg:px-8">
              <div className="max-w-4xl">
                <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-8">
                  Principais Responsabilidades
                </h2>
                <ul className="space-y-4">
                  {job.responsibilities.map((responsibility, index) => (
                    <li key={index} className="font-satoshi text-lg text-mimo-blue flex items-start">
                      <span className="text-mimo-brown mr-3 mt-1">•</span>
                      <span>{responsibility}</span>
                    </li>
                  ))}
                </ul>
              </div>
            </div>
          </section>
        )}

        {/* Requisitos */}
        <section className="py-16 md:py-24 bg-white">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8">
            <div className="max-w-4xl">
              <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-8">
                Requisitos
              </h2>
              <ul className="space-y-4">
                {job.requirements.map((requirement, index) => (
                  <li key={index} className="font-satoshi text-lg text-mimo-blue flex items-start">
                    <span className="text-mimo-gold mr-3 mt-1">•</span>
                    <span>{requirement}</span>
                  </li>
                ))}
              </ul>
            </div>
          </div>
        </section>

        {/* Benefícios */}
        {job.benefits && job.benefits.length > 0 && (
          <section className="py-16 md:py-24 bg-mimo-neutral-light">
            <div className="container mx-auto px-4 sm:px-6 lg:px-8">
              <div className="max-w-4xl">
                <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-8">
                  O que Oferecemos
                </h2>
                <ul className="space-y-4">
                  {job.benefits.map((benefit, index) => (
                    <li key={index} className="font-satoshi text-lg text-mimo-blue flex items-start">
                      <span className="text-mimo-brown mr-3 mt-1">•</span>
                      <span>{benefit}</span>
                    </li>
                  ))}
                </ul>
              </div>
            </div>
          </section>
        )}

        {/* CTA Candidatura */}
        <section className="py-16 md:py-24 bg-mimo-brown">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8">
            <div className="max-w-4xl mx-auto text-center">
              <h2 className="font-bueno text-4xl font-bold text-white mb-6">
                Interessado(a) nesta vaga?
              </h2>
              <p className="font-satoshi text-xl text-white/90 mb-8">
                Envie sua candidatura e venha fazer parte da equipe Mimo!
              </p>
              <div className="flex flex-col sm:flex-row gap-4 justify-center">
                {job.contactMethod === 'whatsapp' ? (
                  <Button
                    variant="primary"
                    href={whatsappUrl}
                    external
                    className="bg-white text-mimo-brown hover:bg-mimo-neutral-light text-xl px-10 py-5"
                  >
                    Candidatar-se via WhatsApp
                  </Button>
                ) : (
                  <Button
                    variant="primary"
                    href={emailUrl}
                    className="bg-white text-mimo-brown hover:bg-mimo-neutral-light text-xl px-10 py-5"
                  >
                    Enviar Currículo por Email
                  </Button>
                )}
                <Button
                  variant="ghost"
                  href="/trabalhe-aqui"
                  className="text-white border-2 border-white hover:bg-white/10 text-xl px-10 py-5"
                >
                  Ver outras vagas
                </Button>
              </div>
            </div>
          </div>
        </section>
      </main>
      <Footer />
    </>
  )
}

