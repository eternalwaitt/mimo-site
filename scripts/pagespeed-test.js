#!/usr/bin/env node

/**
 * script para testar pagespeed insights api.
 * 
 * testa performance mobile de todas as páginas principais.
 * requer: GOOGLE_PAGESPEED_API_KEY no .env.local
 */

const https = require('https')
const fs = require('fs')
const path = require('path')

// Carregar variáveis de ambiente do .env.local
const envPath = path.join(__dirname, '../.env.local')
if (fs.existsSync(envPath)) {
  const envContent = fs.readFileSync(envPath, 'utf8')
  envContent.split('\n').forEach(line => {
    const match = line.match(/^([^=]+)=(.*)$/)
    if (match) {
      const key = match[1].trim()
      const value = match[2].trim().replace(/^["']|["']$/g, '')
      process.env[key] = value
    }
  })
}

const API_KEY = process.env.GOOGLE_PAGESPEED_API_KEY
if (!API_KEY) {
  console.error('❌ GOOGLE_PAGESPEED_API_KEY não encontrada!')
  console.error('   Configure no arquivo .env.local:')
  console.error('   GOOGLE_PAGESPEED_API_KEY=sua_chave_aqui')
  process.exit(1)
}
const BASE_URL = 'https://minhamimo.com.br'
const API_ENDPOINT = 'https://pagespeedonline.googleapis.com/pagespeedonline/v5/runPagespeed'

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
 * faz requisição à api do pagespeed insights.
 */
function runPageSpeedTest(url) {
  return new Promise((resolve, reject) => {
    const apiUrl = `${API_ENDPOINT}?url=${encodeURIComponent(url)}&strategy=MOBILE&category=PERFORMANCE&key=${API_KEY}`
    
    https.get(apiUrl, (res) => {
      let data = ''
      
      res.on('data', (chunk) => {
        data += chunk
      })
      
      res.on('end', () => {
        try {
          const result = JSON.parse(data)
          resolve(result)
        } catch (error) {
          reject(new Error(`Failed to parse response: ${error.message}`))
        }
      })
    }).on('error', (error) => {
      reject(error)
    })
  })
}

/**
 * extrai métricas relevantes do resultado.
 */
function extractMetrics(result) {
  if (!result.lighthouseResult) {
    return null
  }
  
  const lhr = result.lighthouseResult
  const categories = lhr.categories || {}
  const performance = categories.performance || {}
  
  const audits = lhr.audits || {}
  
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
  console.log('🚀 Iniciando testes de PageSpeed Insights...\n')
  console.log(`API Key: ${API_KEY.substring(0, 10)}...`)
  console.log(`Base URL: ${BASE_URL}\n`)
  
  const results = []
  const startTime = Date.now()
  
  for (const page of PAGES) {
    const url = `${BASE_URL}${page.path}`
    console.log(`📊 Testando: ${page.name} (${url})...`)
    
    try {
      const result = await runPageSpeedTest(url)
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
      
      // Rate limiting: aguardar 1 segundo entre requisições
      await new Promise(resolve => setTimeout(resolve, 1000))
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
  const outputDir = path.join(__dirname, '../docs')
  if (!fs.existsSync(outputDir)) {
    fs.mkdirSync(outputDir, { recursive: true })
  }
  
  const timestamp = new Date().toISOString().replace(/[:.]/g, '-')
  const outputFile = path.join(outputDir, `PERFORMANCE-MOBILE-REPORT-${timestamp}.json`)
  
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
    console.log(`\n📊 Score médio: ${avgScore.toFixed(1)}/100`)
  }
}

// Executar
main().catch(console.error)

