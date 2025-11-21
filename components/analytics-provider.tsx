'use client'

import { useEffect } from 'react'
import { usePathname } from 'next/navigation'

/**
 * provider de analytics que inicializa google analytics 4 (ga4).
 * 
 * - carrega gtag apenas se NEXT_PUBLIC_GA_MEASUREMENT_ID estiver configurado
 * - tracking automático de pageviews (atualiza quando rota muda)
 * - eventos customizados via trackEvent()
 * 
 * nota: google analytics requer banner de consentimento (lgpd/gdpr).
 * 
 * @returns {null} componente não renderiza nada
 */
export function AnalyticsProvider() {
  const pathname = usePathname()

  useEffect(() => {
    const gaId = process.env.NEXT_PUBLIC_GA_MEASUREMENT_ID

    if (!gaId || typeof window === 'undefined') {
      return
    }

    // inicializa dataLayer se não existir
    if (!window.dataLayer) {
      window.dataLayer = []
    }

    // pega query string do window.location (não precisa de useSearchParams)
    const search = typeof window !== 'undefined' ? window.location.search : ''

    // inicializa gtag se não existir
    if (!window.gtag) {
      window.gtag = function (...args: Array<unknown>) {
        window.dataLayer?.push(args as unknown as Record<string, unknown>)
      }
      window.gtag('js', new Date())
      window.gtag('config', gaId, {
        page_path: pathname + search,
      })
    } else {
      // atualiza pageview quando rota muda
      window.gtag('config', gaId, {
        page_path: pathname + search,
      })
    }
  }, [pathname])

  return null
}

