/**
 * helper para obter imagem de celebrity com fallback em camadas.
 * 
 * estratégia de fallback (mais confiável primeiro):
 * 1. reelThumbnail explícito (se configurado manualmente)
 * 2. cache local (/public/images/reels/)
 * 3. oEmbed API (com retry)
 * 4. image estática do celebrity
 * 5. placeholder genérico
 */

import type { Celebrity } from './types'
import { getReelThumbnail } from './get-reel-thumbnail'
import { getCachedThumbnailPath } from './reel-thumbnail-cache'

const PLACEHOLDER_IMAGE = '/images/placeholder.svg'

/**
 * valida se uma URL é válida para uso como src de imagem.
 */
function isValidImageUrl(url: string | null | undefined): url is string {
  if (!url || typeof url !== 'string') return false
  return url.startsWith('http://') || url.startsWith('https://') || url.startsWith('/')
}

/**
 * obtém a imagem de um celebrity usando estratégia de fallback em camadas.
 * 
 * estratégia de fallback (ordem de prioridade):
 * 1. reelThumbnail explícito (se configurado manualmente)
 * 2. cache local (/public/images/reels/)
 * 3. oEmbed API (com retry)
 * 4. image estática do celebrity
 * 5. placeholder genérico
 * 
 * @param {Celebrity} celebrity - dados do celebrity/influencer
 * @returns {Promise<string>} URL da imagem a ser usada (sempre retorna string válida)
 * 
 * @example
 * ```ts
 * const image = await getCelebrityImage({
 *   id: 'bruna-huli',
 *   name: 'Bruna Huli',
 *   image: '/images/depo/bruna.webp',
 *   imageAlt: 'Bruna Huli',
 *   reelUrl: 'https://instagram.com/reel/...',
 *   reelThumbnail: '/images/reels/DBACXKPOvd0.webp' // prioridade 1
 * })
 * // Retorna: '/images/reels/DBACXKPOvd0.webp'
 * ```
 */
export async function getCelebrityImage(celebrity: Celebrity): Promise<string> {
  // Fallback padrão
  let imageSrc = celebrity.image || PLACEHOLDER_IMAGE

  // Garantir que imageSrc sempre seja uma string válida
  if (!isValidImageUrl(imageSrc)) {
    imageSrc = PLACEHOLDER_IMAGE
  }

  const hasReel = !!celebrity.reelUrl

  // Camada 1: reelThumbnail explícito (configurado manualmente)
  if (celebrity.reelThumbnail && isValidImageUrl(celebrity.reelThumbnail)) {
    return celebrity.reelThumbnail
  }

  // Camada 2: cache local (mais confiável que API)
  if (hasReel && celebrity.reelUrl) {
    const cachedPath = getCachedThumbnailPath(celebrity.reelUrl)
    if (cachedPath && isValidImageUrl(cachedPath)) {
      return cachedPath
    }

    // Camada 3: oEmbed API (pode falhar, mas tentamos)
    try {
      const thumbnailUrl = await getReelThumbnail(celebrity.reelUrl)
      if (thumbnailUrl && isValidImageUrl(thumbnailUrl)) {
        return thumbnailUrl
      }
    } catch (error) {
      // Em caso de erro, manter imageSrc atual (já tem fallback)
      // Não logar em server component para evitar problemas
    }
  }

  // Camada 4: image estática do celebrity (já definida acima)
  // Camada 5: placeholder genérico (fallback final)
  return imageSrc
}

