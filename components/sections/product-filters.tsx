'use client'

import { useState, useMemo, useEffect, useRef, useCallback } from 'react'
import type { Product } from '@/lib/types'

/**
 * props do componente product-filters.
 */
type ProductFiltersProps = {
  /** produtos disponíveis para filtrar */
  products: Array<Product>
  /** callback quando os filtros mudam */
  onFilterChange: (filteredProducts: Array<Product>) => void
}

/**
 * componente de filtros para produtos do mimo hub.
 * 
 * - botão único que abre dropdown com múltiplos grupos de filtros
 * - categorias (cabelo, unha, etc)
 * - quem indicou (Mimo, Influencer X, etc)
 * - tipo de produto
 * - múltipla seleção
 * - design elegante e compacto
 * 
 * @param {ProductFiltersProps} props - props do componente
 * @returns {JSX.Element} botão de filtro com dropdown
 */
export function ProductFilters({ products, onFilterChange }: ProductFiltersProps) {
  // Extrair opções únicas
  const categories = useMemo(() => {
    const unique = Array.from(new Set(products.map((p) => p.category)))
    return unique.sort()
  }, [products])

  const recommenders = useMemo(() => {
    const unique = Array.from(new Set(products.map((p) => p.recommendedBy)))
    return unique.sort()
  }, [products])

  const productTypes = useMemo(() => {
    // Extrair tipos únicos das tags ou criar baseado em lógica
    const allTags = products.flatMap((p) => p.tags || [])
    const unique = Array.from(new Set(allTags))
    return unique.sort()
  }, [products])

  const [selectedCategories, setSelectedCategories] = useState<Set<string>>(new Set())
  const [selectedRecommenders, setSelectedRecommenders] = useState<Set<string>>(new Set())
  const [selectedTypes, setSelectedTypes] = useState<Set<string>>(new Set())
  const [isOpen, setIsOpen] = useState(false)
  const dropdownRef = useRef<HTMLDivElement>(null)
  const isMounted = useRef(false)

  // Função para aplicar filtros baseado no estado atual
  const applyFilters = useCallback(
    (
      currentCategories: Set<string>,
      currentRecommenders: Set<string>,
      currentTypes: Set<string>
    ) => {
      const filtered = products.filter((product) => {
        const matchesCategory =
          currentCategories.size === 0 || currentCategories.has(product.category)
        const matchesRecommender =
          currentRecommenders.size === 0 || currentRecommenders.has(product.recommendedBy)
        const matchesType =
          currentTypes.size === 0 ||
          (product.tags && product.tags.some((tag) => currentTypes.has(tag)))

        return matchesCategory && matchesRecommender && matchesType
      })
      onFilterChange(filtered)
    },
    [products, onFilterChange]
  )

  // Aplicar filtros quando o estado mudar (mas não na primeira renderização)
  useEffect(() => {
    if (typeof window === 'undefined') return
    
    if (!isMounted.current) {
      isMounted.current = true
      return
    }
    applyFilters(selectedCategories, selectedRecommenders, selectedTypes)
  }, [selectedCategories, selectedRecommenders, selectedTypes, applyFilters])

  // Fechar dropdown ao clicar fora
  useEffect(() => {
    if (typeof window === 'undefined') return

    const handleClickOutside = (event: MouseEvent) => {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target as Node)) {
        setIsOpen(false)
      }
    }

    if (isOpen) {
      document.addEventListener('mousedown', handleClickOutside)
    }

    return () => {
      document.removeEventListener('mousedown', handleClickOutside)
    }
  }, [isOpen])

  const toggleCategory = (category: string) => {
    setSelectedCategories((prev) => {
      const newSet = new Set(prev)
      if (newSet.has(category)) {
        newSet.delete(category)
      } else {
        newSet.add(category)
      }
      return newSet
    })
  }

  const toggleRecommender = (recommender: string) => {
    setSelectedRecommenders((prev) => {
      const newSet = new Set(prev)
      if (newSet.has(recommender)) {
        newSet.delete(recommender)
      } else {
        newSet.add(recommender)
      }
      return newSet
    })
  }

  const toggleType = (type: string) => {
    setSelectedTypes((prev) => {
      const newSet = new Set(prev)
      if (newSet.has(type)) {
        newSet.delete(type)
      } else {
        newSet.add(type)
      }
      return newSet
    })
  }

  const clearFilters = () => {
    setSelectedCategories(new Set())
    setSelectedRecommenders(new Set())
    setSelectedTypes(new Set())
  }

  const hasActiveFilters =
    selectedCategories.size > 0 || selectedRecommenders.size > 0 || selectedTypes.size > 0

  // Contar total de filtros ativos
  const activeFiltersCount =
    selectedCategories.size + selectedRecommenders.size + selectedTypes.size

  return (
    <div className="relative inline-block" ref={dropdownRef}>
      <button
        onClick={() => setIsOpen(!isOpen)}
        className={`inline-flex items-center gap-2 px-4 py-2 rounded-full font-satoshi text-sm transition-all duration-200 min-w-[180px] justify-between ${
          hasActiveFilters
            ? 'bg-mimo-brown text-white shadow-md'
            : 'bg-white text-mimo-brown border border-mimo-neutral-medium hover:bg-mimo-neutral-light'
        }`}
      >
        <span>
          {hasActiveFilters ? `${activeFiltersCount} filtro${activeFiltersCount > 1 ? 's' : ''}` : 'Filtros'}
        </span>
        <svg
          className={`w-4 h-4 transition-transform duration-200 flex-shrink-0 ${isOpen ? 'rotate-180' : ''}`}
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
        </svg>
      </button>

      {/* Dropdown */}
      {isOpen && (
        <div className="absolute top-full left-0 mt-2 w-72 bg-white rounded-lg shadow-lg border border-mimo-neutral-medium z-50 py-3 max-h-[80vh] overflow-y-auto">
          {/* Categorias */}
          <div className="px-4 mb-4">
            <h3 className="font-bueno text-sm font-bold text-mimo-brown mb-2">Categoria</h3>
            <div className="space-y-1">
              {categories.map((category) => {
                const isSelected = selectedCategories.has(category)
                return (
                  <button
                    key={category}
                    onClick={() => toggleCategory(category)}
                    className={`w-full text-left px-3 py-1.5 rounded font-satoshi text-sm transition-colors flex items-center gap-2 ${
                      isSelected
                        ? 'bg-mimo-brown/10 text-mimo-brown font-medium'
                        : 'text-mimo-blue hover:bg-mimo-neutral-light'
                    }`}
                  >
                    {isSelected && (
                      <svg className="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          fillRule="evenodd"
                          d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                          clipRule="evenodd"
                        />
                      </svg>
                    )}
                    <span className={isSelected ? '' : 'ml-6'}>
                      {category.charAt(0).toUpperCase() + category.slice(1)}
                    </span>
                  </button>
                )
              })}
            </div>
          </div>

          {/* Quem Indicou */}
          <div className="px-4 mb-4 border-t border-mimo-neutral-medium pt-4">
            <h3 className="font-bueno text-sm font-bold text-mimo-brown mb-2">Quem Indicou</h3>
            <div className="space-y-1">
              {recommenders.map((recommender) => {
                const isSelected = selectedRecommenders.has(recommender)
                return (
                  <button
                    key={recommender}
                    onClick={() => toggleRecommender(recommender)}
                    className={`w-full text-left px-3 py-1.5 rounded font-satoshi text-sm transition-colors flex items-center gap-2 ${
                      isSelected
                        ? 'bg-mimo-blue/10 text-mimo-blue font-medium'
                        : 'text-mimo-blue hover:bg-mimo-neutral-light'
                    }`}
                  >
                    {isSelected && (
                      <svg className="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          fillRule="evenodd"
                          d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                          clipRule="evenodd"
                        />
                      </svg>
                    )}
                    <span className={isSelected ? '' : 'ml-6'}>{recommender}</span>
                  </button>
                )
              })}
            </div>
          </div>

          {/* Tipo de Produto */}
          {productTypes.length > 0 && (
            <div className="px-4 mb-4 border-t border-mimo-neutral-medium pt-4">
              <h3 className="font-bueno text-sm font-bold text-mimo-brown mb-2">Tipo de Produto</h3>
              <div className="space-y-1">
                {productTypes.map((type) => {
                  const isSelected = selectedTypes.has(type)
                  return (
                    <button
                      key={type}
                      onClick={() => toggleType(type)}
                      className={`w-full text-left px-3 py-1.5 rounded font-satoshi text-sm transition-colors flex items-center gap-2 ${
                        isSelected
                          ? 'bg-mimo-gold/20 text-mimo-brown font-medium'
                          : 'text-mimo-blue hover:bg-mimo-neutral-light'
                      }`}
                    >
                      {isSelected && (
                        <svg className="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                          <path
                            fillRule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clipRule="evenodd"
                          />
                        </svg>
                      )}
                      <span className={isSelected ? '' : 'ml-6'}>
                        {type.charAt(0).toUpperCase() + type.slice(1)}
                      </span>
                    </button>
                  )
                })}
              </div>
            </div>
          )}

          {/* Botão Limpar */}
          {hasActiveFilters && (
            <div className="border-t border-mimo-neutral-medium pt-3 px-4">
              <button
                onClick={clearFilters}
                className="w-full text-center px-4 py-2 rounded font-satoshi text-sm text-mimo-brown hover:text-mimo-blue hover:bg-mimo-neutral-light transition-colors"
              >
                Limpar filtros
              </button>
            </div>
          )}
        </div>
      )}
    </div>
  )
}
