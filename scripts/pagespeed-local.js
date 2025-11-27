#!/usr/bin/env node

/**
 * script para testar performance local usando lighthouse cli.
 * 
 * testa performance mobile de todas as páginas principais em localhost:3000.
 * requer: lighthouse instalado (npm install -g lighthouse)
 */

const { execSync } = require('child_process')
const fs = require('fs')
const path = require('path')

const BASE_URL = 'http://localhost:3000'
const OUTPUT_DIR = path.join(__dirname, '../docs')

// Páginas a testar
const PAGES = [
  { path: '/', name: 'Home' },
  { path: '/servicos', name: 'Serviços' },
  { path: '/servicos/salao', name: 'Serviço: Salão' },
  { path: '/servicos/esmalteria', name: 'Serviço: Esmalteria' },
  { path: '/servicos/cilios', name: 'Serviço: Cílios' },
  { path: '/galeria', name: 'Galeria' },
  { path: '/sobre', name: 'Sobre' },
  { path: '/trabalhe-aqui', name: 'Trabalhe Aqui' },
]

/**
 * verifica se lighthouse está instalado.
 */
function checkLighthouse() {
  try {
    execSync('lighthouse --version', { stdio: 'ignore' })
    return true
  } catch {
    return false
  }
}

/**
 * executa lighthouse em uma url.
 */
function runLighthouse(url) {
  const outputFile = path.join(OUTPUT_DIR, `lighthouse-${Date.now()}.json`)
  
  try {
    const command = `lighthouse "${url}" --only-categories=performance --emulated-form-factor=mobile --output=json --output-path="${outputFile}" --chrome-flags="--headless --no-sandbox" --quiet`
    execSync(command, { stdio: 'inherit' })
    
    const result = JSON.parse(fs.readFileSync(outputFile, 'utf8'))
    fs.unlinkSync(outputFile) // Remove arquivo temporário
    
    return result
  } catch (error) {
    if (fs.existsSync(outputFile)) {
      fs.unlinkSync(outputFile)
    }
    throw error
  }
}

/**
 * extrai métricas relevantes do resultado do lighthouse.
 */
function extractMetrics(result) {
  // Lighthouse CLI retorna resultado direto, não dentro de lhr
  const categories = result.categories || {}
  const performance = categories.performance || {}
  
  const audits = result.audits || {}
  
  return {
    score: Math.round((performance.score || 0) * 100),
    lcp: audits['largest-contentful-paint']?.numericValue || 0,
    fid: audits['max-potential-fid']?.numericValue || 0,
    cls: audits['cumulative-layout-shift']?.numericValue || 0,
    tbt: audits['total-blocking-time']?.numericValue || 0,
    tti: audits['interactive']?.numericValue || 0,
    opportunities: Object.values(audits)
      .filter(audit => audit.details?.type === 'opportunity')
      .map(audit => ({
        id: audit.id,
        title: audit.title,
        description: audit.description,
        score: audit.score,
        numericValue: audit.numericValue,
      })),
  }
}

/**
 * função principal.
 */
async function main() {
  console.log('🚀 Iniciando testes de Lighthouse (localhost)...\n')
  console.log(`Base URL: ${BASE_URL}\n`)
  
  // Verificar se lighthouse está instalado
  if (!checkLighthouse()) {
    console.error('❌ Lighthouse não está instalado!')
    console.error('   Instale com: npm install -g lighthouse')
    process.exit(1)
  }
  
  // Verificar se servidor está rodando
  try {
    const http = require('http')
    await new Promise((resolve, reject) => {
      const req = http.get(BASE_URL, (res) => {
        resolve()
      })
      req.on('error', reject)
      req.setTimeout(2000, () => reject(new Error('Timeout')))
    })
  } catch (error) {
    console.error(`❌ Servidor não está rodando em ${BASE_URL}`)
    console.error('   Inicie o servidor com: npm run dev')
    process.exit(1)
  }
  
  const results = []
  const startTime = Date.now()
  
  for (const page of PAGES) {
    const url = `${BASE_URL}${page.path}`
    console.log(`📊 Testando: ${page.name} (${url})...`)
    
    try {
      const result = runLighthouse(url)
      const metrics = extractMetrics(result)
      
      if (metrics) {
        results.push({
          page: page.name,
          path: page.path,
          url,
          ...metrics,
        })
        
        console.log(`   ✅ Score: ${metrics.score}/100`)
        console.log(`   📈 LCP: ${(metrics.lcp / 1000).toFixed(2)}s`)
        console.log(`   📈 CLS: ${metrics.cls.toFixed(3)}`)
        console.log(`   📈 TBT: ${(metrics.tbt / 1000).toFixed(2)}s`)
      } else {
        console.log(`   ⚠️  Não foi possível extrair métricas`)
      }
      
      // Aguardar um pouco entre requisições
      await new Promise(resolve => setTimeout(resolve, 2000))
    } catch (error) {
      console.error(`   ❌ Erro: ${error.message}`)
      results.push({
        page: page.name,
        path: page.path,
        url,
        error: error.message,
      })
    }
    
    console.log('')
  }
  
  const endTime = Date.now()
  const duration = ((endTime - startTime) / 1000).toFixed(2)
  
  // Salvar resultados
  if (!fs.existsSync(OUTPUT_DIR)) {
    fs.mkdirSync(OUTPUT_DIR, { recursive: true })
  }
  
  const timestamp = new Date().toISOString().replace(/[:.]/g, '-')
  const outputFile = path.join(OUTPUT_DIR, `PERFORMANCE-LOCAL-REPORT-${timestamp}.json`)
  
  fs.writeFileSync(
    outputFile,
    JSON.stringify({
      timestamp: new Date().toISOString(),
      duration: `${duration}s`,
      pages: results,
    }, null, 2)
  )
  
  console.log(`\n✅ Testes concluídos em ${duration}s`)
  console.log(`📄 Resultados salvos em: ${outputFile}`)
  
  // Resumo
  const validResults = results.filter(r => r.score !== undefined)
  if (validResults.length > 0) {
    const avgScore = validResults.reduce((sum, r) => sum + r.score, 0) / validResults.length
    const homeResult = results.find(r => r.path === '/')
    
    console.log(`\n📊 Score médio: ${avgScore.toFixed(1)}/100`)
    if (homeResult && homeResult.score) {
      console.log(`\n🏠 Home Page:`)
      console.log(`   Score: ${homeResult.score}/100`)
      console.log(`   LCP: ${(homeResult.lcp / 1000).toFixed(2)}s`)
      console.log(`   CLS: ${homeResult.cls.toFixed(3)}`)
      console.log(`   TBT: ${(homeResult.tbt / 1000).toFixed(2)}s`)
    }
  }
}

// Executar
main().catch(console.error)

