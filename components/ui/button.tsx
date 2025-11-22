import Link from 'next/link'
import type { ReactNode } from 'react'

/**
 * variantes de botão disponíveis.
 */
type ButtonVariant = 'primary' | 'secondary' | 'ghost' | 'whatsapp'

/**
 * props do componente button.
 */
type ButtonProps = {
  /** variante visual do botão */
  variant?: ButtonVariant
  /** url de destino (se fornecido, renderiza como link) */
  href?: string
  /** callback executado ao clicar (se fornecido sem href, renderiza como button) */
  onClick?: () => void
  /** conteúdo do botão */
  children: ReactNode
  /** classes CSS adicionais */
  className?: string
  /** se true e href fornecido, abre link em nova aba */
  external?: boolean
}

/**
 * button component com variants seguindo brand book.
 * 
 * primary: marrom (#493125) - CTAs principais
 * secondary: outline - CTAs secundários
 * ghost: transparente - links discretos
 * whatsapp: verde sutil ou marrom com ícone - links WhatsApp
 */
export function Button({
  variant = 'primary',
  href,
  onClick,
  children,
  className,
  external = false,
}: ButtonProps) {
  const baseStyles = 'inline-flex items-center justify-center px-8 py-4 rounded-lg font-bueno font-bold text-lg transition-all duration-300 focus-visible:outline-2 focus-visible:outline-mimo-gold focus-visible:outline-offset-2'

  const variants = {
    primary: 'bg-mimo-brown text-white hover:bg-[#3a2519] active:scale-95',
    secondary: 'border-2 border-mimo-brown text-mimo-brown hover:bg-mimo-brown hover:text-white active:scale-95',
    ghost: 'text-mimo-brown hover:text-[#3a2519] hover:underline',
    whatsapp: 'bg-mimo-brown text-white hover:bg-[#3a2519] active:scale-95',
  }

  // Se className customizado for fornecido, usar apenas baseStyles + className
  // Caso contrário, usar variant padrão
  // Isso garante que classes customizadas sempre sobrescrevam o variant
  const styles = className 
    ? [baseStyles, className].filter(Boolean).join(' ')
    : [baseStyles, variants[variant]].filter(Boolean).join(' ')

  if (href) {
    if (external) {
      return (
        <a
          href={href}
          target="_blank"
          rel="noopener noreferrer"
          className={styles}
        >
          {children}
        </a>
      )
    }

    return (
      <Link href={href} className={styles}>
        {children}
      </Link>
    )
  }

  return (
    <button onClick={onClick} className={styles}>
      {children}
    </button>
  )
}

