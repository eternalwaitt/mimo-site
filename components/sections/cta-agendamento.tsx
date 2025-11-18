'use client'

import { motion } from 'framer-motion'
import { Button } from '@/components/ui/button'
import { ImageWithFallback } from '@/components/ui/image-with-fallback'
import { getWhatsAppBookingUrl, getWhatsAppContactUrl, HOME_COPY } from '@/lib/constants'

/**
 * cta agendamento simplificado.
 * 
 * headline: "Pronta pra seu momento?"
 * 2 botões grandes: WhatsApp direto + "Falar com equipe"
 * background: foto ambiente + overlay
 * mobile: stack vertical
 */
export function CTAAgendamento() {
  return (
    <section className="relative py-20 md:py-32 overflow-hidden">
      {/* Background Image */}
      <div className="absolute inset-0 z-0">
        <div className="absolute inset-0 bg-mimo-brown/40 z-10" />
        <ImageWithFallback
          src="/images/cta-ambiente.jpg"
          alt="Ambiente acolhedor Mimo"
          fill
          sizes="100vw"
          className="object-cover"
        />
      </div>

      {/* Content */}
      <div className="relative z-20 container mx-auto px-4 sm:px-6 lg:px-8">
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.6 }}
          className="max-w-3xl mx-auto text-center"
        >
          <h2 className="font-bueno text-4xl md:text-5xl font-bold text-white mb-6">
            {HOME_COPY.ctaAgendamento.headline}
          </h2>

          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Button
              variant="primary"
              href={getWhatsAppBookingUrl()}
              external
              className="bg-white text-mimo-brown hover:bg-mimo-neutral-light text-xl px-10 py-5"
            >
              {HOME_COPY.ctaAgendamento.ctaWhatsApp}
            </Button>
            <Button
              variant="secondary"
              href={getWhatsAppContactUrl()}
              external
              className="border-white text-white hover:bg-white/10 text-xl px-10 py-5"
            >
              {HOME_COPY.ctaAgendamento.ctaEquipe}
            </Button>
          </div>
        </motion.div>
      </div>
    </section>
  )
}

