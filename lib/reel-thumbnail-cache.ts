/**
 * gerenciamento de cache local para thumbnails de Instagram Reels.
 * 
 * thumbnails são salvas em /public/images/reels/ para acesso direto.
 * isso garante que o site funcione mesmo se a API do Instagram falhar.
 */

import { existsSync } from 'fs'
import { join } from 'path'

const REELS_DIR = join(process.cwd(), 'public', 'images', 'reels')

/**
 * extrai o ID do reel ou post da URL do Instagram.
 * ex: https://www.instagram.com/reel/DBACXKPOvd0/ -> DBACXKPOvd0
 * ex: https://www.instagram.com/p/DElH799vrV5/ -> DElH799vrV5
 */
export function extractReelId(reelUrl: string): string | null {
  // tentar reel primeiro
  let match = reelUrl.match(/\/reel\/([A-Za-z0-9_-]+)/)
  if (match) {
    return match[1]
  }
  // depois tentar post
  match = reelUrl.match(/\/p\/([A-Za-z0-9_-]+)/)
  return match ? match[1] : null
}

/**
 * retorna o caminho público da thumbnail do reel.
 * ex: /images/reels/DBACXKPOvd0.webp
 */
export function getReelThumbnailPath(reelUrl: string, format: 'webp' | 'jpg' = 'webp'): string {
  const reelId = extractReelId(reelUrl)
  if (!reelId) {
    return '/images/placeholder.svg'
  }
  return `/images/reels/${reelId}.${format}`
}

/**
 * verifica se a thumbnail existe no cache local.
 * tenta webp primeiro, depois jpg como fallback.
 */
export function hasCachedThumbnail(reelUrl: string): boolean {
  const reelId = extractReelId(reelUrl)
  if (!reelId) {
    return false
  }

  const webpPath = join(REELS_DIR, `${reelId}.webp`)
  const jpgPath = join(REELS_DIR, `${reelId}.jpg`)

  return existsSync(webpPath) || existsSync(jpgPath)
}

/**
 * retorna o caminho da thumbnail se existir no cache.
 * retorna null se não existir.
 */
export function getCachedThumbnailPath(reelUrl: string): string | null {
  if (!hasCachedThumbnail(reelUrl)) {
    return null
  }

  const reelId = extractReelId(reelUrl)
  if (!reelId) {
    return null
  }

  // preferir webp, fallback para jpg
  const webpPath = join(REELS_DIR, `${reelId}.webp`)
  if (existsSync(webpPath)) {
    return getReelThumbnailPath(reelUrl, 'webp')
  }

  const jpgPath = join(REELS_DIR, `${reelId}.jpg`)
  if (existsSync(jpgPath)) {
    return getReelThumbnailPath(reelUrl, 'jpg')
  }

  return null
}

