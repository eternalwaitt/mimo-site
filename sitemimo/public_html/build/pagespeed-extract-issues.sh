#!/bin/bash

# Script para extrair todas as oportunidades de melhoria dos resultados do PageSpeed
# Uso: ./build/pagespeed-extract-issues.sh [diretório-de-resultados]

set -e

RESULTS_DIR="${1:-pagespeed-results}"

if [ ! -d "$RESULTS_DIR" ]; then
    echo "❌ Diretório não encontrado: $RESULTS_DIR"
    exit 1
fi

# Verificar se jq está instalado
if ! command -v jq &> /dev/null; then
    echo "⚠️  jq não está instalado. Instalando análise básica..."
    USE_JQ=false
else
    USE_JQ=true
fi

ISSUES_FILE="${RESULTS_DIR}/all-issues-$(date +%Y%m%d-%H%M%S).md"
echo "# Todas as Oportunidades de Melhoria - PageSpeed Insights" > "$ISSUES_FILE"
echo "" >> "$ISSUES_FILE"
echo "**Data**: $(date +'%Y-%m-%d %H:%M:%S')" >> "$ISSUES_FILE"
echo "" >> "$ISSUES_FILE"

# Categorias (usando arrays associativos compatíveis com bash 3+)
PERFORMANCE_ISSUES=()
ACCESSIBILITY_ISSUES=()
BEST_PRACTICES_ISSUES=()
SEO_ISSUES=()

if [ "$USE_JQ" = true ]; then
    echo "📊 Extraindo problemas de todos os resultados..."
    
    for json_file in "$RESULTS_DIR"/*.json; do
        if [ ! -f "$json_file" ]; then
            continue
        fi
        
        filename=$(basename "$json_file" .json)
        echo "  Processando: $filename"
        
        # Extrair todas as oportunidades
        jq -r '.lighthouseResult.audits | to_entries[] | select(.value.score != null and .value.score < 1) | "\(.key)|\(.value.title)|\(.value.description // "Sem descrição")|\(.value.score)|\(.value.numericValue // 0)|\(.value.displayValue // "")"' "$json_file" 2>/dev/null | while IFS='|' read -r id title description score numericValue displayValue; do
            # Determinar categoria baseado no ID
            category=""
            if [[ "$id" =~ ^(first-contentful-paint|largest-contentful-paint|total-blocking-time|speed-index|render-blocking-resources|unused-css-rules|unused-javascript|uses-optimized-images|modern-image-formats|font-display|uses-text-compression|uses-responsive-images|offscreen-images|unminified-css|unminified-javascript|uses-long-cache-ttl|efficient-animated-content) ]]; then
                category="PERFORMANCE"
            elif [[ "$id" =~ ^(aria-|color-contrast|heading-order|image-alt|button-name|link-name|html-has-lang|html-lang-valid) ]]; then
                category="ACCESSIBILITY"
            elif [[ "$id" =~ ^(is-on-https|uses-http2|no-vulnerable-libraries|deprecations|console-errors|errors-in-console) ]]; then
                category="BEST_PRACTICES"
            elif [[ "$id" =~ ^(meta-description|document-title|link-text|hreflang|canonical|structured-data) ]]; then
                category="SEO"
            else
                category="OTHER"
            fi
            
            # Adicionar ao arquivo
            echo "## $title" >> "$ISSUES_FILE"
            echo "" >> "$ISSUES_FILE"
            echo "- **ID**: \`$id\`" >> "$ISSUES_FILE"
            echo "- **Página**: $filename" >> "$ISSUES_FILE"
            echo "- **Score**: $score" >> "$ISSUES_FILE"
            echo "- **Categoria**: $category" >> "$ISSUES_FILE"
            if [ "$numericValue" != "0" ] && [ "$numericValue" != "null" ]; then
                echo "- **Valor**: $numericValue" >> "$ISSUES_FILE"
            fi
            if [ -n "$displayValue" ] && [ "$displayValue" != "null" ]; then
                echo "- **Display**: $displayValue" >> "$ISSUES_FILE"
            fi
            echo "- **Descrição**: $description" >> "$ISSUES_FILE"
            echo "" >> "$ISSUES_FILE"
        done
    done
else
    echo "⚠️  jq não está disponível. Use 'brew install jq' ou 'apt-get install jq'"
    echo "Arquivos JSON estão em: $RESULTS_DIR/"
fi

echo "✅ Problemas extraídos e salvos em: $ISSUES_FILE"

