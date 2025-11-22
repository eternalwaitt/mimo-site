import Image from 'next/image'
import { Button } from '@/components/ui/button'
import { getWhatsAppBookingUrl, HOME_COPY } from '@/lib/constants/index'

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
 * - animações de entrada com CSS (não framer-motion para melhor performance)
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
      {/* Background Image - Container fixo para evitar CLS */}
      <div 
        className="absolute inset-0 z-0 bg-mimo-neutral-light" 
        style={{ 
          minHeight: '100vh',
          height: '100vh', // Altura fixa para prevenir CLS
          width: '100%',
        }}
      >
        <div className="absolute inset-0 animate-hero-image-scale">
          <div className="absolute inset-0 bg-mimo-brown/20 z-10" />
          {/* Mobile: usar versão menor (28KB) - next/image direto para melhor LCP */}
          <Image
            src="/images/hero-bg-mobile.webp"
            alt={imageAlt}
            fill
            priority
            fetchPriority="high"
            sizes="100vw"
            className="object-cover md:hidden"
            quality={90}
            unoptimized={false}
          />
          {/* Desktop: usar versão completa */}
          <Image
            src={imageSrc}
            alt={imageAlt}
            fill
            priority
            fetchPriority="high"
            sizes="100vw"
            className="object-cover hidden md:block"
            quality={90}
            unoptimized={false}
          />
        </div>
      </div>

      {/* Content - Min height para evitar CLS */}
      <div className="relative z-20 container mx-auto px-4 sm:px-6 lg:px-8 text-center md:text-left min-h-[60vh] flex items-center">
        <div className="max-w-3xl mx-auto md:mx-0 w-full animate-hero-content-fade">
          <h1 className="font-bueno text-6xl md:text-7xl lg:text-8xl xl:text-9xl font-bold text-white mb-8 text-balance">
            {HOME_COPY.hero.headline}
          </h1>
          <p className="font-satoshi text-2xl md:text-3xl lg:text-4xl text-white/90 mb-12 leading-relaxed">
            {HOME_COPY.hero.subheadline}
          </p>

          <div className="flex flex-col sm:flex-row gap-6 justify-center md:justify-start">
            <Button
              href={getWhatsAppBookingUrl()}
              external
              className="inline-flex items-center justify-center rounded-lg font-bueno font-bold transition-all duration-300 focus-visible:outline-2 focus-visible:outline-mimo-gold focus-visible:outline-offset-2 bg-white text-mimo-brown hover:bg-mimo-neutral-light text-xl md:text-2xl px-10 md:px-12 py-5 md:py-6 active:scale-95"
            >
              {HOME_COPY.hero.ctaPrimary}
            </Button>
            <Button 
              variant="ghost" 
              href="/servicos" 
              className="!text-white !border-2 !border-white !bg-black/30 hover:!bg-black/40 backdrop-blur-sm text-xl md:text-2xl px-10 md:px-12 py-5 md:py-6"
            >
              {HOME_COPY.hero.ctaSecondary}
            </Button>
          </div>
        </div>
      </div>
    </section>
  )
}

