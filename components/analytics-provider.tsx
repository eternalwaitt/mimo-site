'use client'

import { useEffect } from 'react'
import { usePathname } from 'next/navigation'
import { trackPageView } from '@/lib/analytics'

/**
 * provider de analytics que inicializa plausible.
 * 
 * - script do plausible é carregado via Next.js Script component em layout.tsx
 * - tracking automático de pageviews (atualiza quando rota muda)
 * - eventos customizados via trackEvent()
 * 
 * nota: plausible é privacy-friendly e não requer banner de consentimento (lgpd/gdpr).
 * 
 * @returns {null} componente não renderiza nada
 */
export function AnalyticsProvider() {
  const pathname = usePathname()

  useEffect(() => {
    // não carregar se analytics estiver desabilitado
    if (process.env.DISABLE_ANALYTICS === 'true') {
      return
    }

    const plausibleDomain = process.env.NEXT_PUBLIC_PLAUSIBLE_DOMAIN

    if (!plausibleDomain || typeof window === 'undefined') {
      return
    }

    // aguarda script do plausible carregar (carregado via Next.js Script em layout.tsx)
    const checkPlausible = () => {
      if (window.plausible) {
        // tracka pageview quando rota muda
        trackPageView(pathname)
      } else {
        // tenta novamente após um delay se plausible ainda não estiver disponível
        setTimeout(checkPlausible, 100)
      }
    }

    checkPlausible()
  }, [pathname])

  return null
}
