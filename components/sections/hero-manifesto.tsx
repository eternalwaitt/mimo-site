'use client'

import { motion } from 'framer-motion'
import { Button } from '@/components/ui/button'
import { ImageWithFallback } from '@/components/ui/image-with-fallback'
import { getWhatsAppBookingUrl, HOME_COPY } from '@/lib/constants'
import Link from 'next/link'

/**
 * props do componente hero-manifesto.
 */
type HeroManifestoProps = {
  /** url da imagem de fundo (padrão: /images/hero-bg.webp) */
  imageSrc?: string
  /** texto alternativo da imagem */
  imageAlt?: string
}

/**
 * hero + manifesto integrado.
 * 
 * - full-screen image com overlay marrom 20% opacity
 * - headline BUENO (4-6 palavras), subheadline Satoshi (1 linha)
 * - CTA primário WhatsApp + CTA secundário ghost
 * - parallax sutil (foto com scale animation)
 * - mobile: min-h-screen, texto centralizado
 * - animações de entrada com framer-motion
 * 
 * @param {HeroManifestoProps} props - propriedades do componente
 * @returns {JSX.Element} seção hero com manifesto
 */
export function HeroManifesto({
  imageSrc = '/images/hero-bg.webp',
  imageAlt = 'Mimo Salão - Beleza sem padrão',
}: HeroManifestoProps) {
  return (
    <section className="relative min-h-screen flex items-center justify-center overflow-hidden">
      {/* Background Image com Parallax - Container fixo para evitar CLS */}
      <div className="absolute inset-0 z-0 bg-mimo-neutral-light" style={{ minHeight: '100vh' }}>
        <motion.div
          className="absolute inset-0"
          initial={{ scale: 1.1 }}
          animate={{ scale: 1 }}
          transition={{ duration: 1.2, ease: 'easeOut' }}
          style={{ willChange: 'transform' }}
        >
          <div className="absolute inset-0 bg-mimo-brown/20 z-10" />
          <ImageWithFallback
            src={imageSrc}
            alt={imageAlt}
            fill
            priority
            sizes="100vw"
            className="object-cover"
          />
        </motion.div>
      </div>

      {/* Content - Min height para evitar CLS */}
      <div className="relative z-20 container mx-auto px-4 sm:px-6 lg:px-8 text-center md:text-left min-h-[60vh] flex items-center">
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.8, delay: 0.2 }}
          className="max-w-3xl mx-auto md:mx-0 w-full"
        >
          <h1 className="font-bueno text-6xl md:text-7xl lg:text-8xl xl:text-9xl font-bold text-white mb-8 text-balance">
            {HOME_COPY.hero.headline}
          </h1>
          <p className="font-satoshi text-2xl md:text-3xl lg:text-4xl text-white/90 mb-12 leading-relaxed">
            {HOME_COPY.hero.subheadline}
          </p>

          <div className="flex flex-col sm:flex-row gap-6 justify-center md:justify-start">
            <Button
              variant="primary"
              href={getWhatsAppBookingUrl()}
              external
              className="bg-white text-mimo-brown hover:bg-mimo-neutral-light text-xl md:text-2xl px-10 md:px-12 py-5 md:py-6"
            >
              {HOME_COPY.hero.ctaPrimary}
            </Button>
            <Button 
              variant="ghost" 
              href="/servicos" 
              className="text-white border-2 border-white hover:bg-white/10 text-xl md:text-2xl px-10 md:px-12 py-5 md:py-6"
            >
              {HOME_COPY.hero.ctaSecondary}
            </Button>
          </div>
        </motion.div>
      </div>
    </section>
  )
}

