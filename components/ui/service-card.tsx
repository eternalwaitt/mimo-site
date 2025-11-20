import Link from 'next/link'
import { ImageWithFallback } from './image-with-fallback'
import { Button } from './button'
import { cn } from '@/lib/utils'
import type { Service } from '@/lib/types'

/**
 * props do componente service-card.
 */
type ServiceCardProps = {
  /** dados do serviço a ser exibido */
  service: Service
  /** classes CSS adicionais */
  className?: string
}

/**
 * service card component - cards grandes com foto dominante.
 * 
 * - foto com overlay gradiente no hover
 * - revela descrição completa e botão "Ver detalhes" no hover
 * - link para subpáginas de cada serviço
 * - animações suaves com scale e opacity
 */
export function ServiceCard({ service, className }: ServiceCardProps) {
  return (
    <Link
      href={`/servicos/${service.slug}`}
      className={cn(
        'group relative block overflow-hidden rounded-2xl bg-white shadow-lg transition-all duration-400 hover:shadow-xl hover:scale-[1.02]',
        className
      )}
    >
      <div className="relative aspect-[4/3] overflow-hidden bg-mimo-neutral-light">
        <ImageWithFallback
          src={service.image}
          alt={service.imageAlt}
          fill
          sizes="(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 33vw"
          className="object-cover transition-transform duration-500 group-hover:scale-110"
        />
        <div className="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 transition-opacity duration-400 group-hover:opacity-100" />
      </div>

      <div className="p-6">
        <h3 className="font-bueno text-2xl font-bold text-mimo-brown mb-2">
          {service.title}
        </h3>
        <p className="font-satoshi text-mimo-blue text-sm mb-3 line-clamp-2">
          {service.shortDescription}
        </p>
        <p className="font-satoshi text-mimo-brown font-medium text-lg mb-4">
          {service.price}
        </p>

        <div className="opacity-0 max-h-0 overflow-hidden transition-all duration-400 group-hover:opacity-100 group-hover:max-h-32">
          <p className="font-satoshi text-mimo-blue text-sm mb-3">
            {service.description}
          </p>
          <Button variant="secondary" className="w-full text-lg py-4">
            Ver detalhes
          </Button>
        </div>
      </div>
    </Link>
  )
}

