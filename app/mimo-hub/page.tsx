'use client'

import { useState, useCallback } from 'react'
import { Header } from '@/components/layout/header'
import { Footer } from '@/components/layout/footer'
import { ProductGrid } from '@/components/sections/product-grid'
import { ProductFilters } from '@/components/sections/product-filters'
import { PRODUCTS } from '@/lib/constants/index'
import type { Product } from '@/lib/types'

/**
 * página do mimo hub - produtos recomendados.
 * 
 * - hero com título e descrição do hub
 * - filtros por categoria e quem recomendou
 * - grid de produtos recomendados (filtrado)
 */
export default function MimoHubPage() {
  const [filteredProducts, setFilteredProducts] = useState<Array<Product>>(PRODUCTS)
  
  const handleFilterChange = useCallback((filtered: Array<Product>) => {
    setFilteredProducts(filtered)
  }, [])

  return (
    <>
      <Header />
      <main id="main-content" className="pt-20">
        {/* Hero Compacto */}
        <section className="py-12 md:py-16 bg-mimo-neutral-light border-b border-mimo-neutral-medium">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8">
            <div className="text-center">
              <h1 className="font-bueno text-4xl md:text-5xl font-bold text-mimo-brown mb-2">
                Mimo Hub
              </h1>
              <p className="font-satoshi text-lg text-mimo-blue max-w-2xl mx-auto">
                Produtos cuidadosamente selecionados pela Mimo e nossas influencers
              </p>
            </div>
          </div>
        </section>

        {/* Filtros acima dos produtos */}
        <section className="py-6 bg-white border-b border-mimo-neutral-medium">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8">
            <div className="flex justify-start">
              <ProductFilters products={PRODUCTS} onFilterChange={handleFilterChange} />
            </div>
          </div>
        </section>

        {/* Grid de Produtos */}
        <ProductGrid products={filteredProducts} />
      </main>
      <Footer />
    </>
  )
}

