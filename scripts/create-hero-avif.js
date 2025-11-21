#!/usr/bin/env node

/**
 * script para criar versão AVIF da hero image mobile.
 * AVIF tem melhor compressão que WebP, melhorando LCP.
 */

const fs = require('fs')
const path = require('path')
let sharp

try {
  sharp = require('sharp')
} catch (error) {
  console.error('❌ sharp não está instalado. Execute: npm install sharp')
  process.exit(1)
}

const INPUT_PATH = path.join(__dirname, '../public/images/hero-bg-mobile.webp')
const OUTPUT_PATH = path.join(__dirname, '../public/images/hero-bg-mobile.avif')

async function createAvif() {
  if (!fs.existsSync(INPUT_PATH)) {
    console.error(`❌ Arquivo não encontrado: ${INPUT_PATH}`)
    process.exit(1)
  }

  console.log('🖼️  Convertendo hero-bg-mobile.webp para AVIF...')

  try {
    const stats = fs.statSync(INPUT_PATH)
    const originalSize = (stats.size / 1024).toFixed(2)

    await sharp(INPUT_PATH)
      .avif({ quality: 80, effort: 4 }) // quality 80 = bom balance, effort 4 = rápido
      .toFile(OUTPUT_PATH)

    const newStats = fs.statSync(OUTPUT_PATH)
    const newSize = (newStats.size / 1024).toFixed(2)
    const savings = ((1 - newStats.size / stats.size) * 100).toFixed(1)

    console.log(`✅ AVIF criado com sucesso!`)
    console.log(`   Original (WebP): ${originalSize} KB`)
    console.log(`   Novo (AVIF): ${newSize} KB`)
    console.log(`   Economia: ${savings}%`)
    console.log(`\n📁 Arquivo salvo em: ${OUTPUT_PATH}`)
  } catch (error) {
    console.error(`❌ Erro ao criar AVIF: ${error.message}`)
    process.exit(1)
  }
}

createAvif().catch(console.error)


