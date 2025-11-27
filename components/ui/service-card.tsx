import Link from 'next/link'
import { ImageWithFallback } from './image-with-fallback'
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
 * - link para subpáginas de cada serviço
 * - hover sutil apenas na shadow
 * 
 * @param {ServiceCardProps} props - props do componente
 * @param {Service} props.service - dados do serviço a ser exibido
 * @param {string} [props.className] - classes CSS adicionais
 * @returns {JSX.Element} card de serviço com imagem, título, preço e link
 * 
 * @example
 * ```tsx
 * <ServiceCard 
 *   service={{
 *     id: 'salao',
 *     slug: 'salao',
 *     title: 'Salão',
 *     description: 'Cortes e coloração...',
 *     shortDescription: 'Cortes personalizados',
 *     price: 'A partir de R$ 80',
 *     image: '/images/servicos/salao/categoria.webp',
 *     imageAlt: 'Salão Mimo',
 *     benefits: ['Benefício 1', 'Benefício 2']
 *   }}
 * />
 * ```
 */
export function ServiceCard({ service, className }: ServiceCardProps) {
  return (
    <Link
      href={`/servicos/${service.slug}`}
      aria-label={`Ver detalhes de ${service.title}`}
      className={cn(
        'group relative block overflow-hidden rounded-2xl bg-white shadow-lg transition-all duration-400 hover:shadow-xl',
        className
      )}
    >
      {/* aspect-[4/3] - ASPECT_RATIOS.serviceCard */}
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
        <p className="font-satoshi text-mimo-brown font-medium text-lg">
          {service.price}
        </p>
      </div>
    </Link>
  )
}

