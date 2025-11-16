#!/usr/bin/env node
/**
 * Script para analisar TODOS os problemas de performance do Lighthouse
 * Extrai informações sobre CLS, LCP, FCP, TBT, SI e oportunidades de otimização
 * 
 * Uso: node scripts/analyze-all-issues.js <arquivo-json-do-lighthouse>
 */

const fs = require('fs');
const path = require('path');

// Verificar argumentos
if (process.argv.length < 3) {
    console.error('❌ Erro: Arquivo JSON do Lighthouse não fornecido');
    console.log('\nUso: node scripts/analyze-all-issues.js <arquivo-json>');
    process.exit(1);
}

const jsonFile = process.argv[2];

// Verificar se arquivo existe
if (!fs.existsSync(jsonFile)) {
    console.error(`❌ Erro: Arquivo não encontrado: ${jsonFile}`);
    process.exit(1);
}

// Ler e parsear JSON
let data;
try {
    const jsonContent = fs.readFileSync(jsonFile, 'utf8');
    data = JSON.parse(jsonContent);
} catch (error) {
    console.error(`❌ Erro ao ler arquivo JSON: ${error.message}`);
    process.exit(1);
}

console.log('🔍 Análise Completa de Performance\n');
console.log('=' .repeat(80));

// Extrair métricas principais
const metrics = data.audits || {};
const categories = data.categories || {};

// Performance Score
const performanceScore = categories.performance?.score * 100 || 0;
console.log(`\n📊 Performance Score: ${performanceScore.toFixed(0)}/100 ${performanceScore >= 90 ? '✅' : '❌'}`);

// Métricas Core Web Vitals
console.log('\n🎯 Core Web Vitals:\n');

const cls = metrics['cumulative-layout-shift'];
if (cls) {
    const clsValue = cls.numericValue || 0;
    const clsTarget = 0.1;
    console.log(`  CLS: ${clsValue.toFixed(3)} (Meta: <${clsTarget}) ${clsValue < clsTarget ? '✅' : '❌'}`);
    if (clsValue >= clsTarget) {
        console.log(`     ⚠️  CRÍTICO: CLS está ${((clsValue / clsTarget) * 100).toFixed(0)}% acima da meta`);
    }
}

const lcp = metrics['largest-contentful-paint'];
if (lcp) {
    const lcpValue = (lcp.numericValue || 0) / 1000; // Converter para segundos
    const lcpTarget = 2.5;
    console.log(`  LCP: ${lcpValue.toFixed(2)}s (Meta: <${lcpTarget}s) ${lcpValue < lcpTarget ? '✅' : '❌'}`);
    if (lcpValue >= lcpTarget) {
        console.log(`     ⚠️  CRÍTICO: LCP está ${((lcpValue / lcpTarget) * 100).toFixed(0)}% acima da meta`);
    }
}

const fcp = metrics['first-contentful-paint'];
if (fcp) {
    const fcpValue = (fcp.numericValue || 0) / 1000; // Converter para segundos
    const fcpTarget = 1.8;
    console.log(`  FCP: ${fcpValue.toFixed(2)}s (Meta: <${fcpTarget}s) ${fcpValue < fcpTarget ? '✅' : '❌'}`);
    if (fcpValue >= fcpTarget) {
        console.log(`     ⚠️  CRÍTICO: FCP está ${((fcpValue / fcpTarget) * 100).toFixed(0)}% acima da meta`);
    }
}

const tbt = metrics['total-blocking-time'];
if (tbt) {
    const tbtValue = (tbt.numericValue || 0);
    const tbtTarget = 200;
    console.log(`  TBT: ${tbtValue.toFixed(0)}ms (Meta: <${tbtTarget}ms) ${tbtValue < tbtTarget ? '✅' : '❌'}`);
}

const si = metrics['speed-index'];
if (si) {
    const siValue = (si.numericValue || 0) / 1000; // Converter para segundos
    const siTarget = 3.4;
    console.log(`  SI:  ${siValue.toFixed(2)}s (Meta: <${siTarget}s) ${siValue < siTarget ? '✅' : '❌'}`);
}

// Oportunidades de Otimização
console.log('\n\n💡 Oportunidades de Otimização:\n');

