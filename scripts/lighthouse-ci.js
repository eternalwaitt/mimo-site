#!/usr/bin/env node

/**
 * lighthouse ci script - valida scores do lighthouse.
 * 
 * testa home page em mobile e desktop.
 * falha se qualquer categoria < 90.
 * pode ser usado em ci ou manualmente.
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
const CATEGORIES = ['PERFORMANCE', 'ACCESSIBILITY', 'BEST_PRACTICES', 'SEO']
const MIN_SCORE = 90
const MAX_LCP = 2500 // 2.5s in milliseconds

/**
 * faz requisição à api do pagespeed insights.
 */
function runPageSpeedTest(url, strategy = 'MOBILE') {
  return new Promise((resolve, reject) => {
    const categoryParam = CATEGORIES.map(c => `category=${c}`).join('&')
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
 * extrai scores das categorias e métricas detalhadas.
 */
function extractScores(result) {
  if (!result.lighthouseResult) {
    return null
  }
  
  const lhr = result.lighthouseResult
  const categories = lhr.categories || {}
  const audits = lhr.audits || {}
  const scores = {}
  
  Object.keys(categories).forEach(key => {
    scores[key] = Math.round((categories[key].score || 0) * 100)
  })
  
  // Extrair métricas de performance detalhadas
  const performanceMetrics = {
    lcp: audits['largest-contentful-paint']?.numericValue || 0,
    cls: audits['cumulative-layout-shift']?.numericValue || 0,
    tbt: audits['total-blocking-time']?.numericValue || 0,
    fcp: audits['first-contentful-paint']?.numericValue || 0,
    tti: audits['interactive']?.numericValue || 0,
  }
  
  // Extrair oportunidades de melhoria (top 5)
  const opportunities = Object.values(audits)
    .filter(audit => audit.details?.type === 'opportunity' && audit.score !== null && audit.score < 1)
    .map(audit => ({
      id: audit.id,
      title: audit.title,
      score: audit.score,
      numericValue: audit.numericValue,
      displayValue: audit.displayValue,
    }))
    .sort((a, b) => (a.score || 0) - (b.score || 0))
    .slice(0, 5)
  
  return {
    scores,
    performanceMetrics,
    opportunities,
  }
}

/**
 * função principal.
 */
async function main() {
  const isCI = process.env.CI === 'true' || process.argv.includes('--ci')
  
  console.log('🚀 Lighthouse CI - Validando scores...\n')
  console.log(`Base URL: ${BASE_URL}`)
  console.log(`Score mínimo: ${MIN_SCORE}/100\n`)
  
  const results = {
    mobile: null,
    desktop: null,
  }
  
  let hasFailures = false
  
  // Testar mobile
  console.log('📱 Testando Mobile...')
  try {
    const mobileResult = await runPageSpeedTest(`${BASE_URL}/`, 'MOBILE')
    const mobileScores = extractScores(mobileResult)
    results.mobile = mobileScores
    
    if (mobileScores) {
      const scores = mobileScores.scores || mobileScores
      console.log(`   Performance: ${scores.performance || 'N/A'}/100`)
      console.log(`   Accessibility: ${scores.accessibility || 'N/A'}/100`)
      console.log(`   Best Practices: ${scores['best-practices'] || 'N/A'}/100`)
      console.log(`   SEO: ${scores.seo || 'N/A'}/100`)
      
      if (mobileScores.performanceMetrics) {
        const pm = mobileScores.performanceMetrics
        const lcpSeconds = (pm.lcp / 1000).toFixed(2)
        console.log(`   📈 LCP: ${lcpSeconds}s`)
        console.log(`   📈 CLS: ${pm.cls.toFixed(3)}`)
        console.log(`   📈 TBT: ${(pm.tbt / 1000).toFixed(2)}s`)
        
        // Validate LCP
        if (pm.lcp > MAX_LCP) {
          console.error(`   ❌ LCP: ${lcpSeconds}s > ${(MAX_LCP / 1000).toFixed(1)}s (target)`)
          hasFailures = true
        }
      }
      
      if (mobileScores.opportunities && mobileScores.opportunities.length > 0) {
        console.log(`   🔍 Top oportunidades:`)
        mobileScores.opportunities.slice(0, 3).forEach(opp => {
          console.log(`      - ${opp.title}: ${opp.displayValue || 'Ver detalhes'}`)
        })
      }
      
      // Verificar scores
      const categories = ['performance', 'accessibility', 'best-practices', 'seo']
      categories.forEach(cat => {
        const score = scores[cat]
        if (score !== undefined && score < MIN_SCORE) {
          console.error(`   ❌ ${cat}: ${score} < ${MIN_SCORE}`)
          hasFailures = true
        }
      })
    }
  } catch (error) {
    console.error(`   ❌ Erro: ${error.message}`)
    hasFailures = true
  }
  
  // Aguardar entre requisições
  await new Promise(resolve => setTimeout(resolve, 2000))
  
  // Testar desktop
  console.log('\n🖥️  Testando Desktop...')
  try {
    const desktopResult = await runPageSpeedTest(`${BASE_URL}/`, 'DESKTOP')
    const desktopScores = extractScores(desktopResult)
    results.desktop = desktopScores
    
    if (desktopScores) {
      const scores = desktopScores.scores || desktopScores
      console.log(`   Performance: ${scores.performance || 'N/A'}/100`)
      console.log(`   Accessibility: ${scores.accessibility || 'N/A'}/100`)
      console.log(`   Best Practices: ${scores['best-practices'] || 'N/A'}/100`)
      console.log(`   SEO: ${scores.seo || 'N/A'}/100`)
      
      if (desktopScores.performanceMetrics) {
        const pm = desktopScores.performanceMetrics
        console.log(`   📈 LCP: ${(pm.lcp / 1000).toFixed(2)}s`)
        console.log(`   📈 CLS: ${pm.cls.toFixed(3)}`)
        console.log(`   📈 TBT: ${(pm.tbt / 1000).toFixed(2)}s`)
      }
      
      if (desktopScores.opportunities && desktopScores.opportunities.length > 0) {
        console.log(`   🔍 Top oportunidades:`)
        desktopScores.opportunities.slice(0, 3).forEach(opp => {
          console.log(`      - ${opp.title}: ${opp.displayValue || 'Ver detalhes'}`)
        })
      }
      
      // Verificar scores
      const categories = ['performance', 'accessibility', 'best-practices', 'seo']
      categories.forEach(cat => {
        const score = scores[cat]
        if (score !== undefined && score < MIN_SCORE) {
          console.error(`   ❌ ${cat}: ${score} < ${MIN_SCORE}`)
          hasFailures = true
        }
      })
    }
  } catch (error) {
    console.error(`   ❌ Erro: ${error.message}`)
    hasFailures = true
  }
  
  // Salvar resultados
  const outputDir = path.join(__dirname, '../docs/lighthouse')
  if (!fs.existsSync(outputDir)) {
    fs.mkdirSync(outputDir, { recursive: true })
  }
  
  const timestamp = new Date().toISOString().replace(/[:.]/g, '-')
  const outputFile = path.join(outputDir, `lighthouse-ci-${timestamp}.json`)
  
  fs.writeFileSync(
    outputFile,
    JSON.stringify({
      timestamp: new Date().toISOString(),
      baseUrl: BASE_URL,
      minScore: MIN_SCORE,
      results,
      passed: !hasFailures,
    }, null, 2)
  )
  
  console.log(`\n📄 Resultados salvos em: ${outputFile}`)
  
  // Resultado final
  if (hasFailures) {
    console.log('\n❌ Lighthouse CI falhou! Alguns scores estão abaixo de 90.')
    process.exit(1)
  } else {
    console.log('\n✅ Lighthouse CI passou! Todos os scores estão ≥ 90.')
    process.exit(0)
  }
}

// Executar
main().catch((error) => {
  console.error('❌ Erro fatal:', error.message)
  process.exit(1)
})

