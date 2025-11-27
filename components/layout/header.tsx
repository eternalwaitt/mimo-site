import Link from 'next/link'
import Image from 'next/image'
import dynamic from 'next/dynamic'
import { getWhatsAppBookingUrl } from '@/lib/constants/index'

// Dynamic import do HeaderClient - code-split para reduzir bundle inicial
// Reduz bundle inicial em ~15-20 KB (mesmo com SSR, o JS é code-split)
const HeaderClient = dynamic(
  () => import('./header-client').then(mod => ({ default: mod.HeaderClient })),
  { ssr: true } // SSR necessário para evitar layout shift, mas JS é code-split
)

type MenuItem = {
  href: string
  label: string
  comingSoon?: boolean
}

const menuItemsLeft: Array<MenuItem> = [
  { href: '/', label: 'Home' },
  { href: '/servicos', label: 'Serviços' },
  { href: '/galeria', label: 'Galeria' },
  { href: '/sobre', label: 'Sobre' },
  { href: '/trabalhe-aqui', label: 'Trabalhe Aqui' },
]

const menuItemsRight: Array<MenuItem> = [
  { href: '/blog', label: 'Blog', comingSoon: true },
  { href: '/hub', label: 'Mimo HUB', comingSoon: true },
]

/**
 * header fixo minimalista - server component.
 * 
 * - estrutura estática renderizada no servidor (logo, nav links, layout)
 * - interatividade (scroll, menu mobile, analytics) delegada para HeaderClient
 * 
 * otimizado: reduz JS inicial ao mínimo necessário.
 */
export function Header() {
  const whatsappUrl = getWhatsAppBookingUrl()

  return (
    <>
      <header className="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-transparent">
        {/* Skip to main content link for keyboard navigation */}
        <a
          href="#main-content"
          className="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[100] focus:px-4 focus:py-2 focus:bg-mimo-brown focus:text-white focus:rounded-lg"
        >
          Pular para conteúdo principal
        </a>
        <nav className="container mx-auto px-4 sm:px-6 lg:px-8" aria-label="Navegação principal">
          <div className="flex items-center justify-between h-20">
            {/* Menu Esquerda - Desktop */}
            <div className="hidden lg:flex items-center space-x-8 flex-1">
              {menuItemsLeft.map((item) => (
                <Link
                  key={item.href}
                  href={item.href}
                  className="font-satoshi text-mimo-blue hover:text-mimo-brown transition-colors"
                  data-nav-label={item.label}
                  data-nav-href={item.href}
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
                fetchPriority="high"
              />
            </Link>

            {/* Menu Direita + CTA - Desktop */}
            <div className="hidden lg:flex items-center space-x-8 flex-1 justify-end">
              {menuItemsRight.map((item) => (
                <div key={item.href} className="relative inline-flex items-center group">
                  {item.comingSoon ? (
                    <>
                      <span className="font-satoshi text-mimo-blue/70 relative inline-flex items-center gap-2 transition-all duration-150 group-hover:text-mimo-blue">
                        {item.label}
                        <svg
                          className="w-4 h-4 text-mimo-brown transition-all duration-150 group-hover:scale-110"
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
              <div id="header-cta-desktop" />
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
                  fetchPriority="high"
                />
              </Link>
              <div id="header-mobile-controls" />
            </div>
          </div>
        </nav>
      </header>
      {/* Client island para interatividade */}
      <HeaderClient
        menuItemsLeft={menuItemsLeft}
        menuItemsRight={menuItemsRight}
        whatsappUrl={whatsappUrl}
      />
    </>
  )
}
