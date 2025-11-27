/**
 * utilitários para trabalhar com Instagram Reels.
 * 
 * usa Instagram oEmbed API para obter thumbnails automaticamente.
 */

/**
 * extrai o shortcode de uma URL do Instagram Reel.
 * 
 * @param url - URL do reel (ex: https://www.instagram.com/reel/DBACXKPOvd0/)
 * @returns shortcode do reel ou null
 */
export function extractReelShortcode(url: string): string | null {
  const match = url.match(/\/reel\/([^\/\?]+)/)
  return match ? match[1] : null
}

/**
 * gera URL do thumbnail do Instagram Reel usando oEmbed API.
 * 
 * @param reelUrl - URL completa do reel
 * @returns URL do thumbnail ou null se não conseguir extrair
 */
export function getReelThumbnailUrl(reelUrl: string): string | null {
  const shortcode = extractReelShortcode(reelUrl)
  if (!shortcode) return null
  
  // Instagram oEmbed API retorna thumbnail_url
  // Formato: https://api.instagram.com/oembed?url={reel_url}
  return `https://api.instagram.com/oembed?url=${encodeURIComponent(reelUrl)}`
}

/**
 * busca thumbnail do reel via oEmbed API.
 * 
 * @param reelUrl - URL completa do reel
 * @returns URL do thumbnail ou null
 */
export async function fetchReelThumbnail(reelUrl: string): Promise<string | null> {
  try {
    const oembedUrl = getReelThumbnailUrl(reelUrl)
    if (!oembedUrl) return null

    const response = await fetch(oembedUrl, {
      next: { revalidate: 86400 } // Cache por 24h
    })
    
    if (!response.ok) return null
    
    const data = await response.json()
    return data.thumbnail_url || null
  } catch (error) {
    console.error('Erro ao buscar thumbnail do reel:', error)
    return null
  }
}

