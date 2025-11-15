#!/bin/bash

# Script para analisar resultados do PageSpeed Insights e gerar relatório de correções
# Uso: ./build/pagespeed-analyze.sh [diretório-de-resultados]

set -e

# Cores
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

RESULTS_DIR="${1:-pagespeed-results}"

if [ ! -d "$RESULTS_DIR" ]; then
    echo -e "${RED}❌ Diretório não encontrado: ${RESULTS_DIR}${NC}"
    exit 1
fi

echo -e "${BLUE}📊 Analisando resultados do PageSpeed Insights${NC}"
echo ""

# Verificar se jq está instalado
if ! command -v jq &> /dev/null; then
    echo -e "${YELLOW}⚠️  jq não está instalado. Instalando análise básica...${NC}"
    USE_JQ=false
else
    USE_JQ=true
fi

# Arquivo de relatório
REPORT_FILE="${RESULTS_DIR}/analysis-$(date +%Y%m%d-%H%M%S).md"
echo "# Análise PageSpeed Insights - $(date +'%Y-%m-%d %H:%M:%S')" > "$REPORT_FILE"
echo "" >> "$REPORT_FILE"

# Processar cada arquivo JSON
for json_file in "$RESULTS_DIR"/*.json; do
    if [ ! -f "$json_file" ]; then
        continue
    fi
    
    filename=$(basename "$json_file")
    echo -e "${BLUE}📄 Processando: ${filename}${NC}"
    
    if [ "$USE_JQ" = true ]; then
        # Extrair dados com jq
        perf_score=$(jq -r '.lighthouseResult.categories.performance.score // "null"' "$json_file" 2>/dev/null || echo "null")
        a11y_score=$(jq -r '.lighthouseResult.categories.accessibility.score // "null"' "$json_file" 2>/dev/null || echo "null")
        bp_score=$(jq -r '.lighthouseResult.categories["best-practices"].score // "null"' "$json_file" 2>/dev/null || echo "null")
        seo_score=$(jq -r '.lighthouseResult.categories.seo.score // "null"' "$json_file" 2>/dev/null || echo "null")
        
        # Core Web Vitals
        fcp=$(jq -r '.lighthouseResult.audits["first-contentful-paint"].numericValue // "null"' "$json_file" 2>/dev/null || echo "null")
        lcp=$(jq -r '.lighthouseResult.audits["largest-contentful-paint"].numericValue // "null"' "$json_file" 2>/dev/null || echo "null")
        cls=$(jq -r '.lighthouseResult.audits["cumulative-layout-shift"].numericValue // "null"' "$json_file" 2>/dev/null || echo "null")
        tbt=$(jq -r '.lighthouseResult.audits["total-blocking-time"].numericValue // "null"' "$json_file" 2>/dev/null || echo "null")
        
        # Converter para segundos
        if [ "$fcp" != "null" ] && [ -n "$fcp" ] && [ "$fcp" != "" ]; then
            fcp=$(awk "BEGIN {printf \"%.2f\", $fcp/1000}")
        fi
        if [ "$lcp" != "null" ] && [ -n "$lcp" ] && [ "$lcp" != "" ]; then
            lcp=$(awk "BEGIN {printf \"%.2f\", $lcp/1000}")
        fi
        if [ "$tbt" != "null" ] && [ -n "$tbt" ] && [ "$tbt" != "" ]; then
            tbt=$(awk "BEGIN {printf \"%.2f\", $tbt/1000}")
        fi
        
        # Adicionar ao relatório
        echo "## $filename" >> "$REPORT_FILE"
        echo "" >> "$REPORT_FILE"
        echo "### Scores" >> "$REPORT_FILE"
        echo "- Performance: $perf_score" >> "$REPORT_FILE"
        echo "- Accessibility: $a11y_score" >> "$REPORT_FILE"
        echo "- Best Practices: $bp_score" >> "$REPORT_FILE"
        echo "- SEO: $seo_score" >> "$REPORT_FILE"
        echo "" >> "$REPORT_FILE"
        echo "### Core Web Vitals" >> "$REPORT_FILE"
        echo "- FCP: ${fcp}s" >> "$REPORT_FILE"
        echo "- LCP: ${lcp}s" >> "$REPORT_FILE"
        echo "- CLS: $cls" >> "$REPORT_FILE"
        echo "- TBT: ${tbt}s" >> "$REPORT_FILE"
        echo "" >> "$REPORT_FILE"
        
        # Extrair oportunidades de melhoria
        echo "### Oportunidades de Melhoria" >> "$REPORT_FILE"
        jq -r '.lighthouseResult.audits | to_entries[] | select(.value.score != null and .value.score < 1) | "- \(.key): \(.value.title) (score: \(.value.score))"' "$json_file" >> "$REPORT_FILE" 2>/dev/null || echo "- Nenhuma oportunidade encontrada" >> "$REPORT_FILE"
        echo "" >> "$REPORT_FILE"
    else
        echo "## $filename" >> "$REPORT_FILE"
        echo "" >> "$REPORT_FILE"
        echo "Arquivo JSON encontrado. Use 'jq' para análise detalhada:" >> "$REPORT_FILE"
        echo "\`\`\`bash" >> "$REPORT_FILE"
        echo "jq '.lighthouseResult.categories' \"$json_file\"" >> "$REPORT_FILE"
        echo "\`\`\`" >> "$REPORT_FILE"
        echo "" >> "$REPORT_FILE"
    fi
done

echo -e "${GREEN}✅ Análise concluída${NC}"
echo -e "${GREEN}📄 Relatório salvo em: ${REPORT_FILE}${NC}"

