/**
 * busca thumbnail de Instagram Reel via oEmbed API.
 * 
 * usa cache do Next.js para evitar múltiplas requisições.
 * 
 * @param reelUrl - URL completa do reel
 * @returns URL do thumbnail ou null
 */
export async function getReelThumbnail(reelUrl: string): Promise<string | null> {
  try {
    const oembedUrl = `https://api.instagram.com/oembed?url=${encodeURIComponent(reelUrl)}`
    
    const response = await fetch(oembedUrl, {
      next: { revalidate: 86400 } // Cache por 24h
    })
    
    if (!response.ok) {
      console.warn(`Instagram oEmbed falhou para ${reelUrl}: ${response.status}`)
      return null
    }
    
    const data = await response.json()
    return data.thumbnail_url || null
  } catch (error) {
    console.error('Erro ao buscar thumbnail do reel:', error)
    return null
  }
}

