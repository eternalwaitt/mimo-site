'use client'

import { cn } from '@/lib/utils'

type CelebrityCardLinkProps = {
  href: string
  className?: string
  children: React.ReactNode
}

/**
 * client component para prevenir auto-embed do Instagram.
 * 
 * o Instagram detecta links para reels e automaticamente cria iframes.
 * este componente intercepta o clique e abre o link manualmente,
 * evitando que o Instagram detecte o link.
 */
export function CelebrityCardLink({ href, className, children }: CelebrityCardLinkProps) {
  const handleClick = (e: React.MouseEvent<HTMLDivElement>) => {
    e.preventDefault()
    if (typeof window !== 'undefined') {
      window.open(href, '_blank', 'noopener,noreferrer')
    }
  }

  return (
    <div
      onClick={handleClick}
      className={cn('cursor-pointer', className)}
      role="link"
      tabIndex={0}
      onKeyDown={(e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault()
          handleClick(e as unknown as React.MouseEvent<HTMLDivElement>)
        }
      }}
    >
      {children}
    </div>
  )
}

