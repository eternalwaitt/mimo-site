'use client'

import { Button } from '@/components/ui/button'
import { ImageWithFallback } from '@/components/ui/image-with-fallback'
import { getWhatsAppBookingUrl, getWhatsAppContactUrl, HOME_COPY } from '@/lib/constants'
import { trackCTAClick } from '@/lib/analytics'

/**
 * cta de agendamento simplificado.
 * 
 * - headline: "Pronta pra seu momento?"
 * - 2 botões grandes: WhatsApp direto + "Falar com equipe"
 * - background: foto ambiente + overlay marrom
 * - mobile: stack vertical
 * - animações de entrada com CSS (replaced framer-motion for performance)
 * 
 * @returns {JSX.Element} seção de CTA de agendamento
 */
export function CTAAgendamento() {
  return (
    <section className="relative py-20 md:py-32 overflow-hidden">
      {/* Background Image - Container com background e altura fixa para evitar CLS */}
      <div 
        className="absolute inset-0 z-0 bg-mimo-neutral-light" 
        style={{ 
          minHeight: '400px',
          height: '100%',
          width: '100%',
        }}
      >
        <div className="absolute inset-0 bg-mimo-brown/40 z-10" />
        <ImageWithFallback
          src="/images/cta-ambiente.jpg"
          alt="Ambiente acolhedor Mimo"
          fill
          sizes="(max-width: 768px) 100vw, 1920px"
          className="object-cover"
          quality={85}
        />
      </div>

      {/* Content */}
      <div className="relative z-20 container mx-auto px-4 sm:px-6 lg:px-8">
        <div className="max-w-3xl mx-auto text-center animate-fade-in-up">
          <h2 className="font-bueno text-4xl md:text-5xl font-bold text-white mb-6">
            {HOME_COPY.ctaAgendamento.headline}
          </h2>

          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Button
              variant="primary"
              href={getWhatsAppBookingUrl()}
              external
              className="bg-white text-mimo-brown hover:bg-mimo-neutral-light text-xl px-10 py-5"
              onClick={() => trackCTAClick('whatsapp_booking', 'cta_section')}
            >
              {HOME_COPY.ctaAgendamento.ctaWhatsApp}
            </Button>
            <Button
              variant="secondary"
              href={getWhatsAppContactUrl()}
              external
              className="border-white text-white hover:bg-white/10 text-xl px-10 py-5"
              onClick={() => trackCTAClick('whatsapp_contact', 'cta_section')}
            >
              {HOME_COPY.ctaAgendamento.ctaEquipe}
            </Button>
          </div>
        </div>
      </div>
    </section>
  )
}

