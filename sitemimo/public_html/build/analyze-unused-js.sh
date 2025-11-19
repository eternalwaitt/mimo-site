#!/bin/bash

# Script para analisar JavaScript não utilizado
# Usa webpack-bundle-analyzer ou source-map-explorer

echo "🔍 Analisando JavaScript não utilizado..."

# Verificar se temos ferramentas disponíveis
if command -v npx &> /dev/null; then
    echo "✅ npx disponível"
    
    # Listar todos os arquivos JS
    echo ""
    echo "📋 Arquivos JavaScript encontrados:"
    find ../ -name "*.js" -not -path "*/node_modules/*" -not -path "*/bootstrap/*" -not -path "*/minified/*" | head -20
    
    echo ""
    echo "📊 Tamanho dos arquivos JS principais:"
    du -h ../main.js ../js/*.js ../form/main.js 2>/dev/null | sort -h
    
    echo ""
    echo "💡 Análise manual necessária:"
    echo "   - Verificar uso de jQuery plugins"
    echo "   - Verificar uso de bc-swipe.js"
    echo "   - Verificar uso de jquery.touchswipe"
    echo "   - Verificar código morto em main.js"
    
else
    echo "⚠️ npx não encontrado. Análise manual necessária."
fi

echo ""
echo "✅ Análise concluída"

