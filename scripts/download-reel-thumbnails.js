#!/usr/bin/env node

/**
 * script para baixar e otimizar thumbnails de Instagram Reels.
 * 
 * estratégia:
 * 1. tenta baixar via oEmbed API (com retry)
 * 2. se falhar, fornece instruções para download manual
 * 3. otimiza imagens baixadas (webp, compressão)
 * 
 * uso: node scripts/download-reel-thumbnails.js [reelUrl]
 * ou: node scripts/download-reel-thumbnails.js --all (baixa todos os reels configurados)
 * 
 * requer: sharp instalado (npm install sharp)
 */

const fs = require('fs')
const path = require('path')
const https = require('https')
const http = require('http')

// Verificar se sharp está instalado
let sharp
try {
  sharp = require('sharp')
} catch (error) {
  console.warn('⚠️  sharp não está instalado. Imagens não serão otimizadas.')
  console.warn('   Execute: npm install sharp')
}

const REELS_DIR = path.join(__dirname, '../public/images/reels')
const MAX_RETRIES = 3
const INITIAL_DELAY = 1000

/**
 * cria diretório se não existir.
 */
function ensureDir(dirPath) {
  if (!fs.existsSync(dirPath)) {
    fs.mkdirSync(dirPath, { recursive: true })
    console.log(`📁 Criado diretório: ${dirPath}`)
  }
}

/**
 * delay com backoff exponencial.
 */
function delay(ms) {
  return new Promise(resolve => setTimeout(resolve, ms))
}

/**
 * extrai ID do reel da URL.
 */
function extractReelId(reelUrl) {
  const match = reelUrl.match(/\/reel\/([A-Za-z0-9_-]+)/)
  return match ? match[1] : null
}

/**
 * baixa arquivo de uma URL.
 */
function downloadFile(url, outputPath) {
  return new Promise((resolve, reject) => {
    const protocol = url.startsWith('https') ? https : http
    const file = fs.createWriteStream(outputPath)
    
    protocol.get(url, (response) => {
      if (response.statusCode === 301 || response.statusCode === 302) {
        // Seguir redirect
        return downloadFile(response.headers.location, outputPath)
          .then(resolve)
          .catch(reject)
      }
      
      if (response.statusCode !== 200) {
        file.close()
        fs.unlinkSync(outputPath)
        reject(new Error(`HTTP ${response.statusCode}: ${response.statusMessage}`))
        return
      }
      
      response.pipe(file)
      file.on('finish', () => {
        file.close()
        resolve()
      })
    }).on('error', (err) => {
      file.close()
      if (fs.existsSync(outputPath)) {
        fs.unlinkSync(outputPath)
      }
      reject(err)
    })
  })
}

/**
 * faz requisição HTTP e retorna resposta como string.
 */
function httpGet(url) {
  return new Promise((resolve, reject) => {
    const protocol = url.startsWith('https') ? https : http
    
    const request = protocol.get(url, {
      headers: {
        'Accept': 'application/json',
        'User-Agent': 'Mozilla/5.0 (compatible; MimoBot/1.0)',
      },
    }, (response) => {
      if (response.statusCode === 301 || response.statusCode === 302) {
        // Seguir redirect
        return httpGet(response.headers.location).then(resolve).catch(reject)
      }

      if (response.statusCode !== 200) {
        reject(new Error(`HTTP ${response.statusCode}: ${response.statusMessage}`))
        return
      }

      let data = ''
      response.on('data', (chunk) => {
        data += chunk
      })
      response.on('end', () => {
        resolve({ data, statusCode: response.statusCode, headers: response.headers })
      })
    })

    request.on('error', reject)
    request.setTimeout(10000, () => {
      request.destroy()
      reject(new Error('Request timeout'))
    })
  })
}

/**
 * tenta buscar thumbnail via oEmbed com retry.
 */
async function fetchThumbnailViaOEmbed(reelUrl, retries = MAX_RETRIES) {
  const urlVariations = [
    (url) => `https://api.instagram.com/oembed?url=${encodeURIComponent(url)}`,
    (url) => `https://api.instagram.com/oembed?url=${encodeURIComponent(url)}&format=json`,
    (url) => `https://api.instagram.com/oembed?url=${encodeURIComponent(url)}&omitscript=true`,
  ]

  for (const urlVariation of urlVariations) {
    for (let attempt = 0; attempt < retries; attempt++) {
      try {
        const oembedUrl = urlVariation(reelUrl)
        const response = await httpGet(oembedUrl)

        if (response.statusCode === 200) {
          const contentType = response.headers['content-type'] || ''
          if (contentType.includes('application/json')) {
            try {
              const data = JSON.parse(response.data)
              if (data.thumbnail_url && typeof data.thumbnail_url === 'string') {
                if (data.thumbnail_url.startsWith('http://') || data.thumbnail_url.startsWith('https://')) {
                  return data.thumbnail_url
                }
              }
            } catch (parseError) {
              // JSON inválido, continuar
            }
          }
        } else if (response.statusCode === 429) {
          const waitTime = INITIAL_DELAY * Math.pow(2, attempt + 1)
          await delay(waitTime)
          continue
        } else if (response.statusCode !== 500 && response.statusCode !== 503) {
          break
        }

        if (attempt < retries - 1) {
          const waitTime = INITIAL_DELAY * Math.pow(2, attempt)
          await delay(waitTime)
        }
      } catch (error) {
        if (attempt < retries - 1) {
          const waitTime = INITIAL_DELAY * Math.pow(2, attempt)
          await delay(waitTime)
        }
      }
    }
  }

  return null
}

