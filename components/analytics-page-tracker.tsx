'use client'

import { useEffect } from 'react'
import { initScrollDepthTracking, initTimeOnPageTracking } from '@/lib/analytics'

/**
 * componente que inicializa tracking de scroll depth e time on page.
 * 
 * adiciona este componente em páginas onde queremos trackar engajamento.
 * tracking é automático e não interfere na performance.
 * 
 * @returns {null} componente não renderiza nada
 */
export function AnalyticsPageTracker() {
  useEffect(() => {
    const cleanupScroll = initScrollDepthTracking()
    const cleanupTime = initTimeOnPageTracking()

    return () => {
      cleanupScroll()
      cleanupTime()
    }
  }, [])

  return null
}

