#!/usr/bin/env node

/**
 * script para testar pagespeed insights api.
 * 
 * testa todas as categorias do lighthouse (performance, accessibility, best practices, seo)
 * em mobile e desktop para páginas principais.
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
const BASE_URL = 'https://mimo-site.vercel.app'
const API_ENDPOINT = 'https://pagespeedonline.googleapis.com/pagespeedonline/v5/runPagespeed'

// Páginas a testar para baseline
const PAGES = [
  { path: '/', name: 'Home' },
  { path: '/servicos', name: 'Serviços' },
  { path: '/sobre', name: 'Sobre' },
]

// Todas as categorias do lighthouse
const CATEGORIES = ['PERFORMANCE', 'ACCESSIBILITY', 'BEST_PRACTICES', 'SEO']
const STRATEGIES = ['MOBILE', 'DESKTOP']

/**
 * faz requisição à api do pagespeed insights.
 */
function runPageSpeedTest(url, strategy = 'MOBILE', categories = CATEGORIES) {
  return new Promise((resolve, reject) => {
    const categoryParam = categories.map(c => `category=${c}`).join('&')
    const apiUrl = `${API_ENDPOINT}?url=${encodeURIComponent(url)}&strategy=${strategy}&${categoryParam}&key=${API_KEY}`
    
    https.get(apiUrl, (res) => {
      let data = ''
      
      res.on('data', (chunk) => {
        data += chunk
      })
      
      res.on('end', () => {
        try {
          const result = JSON.parse(data)
          if (result.error) {
            reject(new Error(result.error.message || 'API Error'))
          } else {
            resolve(result)
          }
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
  const audits = lhr.audits || {}
  
  // Extrair scores de todas as categorias
  const categoryScores = {}
  Object.keys(categories).forEach(key => {
    categoryScores[key] = Math.round((categories[key].score || 0) * 100)
  })
  
  // Extrair métricas de performance
  const performanceMetrics = {
    lcp: audits['largest-contentful-paint']?.numericValue || 0,
    fid: audits['max-potential-fid']?.numericValue || 0,
    cls: audits['cumulative-layout-shift']?.numericValue || 0,
    tbt: audits['total-blocking-time']?.numericValue || 0,
    tti: audits['interactive']?.numericValue || 0,
    fcp: audits['first-contentful-paint']?.numericValue || 0,
  }
  
  // Extrair top issues por categoria
  const topIssues = {}
  Object.keys(categories).forEach(categoryKey => {
    const category = categories[categoryKey]
    const auditRefs = category.auditRefs || []
    const failedAudits = auditRefs
      .filter(ref => {
        const audit = audits[ref.id]
        return audit && audit.score !== null && audit.score < 0.9
      })
      .map(ref => {
        const audit = audits[ref.id]
        return {
          id: ref.id,
          title: audit.title,
          description: audit.description,
          score: Math.round((audit.score || 0) * 100),
          displayValue: audit.displayValue,
        }
      })
      .slice(0, 5) // Top 5 issues
    
    if (failedAudits.length > 0) {
      topIssues[categoryKey] = failedAudits
    }
  })
  
  return {
    categoryScores,
    performanceMetrics,
    topIssues,
  }
}

/**
 * função principal.
 */
async function main() {
  console.log('🚀 Iniciando testes de Lighthouse (PageSpeed Insights)...\n')
  console.log(`API Key: ${API_KEY.substring(0, 10)}...`)
  console.log(`Base URL: ${BASE_URL}\n`)
  
  const allResults = {
    mobile: [],
    desktop: [],
  }
  const startTime = Date.now()
  
  // Testar cada estratégia (mobile e desktop)
  for (const strategy of STRATEGIES) {
    console.log(`\n${'='.repeat(60)}`)
    console.log(`📱 Testando em ${strategy}`)
    console.log('='.repeat(60))
    
    const results = []
    
    for (const page of PAGES) {
      const url = `${BASE_URL}${page.path}`
      console.log(`\n📊 Testando: ${page.name} (${url})...`)
      
      try {
        const result = await runPageSpeedTest(url, strategy, CATEGORIES)
        const metrics = extractMetrics(result)
        
        if (metrics) {
          const pageResult = {
            page: page.name,
            path: page.path,
            url,
            strategy,
            ...metrics,
          }
          results.push(pageResult)
          
          console.log(`   ✅ Performance: ${metrics.categoryScores.performance || 'N/A'}/100`)
          console.log(`   ✅ Accessibility: ${metrics.categoryScores.accessibility || 'N/A'}/100`)
          console.log(`   ✅ Best Practices: ${metrics.categoryScores['best-practices'] || 'N/A'}/100`)
          console.log(`   ✅ SEO: ${metrics.categoryScores.seo || 'N/A'}/100`)
          
          if (metrics.performanceMetrics) {
            const pm = metrics.performanceMetrics
            console.log(`   📈 LCP: ${(pm.lcp / 1000).toFixed(2)}s`)
            console.log(`   📈 CLS: ${pm.cls.toFixed(3)}`)
            console.log(`   📈 TBT: ${(pm.tbt / 1000).toFixed(2)}s`)
          }
        } else {
          console.log(`   ⚠️  Não foi possível extrair métricas`)
        }
        
        // Rate limiting: aguardar 1.5 segundos entre requisições
        await new Promise(resolve => setTimeout(resolve, 1500))
      } catch (error) {
        console.error(`   ❌ Erro: ${error.message}`)
        results.push({
          page: page.name,
          path: page.path,
          url,
          strategy,
          error: error.message,
        })
      }
    }
    
    allResults[strategy.toLowerCase()] = results
  }
  
  const endTime = Date.now()
  const duration = ((endTime - startTime) / 1000).toFixed(2)
  
  // Criar diretório de lighthouse reports
  const outputDir = path.join(__dirname, '../docs/lighthouse')
  if (!fs.existsSync(outputDir)) {
    fs.mkdirSync(outputDir, { recursive: true })
  }
  
  const timestamp = new Date().toISOString().replace(/[:.]/g, '-')
  
  // Salvar resultados separados por estratégia
  const mobileFile = path.join(outputDir, `lighthouse-baseline-mobile.json`)
  const desktopFile = path.join(outputDir, `lighthouse-baseline-desktop.json`)
  
  fs.writeFileSync(
    mobileFile,
    JSON.stringify({
      timestamp: new Date().toISOString(),
      strategy: 'MOBILE',
      baseUrl: BASE_URL,
      pages: allResults.mobile,
    }, null, 2)
  )
  
  fs.writeFileSync(
    desktopFile,
    JSON.stringify({
      timestamp: new Date().toISOString(),
      strategy: 'DESKTOP',
      baseUrl: BASE_URL,
      pages: allResults.desktop,
    }, null, 2)
  )
  
  console.log(`\n${'='.repeat(60)}`)
  console.log(`✅ Testes concluídos em ${duration}s`)
  console.log(`📄 Resultados salvos em:`)
  console.log(`   - ${mobileFile}`)
  console.log(`   - ${desktopFile}`)
  
  // Resumo por categoria
  console.log(`\n${'='.repeat(60)}`)
  console.log('📊 Resumo de Scores')
  console.log('='.repeat(60))
  
  const calculateAverage = (results, category) => {
    const valid = results
      .filter(r => r.categoryScores && r.categoryScores[category] !== undefined)
      .map(r => r.categoryScores[category])
    return valid.length > 0
      ? (valid.reduce((a, b) => a + b, 0) / valid.length).toFixed(1)
      : 'N/A'
  }
  
  const categories = ['performance', 'accessibility', 'best-practices', 'seo']
  categories.forEach(cat => {
    const mobileAvg = calculateAverage(allResults.mobile, cat)
    const desktopAvg = calculateAverage(allResults.desktop, cat)
    console.log(`${cat.toUpperCase().padEnd(20)} Mobile: ${mobileAvg.padStart(5)} | Desktop: ${desktopAvg.padStart(5)}`)
  })
}

// Executar
main().catch(console.error)

