import { ImageWithFallback } from './image-with-fallback'
import { cn } from '@/lib/utils'
import type { Product } from '@/lib/types'

/**
 * props do componente product-card.
 */
type ProductCardProps = {
  /** dados do produto a ser exibido */
  product: Product
  /** classes CSS adicionais */
  className?: string
}

/**
 * product card component - cards elegantes para produtos recomendados.
 * 
 * - foto com overlay gradiente no hover
 * - badge "Recomendado por" no canto superior
 * - link externo para affiliateUrl
 * - hover sutil apenas na shadow
 * - mostra marca, descrição curta e preço (se disponível)
 * 
 * @param {ProductCardProps} props - props do componente
 * @param {Product} props.product - dados do produto a ser exibido
 * @param {string} [props.className] - classes CSS adicionais
 * @returns {JSX.Element} card de produto com imagem, título, marca, descrição e link
 * 
 * @example
 * ```tsx
 * <ProductCard 
 *   product={{
 *     id: 'produto-1',
 *     slug: 'shampoo-reparador',
 *     title: 'Shampoo Reparador',
 *     shortDescription: 'Para cabelos danificados',
 *     description: 'Shampoo reparador...',
 *     image: '/images/hub/shampoo.webp',
 *     imageAlt: 'Shampoo reparador',
 *     affiliateUrl: 'https://exemplo.com/produto?ref=mimo',
 *     category: 'cabelo',
 *     recommendedBy: 'Mimo',
 *     brand: 'Marca X',
 *     price: 'R$ 45,90'
 *   }}
 * />
 * ```
 */
export function ProductCard({ product, className }: ProductCardProps) {
  return (
    <a
      href={product.affiliateUrl}
      target="_blank"
      rel="noopener noreferrer"
      aria-label={`Ver produto ${product.title} - link externo`}
      className={cn(
        'group relative block overflow-hidden rounded-2xl bg-white shadow-lg transition-all duration-400 hover:shadow-xl',
        className
      )}
    >
      {/* aspect-[4/3] - ASPECT_RATIOS.serviceCard */}
      <div className="relative aspect-[4/3] overflow-hidden bg-mimo-neutral-medium">
        {/* Placeholder visual que aparece sempre */}
        <div className="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-mimo-neutral-medium to-mimo-neutral-light z-0">
          <div className="text-center p-6">
            <div className="w-20 h-20 mx-auto mb-4 rounded-full bg-mimo-brown/20 flex items-center justify-center">
              <svg className="w-10 h-10 text-mimo-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
              </svg>
            </div>
            <p className="font-satoshi text-sm text-mimo-brown/60 uppercase tracking-wide">
              {product.brand || 'Produto'}
            </p>
            <p className="font-bueno text-lg font-bold text-mimo-brown mt-2">
              {product.title}
            </p>
          </div>
        </div>
        {/* Imagem que aparece por cima se carregar */}
        <div className="absolute inset-0 z-10">
          <ImageWithFallback
            src={product.image}
            alt={product.imageAlt}
            fill
            sizes="(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 33vw"
            className="object-cover transition-transform duration-500 group-hover:scale-110"
          />
        </div>
        <div className="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 transition-opacity duration-400 group-hover:opacity-100 z-20" />
        
        {/* Badge "Recomendado por" */}
        <div className="absolute top-3 right-3 z-10">
          <span className="inline-flex items-center px-3 py-1 rounded-full text-xs font-satoshi font-medium bg-mimo-gold text-mimo-brown">
            Recomendado por {product.recommendedBy}
          </span>
        </div>

        {/* Badge de categoria (discreto) */}
        {product.category && (
          <div className="absolute top-3 left-3 z-10">
            <span className="inline-flex items-center px-2 py-1 rounded-full text-xs font-satoshi font-medium bg-white/90 text-mimo-blue backdrop-blur-sm">
              {product.category}
            </span>
          </div>
        )}
      </div>

      <div className="p-6">
        {product.brand && (
          <p className="font-satoshi text-sm text-mimo-blue mb-1 uppercase tracking-wide">
            {product.brand}
          </p>
        )}
        <h3 className="font-bueno text-2xl font-bold text-mimo-brown mb-2">
          {product.title}
        </h3>
        {product.shortDescription && (
          <p className="font-satoshi text-mimo-blue text-sm mb-3 line-clamp-2">
            {product.shortDescription}
          </p>
        )}
        {product.price && (
          <p className="font-satoshi text-mimo-brown font-medium text-lg">
            {product.price}
          </p>
        )}
      </div>
    </a>
  )
}