/**
 * otimiza imagem baixada.
 */
async function optimizeImage(inputPath, outputPath) {
  if (!sharp) {
    // Se sharp não estiver instalado, apenas copiar
    fs.copyFileSync(inputPath, outputPath)
    return
  }

  try {
    const image = sharp(inputPath)
    const metadata = await image.metadata()

    // Redimensionar se muito grande (max 1080px de largura para reels)
    const maxWidth = 1080
    let processedImage = image

    if (metadata.width > maxWidth) {
      processedImage = image.resize(maxWidth, null, {
        withoutEnlargement: true,
        fit: 'inside',
      })
    }

    // Converter para WebP com qualidade otimizada
    await processedImage
      .webp({ quality: 85, effort: 4 })
      .toFile(outputPath)

    // Remover arquivo original se for diferente do output
    if (inputPath !== outputPath && fs.existsSync(inputPath)) {
      fs.unlinkSync(inputPath)
    }

    return true
  } catch (error) {
    console.error(`  ❌ Erro ao otimizar: ${error.message}`)
    return false
  }
}

/**
 * baixa thumbnail de um reel.
 */
async function downloadReelThumbnail(reelUrl) {
  const reelId = extractReelId(reelUrl)
  if (!reelId) {
    console.error(`❌ URL inválida: ${reelUrl}`)
    return false
  }

  const outputPath = path.join(REELS_DIR, `${reelId}.webp`)
  const tempPath = path.join(REELS_DIR, `${reelId}.tmp`)

  // Verificar se já existe
  if (fs.existsSync(outputPath)) {
    console.log(`✓ Thumbnail já existe: ${reelId}.webp`)
    return true
  }

  console.log(`\n📥 Baixando thumbnail para: ${reelId}`)
  console.log(`   URL: ${reelUrl}`)

  // Tentar via oEmbed
  try {
    const thumbnailUrl = await fetchThumbnailViaOEmbed(reelUrl)
    if (thumbnailUrl) {
      console.log(`   ✓ Thumbnail encontrada via oEmbed`)
      console.log(`   📥 Baixando de: ${thumbnailUrl}`)

      await downloadFile(thumbnailUrl, tempPath)
      console.log(`   ✓ Download concluído`)

      // Otimizar
      if (await optimizeImage(tempPath, outputPath)) {
        console.log(`   ✓ Imagem otimizada: ${reelId}.webp`)
        return true
      }
    }
  } catch (error) {
    console.error(`   ❌ Erro ao baixar: ${error.message}`)
  }

  // Se chegou aqui, oEmbed falhou
  console.log(`\n⚠️  Não foi possível baixar automaticamente.`)
  console.log(`\n📋 Instruções para download manual:`)
  console.log(`   1. Acesse: ${reelUrl}`)
  console.log(`   2. Use uma das ferramentas online:`)
  console.log(`      - https://gramfetchr.com/pt/thumbnail-downloader`)
  console.log(`      - https://snaplytics.io/instagram-reel-thumbnail-downloader/`)
  console.log(`      - https://www.thumbdownloader.com/instagram-thumbnail`)
  console.log(`   3. Baixe a thumbnail e salve como:`)
  console.log(`      ${outputPath}`)
  console.log(`   4. Execute novamente este script para otimizar a imagem\n`)

  return false
}

/**
 * main.
 */
async function main() {
  ensureDir(REELS_DIR)

  const args = process.argv.slice(2)

  if (args.includes('--all')) {
    // Baixar todos os reels configurados
    try {
      // Importar constants (precisa ser dinâmico pois é TypeScript)
      const constantsPath = path.join(__dirname, '../lib/constants.ts')
      const constantsContent = fs.readFileSync(constantsPath, 'utf-8')

      // Extrair reelUrls do arquivo (hack simples, mas funciona)
      const reelUrlMatches = constantsContent.matchAll(/reelUrl:\s*['"]([^'"]+)['"]/g)
      const reelUrls = Array.from(reelUrlMatches).map(m => m[1])

      if (reelUrls.length === 0) {
        console.log('ℹ️  Nenhum reelUrl encontrado em constants.ts')
        return
      }

      console.log(`📋 Encontrados ${reelUrls.length} reels para processar\n`)

      for (const reelUrl of reelUrls) {
        await downloadReelThumbnail(reelUrl)
      }

      console.log(`\n✅ Processamento concluído!`)
    } catch (error) {
      console.error(`❌ Erro ao processar: ${error.message}`)
      process.exit(1)
    }
  } else if (args.length > 0) {
    // Baixar reel específico
    const reelUrl = args[0]
    const success = await downloadReelThumbnail(reelUrl)
    process.exit(success ? 0 : 1)
  } else {
    console.log('📖 Uso:')
    console.log('   node scripts/download-reel-thumbnails.js <reelUrl>')
    console.log('   node scripts/download-reel-thumbnails.js --all')
    console.log('\nExemplo:')
    console.log('   node scripts/download-reel-thumbnails.js https://www.instagram.com/reel/DBACXKPOvd0/')
    process.exit(1)
  }
}

main().catch((error) => {
  console.error(`❌ Erro fatal: ${error.message}`)
  process.exit(1)
})