const opportunities = [
    'render-blocking-resources',
    'unused-css-rules',
    'unused-javascript',
    'modern-image-formats',
    'offscreen-images',
    'unminified-css',
    'unminified-javascript',
    'efficient-animated-content',
    'uses-text-compression',
    'uses-optimized-images',
    'uses-responsive-images',
    'preload-lcp-image',
    'uses-rel-preconnect',
    'font-display',
    'third-party-summary'
];

opportunities.forEach(oppId => {
    const opp = metrics[oppId];
    if (opp && opp.score !== null && opp.score < 1) {
        const savings = opp.details?.overallSavingsMs || 0;
        const savingsWasted = opp.details?.overallSavingsBytes || 0;
        
        if (savings > 0 || savingsWasted > 0) {
            console.log(`  ❌ ${opp.title}`);
            if (savings > 0) {
                console.log(`     Potencial economia: ${(savings / 1000).toFixed(2)}s`);
            }
            if (savingsWasted > 0) {
                console.log(`     Potencial economia de bytes: ${(savingsWasted / 1024).toFixed(2)}KB`);
            }
            if (opp.details?.items && opp.details.items.length > 0) {
                console.log(`     Itens afetados: ${opp.details.items.length}`);
            }
        }
    }
});

// Diagnosticos
console.log('\n\n🔧 Diagnósticos:\n');

const diagnostics = [
    'dom-size',
    'long-tasks',
    'mainthread-work-breakdown',
    'bootup-time',
    'uses-long-cache-ttl',
    'total-byte-weight',
    'uses-http2',
    'uses-passive-event-listeners'
];

diagnostics.forEach(diagId => {
    const diag = metrics[diagId];
    if (diag && diag.score !== null && diag.score < 1) {
        console.log(`  ⚠️  ${diag.title}`);
        if (diag.displayValue) {
            console.log(`     ${diag.displayValue}`);
        }
    }
});

// Layout Shifts detalhados
if (metrics['layout-shifts']?.details?.items) {
    console.log('\n\n📋 Layout Shifts Detalhados:\n');
    
    const shifts = metrics['layout-shifts'].details.items;
    shifts.forEach((item, index) => {
        if (item.score && item.score > 0) {
            console.log(`  Shift #${index + 1}:`);
            console.log(`    Score: ${item.score.toFixed(4)}`);
            if (item.node?.snippet) {
                console.log(`    Elemento: ${item.node.snippet.substring(0, 80)}${item.node.snippet.length > 80 ? '...' : ''}`);
            }
            if (item.node?.selector) {
                console.log(`    Seletor: ${item.node.selector}`);
            }
        }
    });
}

// LCP Element
if (lcp && lcp.details?.items && lcp.details.items.length > 0) {
    console.log('\n\n🖼️  LCP Element:\n');
    const lcpItem = lcp.details.items[0];
    if (lcpItem.node) {
        console.log(`  Elemento: ${lcpItem.node.snippet || lcpItem.node.selector || 'Desconhecido'}`);
    }
    if (lcpItem.url) {
        console.log(`  URL: ${lcpItem.url}`);
    }
    if (lcpItem.size) {
        console.log(`  Tamanho: ${(lcpItem.size / 1024).toFixed(2)}KB`);
    }
}

// Resumo e recomendações
console.log('\n\n' + '='.repeat(80));
console.log('💡 Recomendações Prioritárias:\n');

const recommendations = [];

if (cls && cls.numericValue >= 0.1) {
    recommendations.push({
        priority: 'CRÍTICA',
        issue: 'CLS alto',
        action: 'Identificar e corrigir elementos causando layout shift (principalmente #main-content)'
    });
}

if (lcp && (lcp.numericValue || 0) / 1000 >= 2.5) {
    recommendations.push({
        priority: 'CRÍTICA',
        issue: 'LCP alto',
        action: 'Otimizar imagem LCP, adicionar preload, melhorar TTFB'
    });
}

if (fcp && (fcp.numericValue || 0) / 1000 >= 1.8) {
    recommendations.push({
        priority: 'ALTA',
        issue: 'FCP alto',
        action: 'Reduzir render-blocking resources, otimizar CSS crítico'
    });
}

recommendations.forEach(rec => {
    console.log(`  [${rec.priority}] ${rec.issue}:`);
    console.log(`     → ${rec.action}`);
});

console.log('\n');

