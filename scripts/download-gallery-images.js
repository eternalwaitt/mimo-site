#!/usr/bin/env node

/**
 * script para baixar imagens da galeria do Unsplash.
 * 
 * baixa imagens de serviços de salão de beleza e salva localmente.
 * otimiza com sharp se disponível.
 * 
 * uso: node scripts/download-gallery-images.js
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
  console.log('⚠️  sharp não está instalado. Imagens serão salvas sem otimização.')
  console.log('   Para otimizar: npm install sharp')
}

const GALLERY_DIR = path.join(__dirname, '../public/images/galeria')
const IMAGES_TO_DOWNLOAD = [
  // Salão
  {
    url: 'https://images.unsplash.com/photo-1560066984-138dadb4c035?w=1200&q=85&auto=format&fit=crop',
    filename: 'salao-corte-1.webp',
    category: 'salao',
    alt: 'Corte de cabelo profissional'
  },
  {
    url: 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=1200&q=85&auto=format&fit=crop',
    filename: 'salao-coloracao-1.webp',
    category: 'salao',
    alt: 'Coloração de cabelo moderna'
  },
  {
    url: 'https://images.unsplash.com/photo-1516975080664-ed2fc6a32937?w=1200&q=85&auto=format&fit=crop',
    filename: 'salao-estilo-1.webp',
    category: 'salao',
    alt: 'Estilo de cabelo profissional'
  },
  {
    url: 'https://images.unsplash.com/photo-1560869713-7d5639459e6b?w=1200&q=85&auto=format&fit=crop',
    filename: 'salao-tratamento-1.webp',
    category: 'salao',
    alt: 'Tratamento capilar'
  },
  
  // Esmalteria
  {
    url: 'https://images.unsplash.com/photo-1604654894610-df63bc536371?w=1200&q=85&auto=format&fit=crop',
    filename: 'esmalteria-unhas-1.webp',
    category: 'esmalteria',
    alt: 'Unhas decoradas profissionalmente'
  },
  {
    url: 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=1200&q=85&auto=format&fit=crop',
    filename: 'esmalteria-manicure-1.webp',
    category: 'esmalteria',
    alt: 'Manicure profissional'
  },
  {
    url: 'https://images.unsplash.com/photo-1604654894610-df63bc536371?w=1200&q=85&auto=format&fit=crop&crop=center',
    filename: 'esmalteria-gel-1.webp',
    category: 'esmalteria',
    alt: 'Esmaltação em gel'
  },
  {
    url: 'https://images.unsplash.com/photo-1604654894610-df63bc536371?w=1200&q=85&auto=format&fit=crop&crop=top',
    filename: 'esmalteria-design-1.webp',
    category: 'esmalteria',
    alt: 'Design de unhas artístico'
  },
  
  // Cílios
  {
    url: 'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?w=1200&q=85&auto=format&fit=crop&crop=face',
    filename: 'cilios-design-1.webp',
    category: 'cilios',
    alt: 'Design de sobrancelhas'
  },
  {
    url: 'https://images.unsplash.com/photo-1512496015851-a90fb38ba796?w=1200&q=85&auto=format&fit=crop',
    filename: 'cilios-lash-lift-1.webp',
    category: 'cilios',
    alt: 'Lash lift'
  },
  
  // Micropigmentação
  {
    url: 'https://images.unsplash.com/photo-1512496015851-a90fb38ba796?w=1200&q=85&auto=format&fit=crop',
    filename: 'micro-sobrancelhas-1.webp',
    category: 'micropigmentacao',
    alt: 'Nanoblading de sobrancelhas'
  },
  {
    url: 'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?w=1200&q=85&auto=format&fit=crop&crop=face',
    filename: 'micro-pigmentacao-1.webp',
    category: 'micropigmentacao',
    alt: 'Micropigmentação profissional'
  },
  
  // Estética Facial
  {
    url: 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=1200&q=85&auto=format&fit=crop',
    filename: 'facial-tratamento-1.webp',
    category: 'estetica-facial',
    alt: 'Tratamento facial profissional'
  },
  {
    url: 'https://images.unsplash.com/photo-1571875257727-256c39da42af?w=1200&q=85&auto=format&fit=crop',
    filename: 'facial-skincare-1.webp',
    category: 'estetica-facial',
    alt: 'Skincare profissional'
  },
  {
    url: 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=1200&q=85&auto=format&fit=crop&crop=face',
    filename: 'facial-cuidados-1.webp',
    category: 'estetica-facial',
    alt: 'Cuidados com a pele'
  },
  
  // Estética Corporal
  {
    url: 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=1200&q=85&auto=format&fit=crop',
    filename: 'corporal-massagem-1.webp',
    category: 'estetica-corporal',
    alt: 'Massagem relaxante'
  },
  {
    url: 'https://images.unsplash.com/photo-1571875257727-256c39da42af?w=1200&q=85&auto=format&fit=crop',
    filename: 'corporal-tratamento-1.webp',
    category: 'estetica-corporal',
    alt: 'Tratamento corporal'
  },
]

/**
 * baixa uma imagem de uma URL.
 */
