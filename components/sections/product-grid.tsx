import { ProductCard } from '@/components/ui/product-card'
import { ANIMATION_DELAYS } from '@/lib/ui-constants'
import type { Product } from '@/lib/types'

/**
 * props do componente product-grid.
 */
type ProductGridProps = {
  /** produtos a serem exibidos */
  products: Array<Product>
}

/**
 * grid de produtos - cards elegantes para produtos recomendados.
 * 
 * - nome BUENO, marca e preço (se disponível)
 * - link externo para affiliateUrl
 * - animações de entrada escalonadas com CSS (não framer-motion)
 * 
 * @param {ProductGridProps} props - props do componente
 * @param {Array<Product>} props.products - produtos a serem exibidos
 * @returns {JSX.Element} seção com grid de produtos
 * 
 * @example
 * ```tsx
 * // Usado na página do Mimo Hub
 * <ProductGrid products={filteredProducts} />
 * ```
 */
export function ProductGrid({ products }: ProductGridProps) {
  if (products.length === 0) {
    return (
      <section className="py-20 md:py-32 bg-white">
        <div className="container mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center">
            <p className="font-satoshi text-lg text-mimo-blue">
              Nenhum produto encontrado com os filtros selecionados.
            </p>
          </div>
        </div>
      </section>
    )
  }

  return (
    <section className="py-12 md:py-16 bg-white" style={{ contentVisibility: 'auto' }}>
      <div className="container mx-auto px-4 sm:px-6 lg:px-8">
        {/* Grid de Produtos */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
          {products.map((product, index) => (
            <div
              key={product.id}
              className="animate-fade-in-up"
              style={{ 
                animationDelay: `${index * ANIMATION_DELAYS.stagger}s`,
                animationFillMode: 'both'
              }}
            >
              <ProductCard product={product} />
            </div>
          ))}
        </div>
      </div>
    </section>
  )
}

