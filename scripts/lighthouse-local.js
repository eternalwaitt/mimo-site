#!/usr/bin/env node

/**
 * script para rodar lighthouse mobile contra localhost:3000.
 * 
 * testa apenas a home page e retorna métricas completas.
 * pode usar lighthouse CLI ou chrome devtools protocol.
 */

const { execSync } = require('child_process')
const fs = require('fs')
const path = require('path')

const BASE_URL = 'http://localhost:3000'
const OUTPUT_FILE = path.join(__dirname, '../docs/lighthouse-local-baseline.json')
const DISABLE_ANALYTICS = process.env.DISABLE_ANALYTICS === 'true'

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
 * executa lighthouse e retorna resultado.
 */
function runLighthouse(url) {
  const tempFile = path.join(__dirname, '../.lighthouse-temp.json')
  
  try {
    const chromeFlags = DISABLE_ANALYTICS 
      ? '--headless --no-sandbox --disable-features=ScriptStreaming'
      : '--headless --no-sandbox'
    const command = `lighthouse "${url}" --only-categories=performance,accessibility,best-practices,seo --emulated-form-factor=mobile --output=json --output-path="${tempFile}" --chrome-flags="${chromeFlags}" --quiet --no-enable-error-reporting`
    execSync(command, { stdio: 'inherit', timeout: 120000, env: { ...process.env, DISABLE_ANALYTICS: DISABLE_ANALYTICS ? 'true' : undefined } })
    
    const result = JSON.parse(fs.readFileSync(tempFile, 'utf8'))
    fs.unlinkSync(tempFile)
    
    return result
  } catch (error) {
    if (fs.existsSync(tempFile)) {
      fs.unlinkSync(tempFile)
    }
    throw error
  }
}

/**
 * extrai métricas do resultado do lighthouse.
 */
function extractMetrics(result) {
  const lhr = result.lhr || result
  const categories = lhr.categories || {}
  const audits = lhr.audits || {}
  
  const performance = categories.performance || {}
  const accessibility = categories.accessibility || {}
  const bestPractices = categories['best-practices'] || {}
  const seo = categories.seo || {}
  
  // LCP element
  const lcpAudit = audits['largest-contentful-paint']
  const lcpElement = lcpAudit?.details?.items?.[0]?.node || null
  
  // Opportunities
  const opportunities = Object.values(audits)
    .filter(audit => audit.details?.type === 'opportunity' && audit.score !== null && audit.score < 1)
    .sort((a, b) => (b.numericValue || 0) - (a.numericValue || 0))
    .slice(0, 5)
    .map(audit => ({
      id: audit.id,
      title: audit.title,
      score: audit.score,
      numericValue: audit.numericValue,
      displayValue: audit.displayValue,
    }))
  
  // Unused JavaScript
  const unusedJs = opportunities.find(opp => opp.id === 'unused-javascript')
  
  return {
    scores: {
      performance: Math.round((performance.score || 0) * 100),
      accessibility: Math.round((accessibility.score || 0) * 100),
      bestPractices: Math.round((bestPractices.score || 0) * 100),
      seo: Math.round((seo.score || 0) * 100),
    },
    metrics: {
      lcp: audits['largest-contentful-paint']?.numericValue || 0,
      fcp: audits['first-contentful-paint']?.numericValue || 0,
      cls: audits['cumulative-layout-shift']?.numericValue || 0,
      tbt: audits['total-blocking-time']?.numericValue || 0,
      tti: audits['interactive']?.numericValue || 0,
    },
    lcpElement: lcpElement ? {
      selector: lcpElement.selector,
      nodeLabel: lcpElement.nodeLabel,
      snippet: lcpElement.snippet,
    } : null,
    unusedJavaScript: unusedJs ? {
      savings: unusedJs.numericValue,
      displayValue: unusedJs.displayValue,
    } : null,
    topOpportunities: opportunities,
  }
}

/**
 * função principal.
 */
async function main() {
  console.log('🚀 Lighthouse Local - Testando Home Page\n')
  console.log(`URL: ${BASE_URL}`)
  if (DISABLE_ANALYTICS) {
    console.log('📊 Analytics desabilitado para testes de performance\n')
  } else {
    console.log('')
  }
  
  // Verificar se lighthouse está instalado
  if (!checkLighthouse()) {
    console.error('❌ Lighthouse CLI não está instalado!')
    console.error('   Instale com: npm install -g lighthouse')
    console.error('   Ou: npx lighthouse (sem instalar globalmente)')
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
      req.setTimeout(3000, () => reject(new Error('Timeout')))
    })
  } catch (error) {
    console.error(`❌ Servidor não está rodando em ${BASE_URL}`)
    console.error('   Inicie o servidor com: npm run build && npm run start')
    process.exit(1)
  }
  
  console.log('📊 Executando Lighthouse...\n')
  
  try {
    const result = runLighthouse(BASE_URL)
    const metrics = extractMetrics(result)
    
    // Salvar resultado
    if (!fs.existsSync(path.dirname(OUTPUT_FILE))) {
      fs.mkdirSync(path.dirname(OUTPUT_FILE), { recursive: true })
    }
    
    fs.writeFileSync(
      OUTPUT_FILE,
      JSON.stringify({
        timestamp: new Date().toISOString(),
        url: BASE_URL,
        ...metrics,
        fullResult: result, // Incluir resultado completo para análise
      }, null, 2)
    )
    
    // Exibir resultados
    console.log('📊 Resultados:\n')
    console.log(`   Performance: ${metrics.scores.performance}/100`)
    console.log(`   Accessibility: ${metrics.scores.accessibility}/100`)
    console.log(`   Best Practices: ${metrics.scores.bestPractices}/100`)
    console.log(`   SEO: ${metrics.scores.seo}/100`)
    console.log('\n📈 Core Web Vitals:')
    console.log(`   LCP: ${(metrics.metrics.lcp / 1000).toFixed(2)}s`)
    console.log(`   FCP: ${(metrics.metrics.fcp / 1000).toFixed(2)}s`)
    console.log(`   CLS: ${metrics.metrics.cls.toFixed(3)}`)
    console.log(`   TBT: ${(metrics.metrics.tbt / 1000).toFixed(2)}s`)
    
    if (metrics.lcpElement) {
      console.log(`\n🎯 LCP Element:`)
      console.log(`   ${metrics.lcpElement.nodeLabel || metrics.lcpElement.snippet || 'N/A'}`)
    }
    
    if (metrics.unusedJavaScript) {
      console.log(`\n⚠️  JavaScript Não Utilizado:`)
      console.log(`   ${metrics.unusedJavaScript.displayValue}`)
    }
    
    if (metrics.topOpportunities.length > 0) {
      console.log(`\n🔍 Top Opportunities:`)
      metrics.topOpportunities.forEach((opp, i) => {
        console.log(`   ${i + 1}. ${opp.title} (${opp.displayValue || 'N/A'})`)
      })
    }
    
    console.log(`\n📄 Resultado completo salvo em: ${OUTPUT_FILE}`)
    
    // Exit code baseado em performance
    if (metrics.scores.performance < 95 || metrics.metrics.lcp > 2500) {
      console.log('\n❌ Performance abaixo do target!')
      process.exit(1)
    } else {
      console.log('\n✅ Performance dentro do target!')
      process.exit(0)
    }
  } catch (error) {
    console.error(`\n❌ Erro ao executar Lighthouse: ${error.message}`)
    process.exit(1)
  }
}

main().catch(console.error)

