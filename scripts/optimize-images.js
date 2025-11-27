#!/usr/bin/env node

/**
 * script para otimizar imagens usando sharp.
 * 
 * converte imagens para webp e avif, gera múltiplos tamanhos.
 * requer: sharp instalado (npm install sharp)
 * 
 * uso: node scripts/optimize-images.js [caminho]
 */

const fs = require('fs')
const path = require('path')

// Verificar se sharp está instalado
let sharp
try {
  sharp = require('sharp')
} catch (error) {
  console.error('❌ sharp não está instalado. Execute: npm install sharp')
  process.exit(1)
}

const PUBLIC_IMAGES_DIR = path.join(__dirname, '../public/images')
const SIZES = [400, 800, 1200, 1920]
const QUALITY = {
  webp: 85,
  avif: 80,
}

/**
 * processa uma imagem individual.
 */
async function processImage(inputPath, outputDir, baseName) {
  try {
    const image = sharp(inputPath)
    const metadata = await image.metadata()
    
    console.log(`  📸 Processando: ${baseName} (${metadata.width}x${metadata.height})`)
    
    // Gerar WebP em múltiplos tamanhos
    for (const size of SIZES) {
      if (metadata.width < size) {
        // Se imagem original é menor, não gerar tamanho maior
        continue
      }
      
      const webpPath = path.join(outputDir, `${baseName}-${size}.webp`)
      await image
        .resize(size, null, { withoutEnlargement: true })
        .webp({ quality: QUALITY.webp })
        .toFile(webpPath)
      
      // Gerar AVIF também
      const avifPath = path.join(outputDir, `${baseName}-${size}.avif`)
      await image
        .resize(size, null, { withoutEnlargement: true })
        .avif({ quality: QUALITY.avif })
        .toFile(avifPath)
    }
    
    // Gerar versão original otimizada (WebP)
    const webpOriginalPath = path.join(outputDir, `${baseName}.webp`)
    await image
      .webp({ quality: QUALITY.webp })
      .toFile(webpOriginalPath)
    
    console.log(`  ✅ ${baseName} processada`)
  } catch (error) {
    console.error(`  ❌ Erro ao processar ${baseName}: ${error.message}`)
  }
}

/**
 * processa diretório recursivamente.
 */
async function processDirectory(dir, relativePath = '') {
  const entries = fs.readdirSync(dir, { withFileTypes: true })
  let processed = 0
  
  for (const entry of entries) {
    const fullPath = path.join(dir, entry.name)
    const relativeFilePath = path.join(relativePath, entry.name)
    
    if (entry.isDirectory()) {
      // Processar subdiretório
      await processDirectory(fullPath, relativeFilePath)
    } else if (entry.isFile()) {
      // Processar arquivo de imagem
      const ext = path.extname(entry.name).toLowerCase()
      if (['.jpg', '.jpeg', '.png', '.webp'].includes(ext)) {
        const baseName = path.basename(entry.name, ext)
        const outputDir = dir // Manter na mesma pasta
        
        await processImage(fullPath, outputDir, baseName)
        processed++
      }
    }
  }
  
  return processed
}

/**
 * função principal.
 */
async function main() {
  const targetPath = process.argv[2] || PUBLIC_IMAGES_DIR
  
  if (!fs.existsSync(targetPath)) {
    console.error(`❌ Diretório não encontrado: ${targetPath}`)
    process.exit(1)
  }
  
  console.log('🖼️  Iniciando otimização de imagens...\n')
  console.log(`📁 Diretório: ${targetPath}\n`)
  
  const startTime = Date.now()
  const processed = await processDirectory(targetPath)
  const endTime = Date.now()
  const duration = ((endTime - startTime) / 1000).toFixed(2)
  
  console.log(`\n✅ Processamento concluído!`)
  console.log(`📊 Imagens processadas: ${processed}`)
  console.log(`⏱️  Tempo: ${duration}s`)
  console.log(`\n💡 Dica: Use as versões .webp e .avif no código para melhor performance`)
}

// Executar
main().catch(console.error)

