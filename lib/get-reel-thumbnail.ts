/**
 * busca thumbnail de Instagram Reel via oEmbed API com retry e fallbacks.
 * 
 * estratégia de múltiplas camadas:
 * 1. verifica cache local primeiro (mais confiável)
 * 2. tenta oEmbed API oficial com retry e backoff exponencial
 * 3. tenta variações de URL do oEmbed
 * 
 * usa cache do Next.js para evitar múltiplas requisições.
 * 
 * @param reelUrl - URL completa do reel
 * @returns URL do thumbnail ou null
 */
import { getCachedThumbnailPath } from './reel-thumbnail-cache'

const MAX_RETRIES = 3
const INITIAL_DELAY = 1000 // 1 segundo

/**
 * delay com backoff exponencial.
 */
function delay(ms: number): Promise<void> {
  return new Promise(resolve => setTimeout(resolve, ms))
}

/**
 * tenta buscar thumbnail via oEmbed com retry.
 */
async function tryOEmbed(
  reelUrl: string,
  urlVariation: (url: string) => string,
  retries = MAX_RETRIES
): Promise<string | null> {
  for (let attempt = 0; attempt < retries; attempt++) {
    try {
      const oembedUrl = urlVariation(reelUrl)
      
      const response = await fetch(oembedUrl, {
        next: { revalidate: 86400 }, // Cache por 24h
        headers: {
          'Accept': 'application/json',
          'User-Agent': 'Mozilla/5.0 (compatible; MimoBot/1.0)',
        },
      })
      
      if (response.ok) {
        const contentType = response.headers.get('content-type')
        // Verificar se a resposta é JSON antes de fazer parse
        if (contentType && contentType.includes('application/json')) {
          const data = await response.json()
          if (data.thumbnail_url && typeof data.thumbnail_url === 'string') {
            // Validar que é uma URL válida
            if (data.thumbnail_url.startsWith('http://') || data.thumbnail_url.startsWith('https://')) {
              return data.thumbnail_url
            }
          }
        }
      } else if (response.status === 429) {
        // Rate limit - esperar mais antes de retry
        const waitTime = INITIAL_DELAY * Math.pow(2, attempt + 1)
        await delay(waitTime)
        continue
      } else if (response.status !== 500 && response.status !== 503) {
        // Erros não recuperáveis (exceto 500/503) - não retry
        break
      }
      
      // Se chegou aqui, foi erro 500/503 ou resposta inválida - fazer retry
      if (attempt < retries - 1) {
        const waitTime = INITIAL_DELAY * Math.pow(2, attempt)
        await delay(waitTime)
      }
    } catch (error) {
      // Erro de rede - fazer retry se ainda tiver tentativas
      if (attempt < retries - 1) {
        const waitTime = INITIAL_DELAY * Math.pow(2, attempt)
        await delay(waitTime)
      }
    }
  }
  
  return null
}

export async function getReelThumbnail(reelUrl: string): Promise<string | null> {
  // Camada 1: Verificar cache local primeiro (mais confiável)
  const cachedPath = getCachedThumbnailPath(reelUrl)
  if (cachedPath) {
    return cachedPath
  }

  // Camada 2: Tentar oEmbed API padrão com retry
  const thumbnail1 = await tryOEmbed(
    reelUrl,
    (url) => `https://api.instagram.com/oembed?url=${encodeURIComponent(url)}`
  )
  if (thumbnail1) {
    return thumbnail1
  }

  // Camada 3: Tentar oEmbed com formato JSON explícito
  const thumbnail2 = await tryOEmbed(
    reelUrl,
    (url) => `https://api.instagram.com/oembed?url=${encodeURIComponent(url)}&format=json`
  )
  if (thumbnail2) {
    return thumbnail2
  }

  // Camada 4: Tentar com omitscript (às vezes ajuda)
  const thumbnail3 = await tryOEmbed(
    reelUrl,
    (url) => `https://api.instagram.com/oembed?url=${encodeURIComponent(url)}&omitscript=true`
  )
  if (thumbnail3) {
    return thumbnail3
  }

  // Se todos os métodos falharem, retornar null
  // O CelebrityCard vai usar o fallback (imagem estática ou placeholder)
  return null
}

