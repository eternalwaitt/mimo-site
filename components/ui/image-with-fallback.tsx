'use client'

import Image from 'next/image'
import { useState } from 'react'
import { cn } from '@/lib/utils'

/**
 * props do componente image-with-fallback.
 */
type ImageWithFallbackProps = {
  /** url da imagem */
  src: string
  /** texto alternativo para acessibilidade */
  alt: string
  /** largura da imagem (obrigatório se fill=false) */
  width?: number
  /** altura da imagem (obrigatório se fill=false) */
  height?: number
  /** classes CSS adicionais */
  className?: string
  /** se true, imagem é carregada com prioridade (above-the-fold) */
  priority?: boolean
  /** fetchPriority para LCP optimization ('high' | 'low' | 'auto') */
  fetchPriority?: 'high' | 'low' | 'auto'
  /** se true, imagem preenche container pai (requer position relative no pai) */
  fill?: boolean
  /** atributo sizes para otimização responsiva */
  sizes?: string
  /** como a imagem deve se ajustar ao container */
  objectFit?: 'contain' | 'cover' | 'fill' | 'none' | 'scale-down'
  /** aspect ratio da imagem para prevenir CLS (ex: "16/9") */
  aspectRatio?: string
  /** qualidade da imagem (1-100, padrão 75) */
  quality?: number
}

/**
 * componente de imagem com fallback para placeholder.
 * 
 * usa next/image para otimização automática (webp/avif, lazy loading, etc).
 * se a imagem falhar ao carregar, mostra um placeholder SVG com gradiente.
 * 
 * @param {ImageWithFallbackProps} props - propriedades do componente
 * @returns {JSX.Element} componente de imagem otimizado
 */
export function ImageWithFallback({
  src,
  alt,
  width,
  height,
  className,
  priority = false,
  fetchPriority,
  fill = false,
  sizes,
  objectFit = 'cover',
  aspectRatio,
  quality,
}: ImageWithFallbackProps) {
  const [imgSrc, setImgSrc] = useState(src)
  const [hasError, setHasError] = useState(false)
  const [isLoading, setIsLoading] = useState(true)

  const handleError = () => {
    if (!hasError) {
      setHasError(true)
      setImgSrc('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjQwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iNDAwIiBoZWlnaHQ9IjQwMCIgZmlsbD0iI0U1RENEMyIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMTYiIGZpbGw9IiM0OTMxMjUiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGRvbWluYW50LWJhc2VsaW5lPSJtaWRkbGUiPkltYWdlbSBuw6NvIGRpc3BvbsOtdmVsPC90ZXh0Pjwvc3ZnPg==')
    }
  }

  // Calcular aspect ratio do placeholder se fornecido
  const placeholderStyle = aspectRatio
    ? { aspectRatio, backgroundColor: '#F4EFEB' }
    : undefined

  if (fill) {
    return (
      <div className="relative w-full h-full">
        {isLoading && !hasError && (
          <div className="absolute inset-0 bg-mimo-neutral-light animate-pulse" />
        )}
        <Image
          src={imgSrc}
          alt={alt}
          fill
          className={cn('object-cover', isLoading && !hasError && 'opacity-0', className)}
          priority={priority}
          fetchPriority={fetchPriority}
          sizes={sizes}
          quality={quality}
          onError={handleError}
          onLoad={() => setIsLoading(false)}
          style={placeholderStyle}
        />
      </div>
    )
  }

  if (!width || !height) {
    throw new Error('width and height are required when fill is not used')
  }

  return (
    <div className="relative" style={{ width, height }}>
      {isLoading && !hasError && (
        <div className="absolute inset-0 bg-mimo-neutral-light animate-pulse rounded" />
      )}
      <Image
        src={imgSrc}
        alt={alt}
        width={width}
        height={height}
        className={cn(`object-${objectFit}`, isLoading && !hasError && 'opacity-0', className)}
        priority={priority}
        fetchPriority={fetchPriority}
        sizes={sizes}
        quality={quality}
        onError={handleError}
        onLoad={() => setIsLoading(false)}
      />
    </div>
  )
}

