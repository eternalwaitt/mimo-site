'use client'

import { useState, useEffect } from 'react'
import Link from 'next/link'
import Image from 'next/image'
import { Button } from '@/components/ui/button'
import { getWhatsAppBookingUrl } from '@/lib/constants'
import { cn } from '@/lib/utils'

/**
 * header fixo minimalista.
 * 
 * - logo esquerda, menu central, CTA "Agendar" direita
 * - sticky com backdrop blur no scroll
 * - itens "em breve" com tooltip à esquerda
 * - navegação responsiva
 * - transições suaves
 */
export function Header() {
  const [isScrolled, setIsScrolled] = useState(false)

  useEffect(() => {
    const handleScroll = () => {
      setIsScrolled(window.scrollY > 20)
    }

    window.addEventListener('scroll', handleScroll)
    return () => window.removeEventListener('scroll', handleScroll)
  }, [])

  const menuItemsLeft = [
    { href: '/', label: 'Home' },
    { href: '/servicos', label: 'Serviços' },
    { href: '/galeria', label: 'Galeria' },
    { href: '/sobre', label: 'Sobre' },
    { href: '/trabalhe-aqui', label: 'Trabalhe Aqui' },
  ]

  const menuItemsRight = [
    { href: '/blog', label: 'Blog', comingSoon: true },
    { href: '/hub', label: 'Mimo HUB', comingSoon: true },
  ]

  return (
    <header
      className={cn(
        'fixed top-0 left-0 right-0 z-50 transition-all duration-300',
        isScrolled
          ? 'bg-white/95 backdrop-blur-md shadow-sm'
          : 'bg-transparent'
      )}
    >
      <nav className="container mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-20">
          {/* Menu Esquerda */}
          <div className="hidden lg:flex items-center space-x-8 flex-1">
            {menuItemsLeft.map((item) => (
              <Link
                key={item.href}
                href={item.href}
                className="font-satoshi text-mimo-blue hover:text-mimo-brown transition-colors"
              >
                {item.label}
              </Link>
            ))}
          </div>

          {/* Logo Central */}
          <Link href="/" className="flex items-center flex-shrink-0 mx-8">
            <Image
              src="/images/MIMO Text.svg"
              alt="Mimo"
              width={140}
              height={40}
              className="h-10 w-auto"
              priority
            />
          </Link>

          {/* Menu Direita + CTA */}
          <div className="hidden lg:flex items-center space-x-8 flex-1 justify-end">
            {menuItemsRight.map((item) => (
              <div key={item.href} className="relative inline-flex items-center group">
                {item.comingSoon ? (
                  <>
                    <span
                      className="font-satoshi text-mimo-blue/50 relative inline-flex items-center gap-2 transition-all duration-150 group-hover:text-mimo-blue/70"
                    >
                      {item.label}
                      <svg
                        className="w-4 h-4 text-mimo-gold transition-all duration-150 group-hover:scale-110"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          strokeLinecap="round"
                          strokeLinejoin="round"
                          strokeWidth={2}
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                      </svg>
                    </span>
                    <div className="absolute top-1/2 -translate-y-1/2 right-full mr-3 px-3 py-1.5 bg-mimo-brown text-white text-xs font-satoshi rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-150 pointer-events-none whitespace-nowrap z-50">
                      👀 Em breve
                      <div className="absolute top-1/2 left-full -translate-y-1/2 w-0 h-0 border-t-4 border-b-4 border-l-4 border-transparent border-l-mimo-brown"></div>
                    </div>
                  </>
                ) : (
                  <Link
                    href={item.href}
                    className="font-satoshi text-mimo-blue hover:text-mimo-brown transition-colors"
                  >
                    {item.label}
                  </Link>
                )}
              </div>
            ))}
            <Button
              variant="primary"
              href={getWhatsAppBookingUrl()}
              external
              className="text-xl px-10 py-4"
            >
              Agendar
            </Button>
          </div>

          {/* Mobile: Logo + Menu Button */}
          <div className="lg:hidden flex items-center justify-between w-full">
            <Link href="/" className="flex items-center">
              <Image
                src="/images/MIMO Text.svg"
                alt="Mimo"
                width={120}
                height={32}
                className="h-8 w-auto"
                priority
              />
            </Link>
            <div className="flex items-center space-x-4">
              <Button
                variant="primary"
                href={getWhatsAppBookingUrl()}
                external
                className="text-lg px-8 py-3"
              >
                Agendar
              </Button>

              <button
                className="p-2 text-mimo-brown"
                aria-label="Menu"
              >
                <svg
                  className="w-6 h-6"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M4 6h16M4 12h16M4 18h16"
                  />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </nav>
    </header>
  )
}

