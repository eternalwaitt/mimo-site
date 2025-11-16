#!/usr/bin/env node
/**
 * Script para analisar CLS (Cumulative Layout Shift) do Lighthouse
 * Extrai informações detalhadas sobre elementos causando layout shift
 * 
 * Uso: node scripts/analyze-cls.js <arquivo-json-do-lighthouse>
 */

const fs = require('fs');
const path = require('path');

// Verificar argumentos
if (process.argv.length < 3) {
    console.error('❌ Erro: Arquivo JSON do Lighthouse não fornecido');
    console.log('\nUso: node scripts/analyze-cls.js <arquivo-json>');
    console.log('Exemplo: node scripts/analyze-cls.js pagespeed-results/validation-mobile-*.json');
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

console.log('🔍 Análise de CLS (Cumulative Layout Shift)\n');
console.log('=' .repeat(60));

// Extrair métricas principais
const clsAudit = data.audits?.['cumulative-layout-shift'];
const layoutShiftsAudit = data.audits?.['layout-shifts'];

if (!clsAudit) {
    console.error('❌ Erro: Dados de CLS não encontrados no arquivo');
    process.exit(1);
}

console.log(`\n📊 CLS Total: ${clsAudit.displayValue || clsAudit.numericValue}`);
console.log(`   Score: ${(clsAudit.score * 100).toFixed(0)}/100`);
console.log(`   Meta: <0.1 (${clsAudit.score < 0.1 ? '✅' : '❌'})`);

// Analisar layout shifts individuais
if (layoutShiftsAudit?.details?.items) {
    console.log(`\n📋 Layout Shifts Individuais: ${layoutShiftsAudit.details.items.length} encontrados\n`);
    
    layoutShiftsAudit.details.items.forEach((item, index) => {
        console.log(`\n${'─'.repeat(60)}`);
        console.log(`Shift #${index + 1}:`);
        
        if (item.value !== null && item.value !== undefined) {
            console.log(`  Valor: ${item.value}`);
        }
        
        if (item.node) {
            console.log(`  Elemento:`);
            if (item.node.snippet) {
                console.log(`    HTML: ${item.node.snippet.substring(0, 100)}${item.node.snippet.length > 100 ? '...' : ''}`);
            }
            if (item.node.selector) {
                console.log(`    Seletor: ${item.node.selector}`);
            }
            if (item.node.nodeLabel) {
                console.log(`    Label: ${item.node.nodeLabel}`);
            }
        }
        
        if (item.sources && item.sources.length > 0) {
            console.log(`  Fontes (${item.sources.length}):`);
            item.sources.forEach((source, sIndex) => {
                console.log(`    ${sIndex + 1}. ${source.node?.snippet || source.node?.selector || 'Desconhecido'}`);
                if (source.previousRect) {
                    console.log(`       Antes: ${source.previousRect.width}x${source.previousRect.height} @ (${source.previousRect.x}, ${source.previousRect.y})`);
                }
                if (source.currentRect) {
                    console.log(`       Depois: ${source.currentRect.width}x${source.currentRect.height} @ (${source.currentRect.x}, ${source.currentRect.y})`);
                }
            });
        }
    });
}

// Analisar CLS culprits (se disponível)
const clsCulprits = data.audits?.['cls-culprits-insight'];
if (clsCulprits?.details?.items) {
    console.log(`\n\n🎯 CLS Culprits (Elementos que mais causam CLS):\n`);
    
    clsCulprits.details.items
        .sort((a, b) => (b.score || 0) - (a.score || 0))
        .forEach((item, index) => {
            console.log(`${index + 1}. Score: ${(item.score * 100).toFixed(1)}%`);
            if (item.node?.snippet) {
                console.log(`   Elemento: ${item.node.snippet.substring(0, 80)}${item.node.snippet.length > 80 ? '...' : ''}`);
            }
            if (item.node?.selector) {
                console.log(`   Seletor: ${item.node.selector}`);
            }
        });
}

// Resumo e recomendações
console.log(`\n\n${'='.repeat(60)}`);
console.log('💡 Recomendações:\n');

if (clsAudit.numericValue >= 0.1) {
    console.log('❌ CLS está acima da meta (<0.1)');
    console.log('\nAções recomendadas:');
    console.log('1. Verificar elementos identificados acima');
    console.log('2. Garantir que todas as imagens têm width/height explícitos');
    console.log('3. Usar aspect-ratio CSS para elementos com dimensões dinâmicas');
    console.log('4. Evitar inserir conteúdo acima do conteúdo existente');
    console.log('5. Pre-carregar fontes críticas');
    console.log('6. Usar transform em vez de mudar propriedades de layout');
} else {
    console.log('✅ CLS está dentro da meta!');
}

console.log('\n');