function downloadImage(url, filepath) {
  return new Promise((resolve, reject) => {
    const protocol = url.startsWith('https') ? https : http
    
    protocol.get(url, (response) => {
      if (response.statusCode !== 200) {
        reject(new Error(`Failed to download: ${response.statusCode}`))
        return
      }
      
      const fileStream = fs.createWriteStream(filepath)
      response.pipe(fileStream)
      
      fileStream.on('finish', () => {
        fileStream.close()
        resolve(filepath)
      })
      
      fileStream.on('error', (err) => {
        fs.unlink(filepath, () => {})
        reject(err)
      })
    }).on('error', reject)
  })
}

/**
 * otimiza imagem com sharp se disponível.
 */
async function optimizeImage(inputPath, outputPath) {
  if (!sharp) {
    // Se sharp não estiver disponível, apenas copia o arquivo
    fs.copyFileSync(inputPath, outputPath)
    return
  }
  
  try {
    await sharp(inputPath)
      .webp({ quality: 85 })
      .resize(1200, null, { withoutEnlargement: true })
      .toFile(outputPath)
    
    // Remove arquivo original se otimização foi bem-sucedida
    if (fs.existsSync(outputPath)) {
      fs.unlinkSync(inputPath)
    }
  } catch (error) {
    console.error(`  ⚠️  Erro ao otimizar: ${error.message}`)
    // Se falhar, mantém o original
    if (!fs.existsSync(outputPath)) {
      fs.copyFileSync(inputPath, outputPath)
    }
  }
}

/**
 * cria diretório se não existir.
 */
function ensureDir(dirPath) {
  if (!fs.existsSync(dirPath)) {
    fs.mkdirSync(dirPath, { recursive: true })
  }
}

/**
 * função principal.
 */
async function main() {
  console.log('📥 Baixando imagens da galeria...\n')
  
  // Criar diretórios por categoria
  const categories = ['salao', 'esmalteria', 'cilios', 'micropigmentacao', 'estetica-facial', 'estetica-corporal']
  categories.forEach(cat => {
    ensureDir(path.join(GALLERY_DIR, cat))
  })
  
  let successCount = 0
  let failCount = 0
  
  for (const image of IMAGES_TO_DOWNLOAD) {
    const categoryDir = path.join(GALLERY_DIR, image.category)
    const tempPath = path.join(categoryDir, `temp-${image.filename}`)
    const finalPath = path.join(categoryDir, image.filename)
    
    // Pular se já existe
    if (fs.existsSync(finalPath)) {
      console.log(`⏭️  ${image.filename} já existe, pulando...`)
      continue
    }
    
    try {
      process.stdout.write(`⬇️  Baixando ${image.filename}... `)
      
      // Baixar imagem
      await downloadImage(image.url, tempPath)
      
      // Otimizar se sharp estiver disponível
      if (sharp) {
        await optimizeImage(tempPath, finalPath)
        process.stdout.write('✓ (otimizado)\n')
      } else {
        fs.renameSync(tempPath, finalPath)
        process.stdout.write('✓\n')
      }
      
      successCount++
    } catch (error) {
      console.error(`✗ Erro: ${error.message}`)
      failCount++
      
      // Limpar arquivo temporário se existir
      if (fs.existsSync(tempPath)) {
        fs.unlinkSync(tempPath)
      }
    }
  }
  
  console.log(`\n✅ Concluído!`)
  console.log(`   Sucesso: ${successCount}`)
  console.log(`   Falhas: ${failCount}`)
  console.log(`\n📁 Imagens salvas em: ${GALLERY_DIR}`)
  console.log(`\n💡 Próximo passo: Atualizar app/galeria/page.tsx com as novas imagens`)
}

main().catch(console.error)

