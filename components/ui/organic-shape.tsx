import { cn } from '@/lib/utils'

type OrganicShapeProps = {
  variant?: 'blob' | 'circle' | 'ellipse'
  className?: string
  children?: React.ReactNode
}

/**
 * organic shape component - formas orgânicas decorativas.
 * 
 * elemento-chave do rebrand: círculos, elipses, blobs irregulares.
 * usado como backgrounds de seções, frames para fotos, elementos decorativos.
 */
export function OrganicShape({
  variant = 'blob',
  className,
  children,
}: OrganicShapeProps) {
  const variants = {
    blob: 'organic-blob',
    circle: 'organic-circle',
    ellipse: 'organic-ellipse',
  }

  return (
    <div className={cn(variants[variant], className)}>
      {children}
    </div>
  )
}

