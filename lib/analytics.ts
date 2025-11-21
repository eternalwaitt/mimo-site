/**
 * utilitários de analytics e tracking de eventos.
 * 
 * wrapper type-safe para plausible analytics com helpers para eventos comuns.
 * todos os eventos são opcionais - analytics só funciona se provider estiver configurado.
 * 
 * nota: api compatível com a anterior para facilitar migração.
 */

type EventProperties = Record<string, string | number | boolean | null | undefined>

/**
 * declaração de tipo para plausible no window.
 */
declare global {
  interface Window {
    plausible?: (
      eventName: string,
      options?: { props?: EventProperties; callback?: () => void }
    ) => void
  }
}

/**
 * verifica se analytics está habilitado.
 */
function isAnalyticsEnabled(): boolean {
  if (typeof window === 'undefined') return false
  if (process.env.DISABLE_ANALYTICS === 'true') return false
  return typeof window.plausible === 'function'
}

/**
 * tracka um evento customizado.
 * 
 * @param {string} eventName - nome do evento (ex: "cta_click", "service_view")
 * @param {EventProperties} [properties] - propriedades opcionais do evento
 * @returns {void}
 * 
 * @example
 * ```ts
 * trackEvent('custom_event', {
 *   category: 'engagement',
 *   value: 100
 * })
 * ```
 */
export function trackEvent(eventName: string, properties?: EventProperties): void {
  if (!isAnalyticsEnabled()) return

  if (window.plausible) {
    window.plausible(eventName, {
      props: properties,
    })
  }
}

/**
 * tracka clique em cta (whatsapp, agendamento, etc).
 * 
 * @param {string} ctaType - tipo de cta ("whatsapp_booking", "whatsapp_contact", "agendamento")
 * @param {string} [location] - onde o cta foi clicado (ex: "header", "cta_section", "service_page")
 * @returns {void}
 * 
 * @example
 * ```ts
 * trackCTAClick('whatsapp_booking', 'header')
 * ```
 */
export function trackCTAClick(ctaType: string, location?: string): void {
  trackEvent('cta_click', {
    cta_type: ctaType,
    location: location || 'unknown',
  })
}

/**
 * tracka visualização de página de serviço.
 * 
 * @param {string} serviceSlug - slug do serviço (ex: "salao", "cilios")
 * @returns {void}
 * 
 * @example
 * ```ts
 * trackServiceView('salao')
 * ```
 */
export function trackServiceView(serviceSlug: string): void {
  trackEvent('service_view', {
    service_slug: serviceSlug,
  })
}

/**
 * tracka clique em item de navegação.
 * 
 * @param {string} menuItem - label do item de menu (ex: "Home", "Serviços")
 * @param {string} href - href do link
 * @returns {void}
 * 
 * @example
 * ```ts
 * trackNavigationClick('Serviços', '/servicos')
 * ```
 */
export function trackNavigationClick(menuItem: string, href: string): void {
  trackEvent('navigation_click', {
    menu_item: menuItem,
    href,
  })
}

/**
 * tracka scroll depth (quanto da página foi lido).
 * 
 * @param depth - porcentagem de scroll (25, 50, 75, 100)
 */
export function trackScrollDepth(depth: number): void {
  trackEvent('scroll_depth', {
    depth_percent: depth,
  })
}

/**
 * tracka tempo na página.
 * 
 * @param seconds - segundos na página
 */
export function trackTimeOnPage(seconds: number): void {
  trackEvent('time_on_page', {
    seconds,
  })
}

/**
 * inicializa tracking de scroll depth automaticamente.
 * tracka quando usuário atinge 25%, 50%, 75% e 100% da página.
 * 
 * @returns {() => void} função de cleanup para remover event listeners
 * 
 * @example
 * ```ts
 * useEffect(() => {
 *   const cleanup = initScrollDepthTracking()
 *   return cleanup
 * }, [])
 * ```
 */
export function initScrollDepthTracking(): () => void {
  if (typeof window === 'undefined' || !isAnalyticsEnabled()) return () => {}

  const trackedDepths = new Set<number>()
  const depths = [25, 50, 75, 100]

  const handleScroll = () => {
    const windowHeight = window.innerHeight
    const documentHeight = document.documentElement.scrollHeight
    const scrollTop = window.scrollY || document.documentElement.scrollTop
    const scrollPercent = Math.round(((scrollTop + windowHeight) / documentHeight) * 100)

    depths.forEach((depth) => {
      if (scrollPercent >= depth && !trackedDepths.has(depth)) {
        trackedDepths.add(depth)
        trackScrollDepth(depth)
      }
    })
  }

  // throttle scroll events
  let ticking = false
  const throttledHandleScroll = () => {
    if (!ticking) {
      window.requestAnimationFrame(() => {
        handleScroll()
        ticking = false
      })
      ticking = true
    }
  }

  window.addEventListener('scroll', throttledHandleScroll, { passive: true })

  return () => {
    window.removeEventListener('scroll', throttledHandleScroll)
  }
}

/**
 * inicializa tracking de tempo na página.
 * tracka quando usuário fica 30s, 1min, 2min+ na página.
 * 
 * @returns {() => void} função de cleanup para remover interval
 * 
 * @example
 * ```ts
 * useEffect(() => {
 *   const cleanup = initTimeOnPageTracking()
 *   return cleanup
 * }, [])
 * ```
 */
export function initTimeOnPageTracking(): () => void {
  if (typeof window === 'undefined' || !isAnalyticsEnabled()) return () => {}

  const trackedTimes = new Set<number>()
  const times = [30, 60, 120] // 30s, 1min, 2min

  const startTime = Date.now()

  const checkTime = () => {
    const elapsed = Math.floor((Date.now() - startTime) / 1000)

    times.forEach((time) => {
      if (elapsed >= time && !trackedTimes.has(time)) {
        trackedTimes.add(time)
        trackTimeOnPage(time)
      }
    })
  }

  const interval = setInterval(checkTime, 5000) // check every 5s

  return () => {
    clearInterval(interval)
  }
}

/**
 * tracka pageview (usado pelo AnalyticsPageTracker).
 * 
 * @param path - caminho da página (ex: "/", "/servicos/salao")
 */
export function trackPageView(path: string): void {
  if (!isAnalyticsEnabled()) return

  // Plausible tracka pageviews automaticamente, mas podemos forçar um evento customizado
  if (window.plausible) {
    window.plausible('pageview', {
      props: {
        path,
      },
    })
  }
}
