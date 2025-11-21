'use client'

import { useState, useEffect, useCallback } from 'react'
import Link from 'next/link'
import { trackCTAClick, trackNavigationClick } from '@/lib/analytics'

type MenuItem = {
  href: string
  label: string
  comingSoon?: boolean
}

type HeaderClientProps = {
  menuItemsLeft: Array<MenuItem>
  menuItemsRight: Array<MenuItem>
  whatsappUrl: string
}

/**
 * client island do header - apenas lógica interativa.
 * 
 * - scroll detection (aplica classe ao header)
 * - menu mobile toggle e drawer
 * - event handlers (navigation, CTA)
 * - controles mobile e desktop renderizados como React components
 */
export function HeaderClient({
  menuItemsLeft,
  menuItemsRight,
  whatsappUrl,
}: HeaderClientProps) {
  const [isScrolled, setIsScrolled] = useState(false)
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false)

  useEffect(() => {
    const handleScroll = () => {
      setIsScrolled(window.scrollY > 20)
    }

    window.addEventListener('scroll', handleScroll, { passive: true })
    handleScroll() // initial check
    
    return () => {
      window.removeEventListener('scroll', handleScroll)
    }
  }, [])

  useEffect(() => {
    // aplica classe de scroll ao header
    const header = document.querySelector('header')
    if (header) {
      if (isScrolled) {
        header.classList.add('bg-white/95', 'backdrop-blur-md', 'shadow-sm')
        header.classList.remove('bg-transparent')
      } else {
        header.classList.add('bg-transparent')
        header.classList.remove('bg-white/95', 'backdrop-blur-md', 'shadow-sm')
      }
    }
  }, [isScrolled])

  useEffect(() => {
    // adiciona event listeners aos links de navegação desktop
    const navLinks = document.querySelectorAll('[data-nav-label][data-nav-href]')
    const handleNavClick = (e: Event) => {
      const target = e.currentTarget as HTMLElement
      const label = target.getAttribute('data-nav-label')
      const href = target.getAttribute('data-nav-href')
      if (label && href) {
        trackNavigationClick(label, href)
      }
    }
    navLinks.forEach((link) => {
      link.addEventListener('click', handleNavClick)
    })
    return () => {
      navLinks.forEach((link) => {
        link.removeEventListener('click', handleNavClick)
      })
    }
  }, [])

  // injeta desktop CTA button via React portal
  useEffect(() => {
    const placeholder = document.getElementById('header-cta-desktop')
    if (placeholder) {
      const link = document.createElement('a')
      link.href = whatsappUrl
      link.target = '_blank'
      link.rel = 'noopener noreferrer'
      link.className = 'inline-flex items-center justify-center px-10 py-4 rounded-lg font-bueno font-bold text-xl transition-all duration-300 bg-mimo-brown text-white hover:bg-[#3a2519] active:scale-95 focus-visible:outline-2 focus-visible:outline-mimo-gold focus-visible:outline-offset-2'
      link.textContent = 'Agendar'
      link.addEventListener('click', () => handleCTAClick('header'))
      placeholder.innerHTML = ''
      placeholder.appendChild(link)
      
      return () => {
        placeholder.innerHTML = ''
      }
    }
  }, [whatsappUrl])

  const handleCTAClick = useCallback((source: string) => {
    trackCTAClick('whatsapp_booking', source)
  }, [])

  const handleMobileNavClick = useCallback((label: string, href: string) => {
    trackNavigationClick(label, href)
    setIsMobileMenuOpen(false)
  }, [])

  const toggleMobileMenu = useCallback(() => {
    setIsMobileMenuOpen((prev) => !prev)
  }, [])

  // fecha menu ao pressionar ESC
  useEffect(() => {
    const handleEscape = (e: KeyboardEvent) => {
      if (e.key === 'Escape' && isMobileMenuOpen) {
        setIsMobileMenuOpen(false)
      }
    }
    window.addEventListener('keydown', handleEscape)
    return () => {
      window.removeEventListener('keydown', handleEscape)
    }
  }, [isMobileMenuOpen])

  // previne scroll do body quando menu está aberto
  useEffect(() => {
    if (isMobileMenuOpen) {
      document.body.style.overflow = 'hidden'
    } else {
      document.body.style.overflow = ''
    }
    return () => {
      document.body.style.overflow = ''
    }
  }, [isMobileMenuOpen])

  // Injetar mobile controls diretamente no DOM (sem portal para evitar problemas de z-index)
  useEffect(() => {
    const container = document.getElementById('header-mobile-controls')
    if (!container) return

    // Limpar conteúdo anterior
    container.innerHTML = ''

    // Criar wrapper
    const wrapper = document.createElement('div')
    wrapper.className = 'flex items-center space-x-4'

    // Criar link Agendar
    const ctaLink = document.createElement('a')
    ctaLink.href = whatsappUrl
    ctaLink.target = '_blank'
    ctaLink.rel = 'noopener noreferrer'
    ctaLink.className = 'inline-flex items-center justify-center px-8 py-3 rounded-lg font-bueno font-bold text-lg transition-all duration-300 bg-mimo-brown text-white hover:bg-[#3a2519] active:scale-95 focus-visible:outline-2 focus-visible:outline-mimo-gold focus-visible:outline-offset-2 min-h-[44px] min-w-[44px]'
    ctaLink.textContent = 'Agendar'
    ctaLink.addEventListener('click', (e) => {
      e.preventDefault()
      handleCTAClick('header_mobile')
      window.open(whatsappUrl, '_blank', 'noopener,noreferrer')
    })

    // Criar botão menu
    const menuButton = document.createElement('button')
    menuButton.type = 'button'
    menuButton.className = 'p-2 text-mimo-brown min-h-[44px] min-w-[44px] flex items-center justify-center focus-visible:outline-2 focus-visible:outline-mimo-gold focus-visible:outline-offset-2 rounded-lg hover:bg-mimo-neutral-light transition-colors relative z-[60]'
    menuButton.setAttribute('aria-label', isMobileMenuOpen ? 'Fechar menu' : 'Abrir menu')
    menuButton.setAttribute('aria-expanded', String(isMobileMenuOpen))
    menuButton.addEventListener('click', (e) => {
      e.preventDefault()
      e.stopPropagation()
      toggleMobileMenu()
    })

    // SVG do menu
    const menuIcon = document.createElementNS('http://www.w3.org/2000/svg', 'svg')
    menuIcon.setAttribute('class', 'w-6 h-6')
    menuIcon.setAttribute('fill', 'none')
    menuIcon.setAttribute('stroke', 'currentColor')
    menuIcon.setAttribute('viewBox', '0 0 24 24')
    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path')
    if (isMobileMenuOpen) {
      path.setAttribute('d', 'M6 18L18 6M6 6l12 12')
    } else {
      path.setAttribute('d', 'M4 6h16M4 12h16M4 18h16')
    }
    path.setAttribute('stroke-linecap', 'round')
    path.setAttribute('stroke-linejoin', 'round')
    path.setAttribute('stroke-width', '2')
    menuIcon.appendChild(path)
    menuButton.appendChild(menuIcon)

    wrapper.appendChild(ctaLink)
    wrapper.appendChild(menuButton)
    container.appendChild(wrapper)

    // Atualizar ícone quando estado muda
    const updateIcon = () => {
      menuButton.setAttribute('aria-label', isMobileMenuOpen ? 'Fechar menu' : 'Abrir menu')
      menuButton.setAttribute('aria-expanded', String(isMobileMenuOpen))
      const svg = menuButton.querySelector('svg')
      if (svg) {
        const pathEl = svg.querySelector('path')
        if (pathEl) {
          pathEl.setAttribute('d', isMobileMenuOpen ? 'M6 18L18 6M6 6l12 12' : 'M4 6h16M4 12h16M4 18h16')
        }
      }
    }

    // Observer para mudanças de estado
    const observer = new MutationObserver(updateIcon)
    observer.observe(document.body, { attributes: true, attributeFilter: ['data-menu-state'] })

    return () => {
      container.innerHTML = ''
      observer.disconnect()
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [whatsappUrl, isMobileMenuOpen])

  return (
    <>

      {/* Mobile menu drawer */}
      {isMobileMenuOpen && (
        <>
          <div
            className="fixed inset-0 bg-black/50 z-[60] lg:hidden"
            onClick={() => setIsMobileMenuOpen(false)}
            aria-hidden="true"
          />
          <div
            className="fixed top-20 left-0 right-0 bg-white z-[70] lg:hidden shadow-xl"
            role="dialog"
            aria-modal="true"
            aria-label="Menu de navegação"
          >
            <nav className="container mx-auto px-4 py-6">
              <div className="space-y-4">
                {menuItemsLeft.map((item) => (
                  <Link
                    key={item.href}
                    href={item.href}
                    className="block font-satoshi text-lg text-mimo-blue hover:text-mimo-brown transition-colors py-2 min-h-[44px] flex items-center"
                    onClick={() => handleMobileNavClick(item.label, item.href)}
                  >
                    {item.label}
                  </Link>
                ))}
                {menuItemsRight.map((item) => (
                  <div key={item.href} className="py-2 min-h-[44px] flex items-center">
                    {item.comingSoon ? (
                      <span className="font-satoshi text-lg text-mimo-blue/70 flex items-center gap-2">
                        {item.label}
                        <span className="text-sm text-mimo-brown">👀 Em breve</span>
                      </span>
                    ) : (
                      <Link
                        href={item.href}
                        className="block font-satoshi text-lg text-mimo-blue hover:text-mimo-brown transition-colors"
                        onClick={() => setIsMobileMenuOpen(false)}
                      >
                        {item.label}
                      </Link>
                    )}
                  </div>
                ))}
              </div>
            </nav>
          </div>
        </>
      )}
    </>
  )
}
