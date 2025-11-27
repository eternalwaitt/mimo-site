import { ImageWithFallback } from './image-with-fallback'
import { CelebrityCardLink } from './celebrity-card-link'
import { cn } from '@/lib/utils'
import { getCelebrityImage } from '@/lib/get-celebrity-image'
import type { Celebrity } from '@/lib/types'

type CelebrityCardProps = {
  celebrity: Celebrity
  className?: string
}

/**
 * celebrity card component - para #MomentoMIMO.
 * 
 * - estilo editorial (não testemunhal genérico)
 * - thumbnail com link direto para Instagram Reel ou perfil
 * - overlay com play button quando tem reel
 * - footer customizado com informações (nome, serviço, quote, link Instagram)
 * - server component para melhor performance (zero JS)
 * 
 * performance:
 * - sem iframes (remove ~50-100 KiB de JS)
 * - sem IntersectionObserver ou hooks
 * - imagens otimizadas com next/image
 * - link direto abre no app Instagram (melhor UX mobile)
 * 
 * @param {CelebrityCardProps} props - props do componente
 * @param {Celebrity} props.celebrity - dados da celebridade/influencer
 * @param {string} [props.className] - classes CSS adicionais
 * @returns {Promise<JSX.Element>} card de celebridade com imagem e informações
 * 
 * @example
 * ```tsx
 * <CelebrityCard 
 *   celebrity={{
 *     id: 'bruna-huli',
 *     name: 'Bruna Huli',
 *     service: 'Coloração',
 *     image: '/images/depo/bruna.webp',
 *     imageAlt: 'Bruna Huli - cliente Mimo',
 *     reelUrl: 'https://instagram.com/reel/...',
 *     instagram: 'https://instagram.com/brunahuli'
 *   }}
 * />
 * ```
 */
export async function CelebrityCard({ celebrity, className }: CelebrityCardProps) {
  // Usar reelUrl se disponível, senão instagram, senão #
  const linkUrl = celebrity.reelUrl || celebrity.instagram || '#'
  const hasReel = !!celebrity.reelUrl

  // Obter imagem usando estratégia de fallback em camadas
  const imageSrc = await getCelebrityImage(celebrity)

  return (
    <CelebrityCardLink
      href={linkUrl}
      className={cn(
        'group block overflow-hidden rounded-xl bg-white shadow-md transition-all duration-400 hover:shadow-lg',
        className
      )}
    >
      <div className="relative aspect-[9/16] overflow-hidden bg-black">
        <ImageWithFallback
          src={imageSrc}
          alt={celebrity.imageAlt}
          fill
          sizes="(max-width: 768px) 50vw, 25vw"
          className="object-cover transition-transform duration-500 group-hover:scale-110"
        />
        
        {/* Play overlay apenas se tiver reel */}
        {hasReel && (
          <div className="pointer-events-none absolute inset-0 flex items-center justify-center">
            <div className="rounded-full bg-black/60 backdrop-blur-sm p-4 transition-transform duration-300 group-hover:scale-110 group-hover:bg-black/70">
              <svg 
                className="w-12 h-12 text-white ml-1" 
                fill="currentColor" 
                viewBox="0 0 24 24"
                aria-hidden="true"
              >
                <path d="M8 5v14l11-7z"/>
              </svg>
            </div>
          </div>
        )}

        {/* Footer customizado */}
        <div className="absolute bottom-0 left-0 right-0 p-5 text-white bg-gradient-to-t from-black via-black/90 to-transparent backdrop-blur-sm">
          <h3 className="font-bueno text-2xl font-bold mb-2 drop-shadow-lg">
            {celebrity.name}
          </h3>
          <p className="font-satoshi text-base opacity-95 mb-3 drop-shadow-md">
            {celebrity.service}
          </p>
          {celebrity.instagram && (
            <div className="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm px-3 py-1.5 rounded-lg border border-white/20">
              <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.98-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.98-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
              </svg>
              <span className="font-satoshi text-sm font-semibold">
                {hasReel ? 'Ver Reel' : 'Instagram'}
              </span>
            </div>
          )}
          {celebrity.quote && (
            <p className="font-satoshi text-sm italic opacity-90 line-clamp-2 mt-3 drop-shadow-md">
              &quot;{celebrity.quote}&quot;
            </p>
          )}
        </div>
      </div>
    </CelebrityCardLink>
  )
}
