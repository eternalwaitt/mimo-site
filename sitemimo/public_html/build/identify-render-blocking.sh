#!/bin/bash

# Script para identificar recursos render-blocking restantes
# Analisa HTML e identifica CSS/JS que podem estar bloqueando renderização

echo "🔍 Identificando recursos render-blocking restantes..."
echo ""

# Arquivos PHP para analisar
FILES=(
    "../index.php"
    "../contato.php"
    "../vagas.php"
    "../inc/service-template.php"
)

TOTAL_BLOCKING=0

for FILE in "${FILES[@]}"; do
    if [[ ! -f "$FILE" ]]; then
        continue
    fi
    
    echo "📄 Analisando: $FILE"
    
    # Buscar <link rel="stylesheet"> sem defer/async
    BLOCKING_CSS=$(grep -E '<link[^>]*rel=.*stylesheet' "$FILE" | grep -v 'media="print"' | grep -v 'onload=' | wc -l | tr -d ' ')
    
    if [[ $BLOCKING_CSS -gt 0 ]]; then
        echo "   ⚠️  CSS render-blocking encontrado: $BLOCKING_CSS"
        grep -E '<link[^>]*rel=.*stylesheet' "$FILE" | grep -v 'media="print"' | grep -v 'onload=' | head -5 | while read -r LINE; do
            echo "      - $(echo "$LINE" | cut -c1-80)..."
        done
        TOTAL_BLOCKING=$((TOTAL_BLOCKING + BLOCKING_CSS))
    fi
    
    # Buscar <script> sem defer/async (exceto loadCSS)
    BLOCKING_JS=$(grep -E '<script[^>]*src=' "$FILE" | grep -v 'defer' | grep -v 'async' | grep -v 'loadcss' | wc -l | tr -d ' ')
    
    if [[ $BLOCKING_JS -gt 0 ]]; then
        echo "   ⚠️  JS render-blocking encontrado: $BLOCKING_JS"
        grep -E '<script[^>]*src=' "$FILE" | grep -v 'defer' | grep -v 'async' | grep -v 'loadcss' | head -5 | while read -r LINE; do
            echo "      - $(echo "$LINE" | cut -c1-80)..."
        done
        TOTAL_BLOCKING=$((TOTAL_BLOCKING + BLOCKING_JS))
    fi
    
    echo ""
done

echo "📊 Total de recursos render-blocking encontrados: $TOTAL_BLOCKING"
echo ""
echo "💡 Recomendações:"
echo "   - CSS: Usar media='print' onload='this.media=\"all\"' ou loadCSS()"
echo "   - JS: Adicionar defer ou async quando possível"
echo "   - Fonts: Já estão com font-display: swap?"
