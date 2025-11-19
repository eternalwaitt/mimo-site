#!/bin/bash

# Script completo para testar todas as páginas usando PageSpeed Insights API
# Gera relatório comparativo com métricas críticas
# Uso: ./build/pagespeed-test-all.sh [API_KEY]

set -e

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# API Key
API_KEY="${1:-${PAGESPEED_API_KEY}}"

if [ -z "$API_KEY" ]; then
    echo -e "${RED}❌ Erro: API Key não fornecida${NC}"
    echo "Uso: $0 [API_KEY]"
    echo "Ou defina a variável de ambiente: export PAGESPEED_API_KEY='sua-chave'"
    exit 1
fi

# Base URL do site
BASE_URL="https://minhamimo.com.br"

# Páginas para testar
PAGES=(
    "/"
    "/contato.php"
    "/vagas.php"
    "/esteticafacial/"
    "/estetica/"
    "/esmalteria/"
    "/salao/"
    "/micropigmentacao/"
    "/cilios/"
)

# Estratégias (mobile e desktop)
STRATEGIES=("mobile" "desktop")

# Diretório para salvar resultados
RESULTS_DIR="pagespeed-results"
mkdir -p "$RESULTS_DIR"

# Timestamp para nomear arquivos
TIMESTAMP=$(date +%Y%m%d-%H%M%S)

echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${BLUE}🚀 PageSpeed Insights - Teste Completo${NC}"
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${CYAN}📊 Testando ${#PAGES[@]} páginas em ${#STRATEGIES[@]} estratégias${NC}"
echo ""

# Contadores
TOTAL_TESTS=$((${#PAGES[@]} * ${#STRATEGIES[@]}))
CURRENT_TEST=0
PASSED=0
FAILED=0

# Arrays para armazenar resultados
declare -a RESULTS

# Função para extrair métrica do JSON
extract_metric() {
    local json="$1"
    local path="$2"
    echo "$json" | grep -o "\"${path}\":[0-9.]*" | head -1 | cut -d':' -f2 || echo "null"
}

# Função para testar uma página
test_page() {
    local url="$1"
    local strategy="$2"
    local full_url="${BASE_URL}${url}"
    
    CURRENT_TEST=$((CURRENT_TEST + 1))
    
    echo -e "${YELLOW}[$CURRENT_TEST/$TOTAL_TESTS]${NC} ${CYAN}${strategy}${NC} → ${BLUE}${full_url}${NC}"
    
    # Nome do arquivo de resultado
    local safe_url=$(echo "$url" | sed 's/[^a-zA-Z0-9]/-/g' | sed 's/--*/-/g' | sed 's/^-//' | sed 's/-$//')
    local result_file="${RESULTS_DIR}/${strategy}--${safe_url}-${TIMESTAMP}.json"
    
    # Fazer requisição à API
    local response=$(curl -s -w "\n%{http_code}" \
        "https://pagespeedonline.googleapis.com/pagespeedonline/v5/runPagespeed?url=${full_url}&strategy=${strategy}&key=${API_KEY}")
    
    local http_code=$(echo "$response" | tail -n1)
    local body=$(echo "$response" | sed '$d')
    
    if [ "$http_code" -eq 200 ]; then
        # Salvar resultado
        echo "$body" > "$result_file"
        
        # Extrair scores usando jq se disponível, senão grep
        if command -v jq &> /dev/null; then
            local perf_score=$(echo "$body" | jq -r '.lighthouseResult.categories.performance.score // "null"' | awk '{if ($1 == "null") print "null"; else printf "%.0f", $1*100}')
            local a11y_score=$(echo "$body" | jq -r '.lighthouseResult.categories.accessibility.score // "null"' | awk '{if ($1 == "null") print "null"; else printf "%.0f", $1*100}')
            local bp_score=$(echo "$body" | jq -r '.lighthouseResult.categories["best-practices"].score // "null"' | awk '{if ($1 == "null") print "null"; else printf "%.0f", $1*100}')
            local seo_score=$(echo "$body" | jq -r '.lighthouseResult.categories.seo.score // "null"' | awk '{if ($1 == "null") print "null"; else printf "%.0f", $1*100}')
            
            # Core Web Vitals
            local fcp=$(echo "$body" | jq -r '.lighthouseResult.audits["first-contentful-paint"].numericValue // "null"' | awk '{if ($1 == "null") print "null"; else printf "%.2f", $1/1000}')
            local lcp=$(echo "$body" | jq -r '.lighthouseResult.audits["largest-contentful-paint"].numericValue // "null"' | awk '{if ($1 == "null") print "null"; else printf "%.2f", $1/1000}')
            local cls=$(echo "$body" | jq -r '.lighthouseResult.audits["cumulative-layout-shift"].numericValue // "null"')
            local tbt=$(echo "$body" | jq -r '.lighthouseResult.audits["total-blocking-time"].numericValue // "null"' | awk '{if ($1 == "null") print "null"; else printf "%.2f", $1/1000}')
            local si=$(echo "$body" | jq -r '.lighthouseResult.audits["speed-index"].numericValue // "null"' | awk '{if ($1 == "null") print "null"; else printf "%.2f", $1/1000}')
        else
            # Fallback sem jq
            local perf_score=$(extract_metric "$body" "score" | head -1 || echo "null")
            local fcp=$(extract_metric "$body" "first-contentful-paint" | awk '{if ($1 == "null") print "null"; else printf "%.2f", $1/1000}')
            local lcp=$(extract_metric "$body" "largest-contentful-paint" | awk '{if ($1 == "null") print "null"; else printf "%.2f", $1/1000}')
            local cls=$(extract_metric "$body" "cumulative-layout-shift" || echo "null")
            local tbt=$(extract_metric "$body" "total-blocking-time" | awk '{if ($1 == "null") print "null"; else printf "%.2f", $1/1000}')
            local si=$(extract_metric "$body" "speed-index" | awk '{if ($1 == "null") print "null"; else printf "%.2f", $1/1000}')
            local a11y_score="null"
            local bp_score="null"
            local seo_score="null"
        fi
        
        # Armazenar resultado
        RESULTS+=("${strategy}|${url}|${perf_score}|${fcp}|${lcp}|${cls}|${tbt}|${si}|${a11y_score}|${bp_score}|${seo_score}")
        
        # Mostrar resultados
        echo -e "  ${GREEN}✅${NC} Perf: ${perf_score} | FCP: ${fcp}s | LCP: ${lcp}s | CLS: ${cls} | TBT: ${tbt}s"
        
        PASSED=$((PASSED + 1))
    else
        echo -e "  ${RED}❌ Erro HTTP $http_code${NC}"
        echo "$body" > "${result_file}.error"
        FAILED=$((FAILED + 1))
    fi
    
    # Rate limiting: aguardar 1 segundo entre requisições
    sleep 1
}

# Testar todas as páginas
for strategy in "${STRATEGIES[@]}"; do
    echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
    echo -e "${BLUE}📱 Estratégia: ${strategy}${NC}"
    echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
    echo ""
    
    for page in "${PAGES[@]}"; do
        test_page "$page" "$strategy"
    done
done

# Gerar relatório consolidado
REPORT_FILE="${RESULTS_DIR}/BASELINE-${TIMESTAMP}.md"
echo "# PageSpeed Insights Baseline - ${TIMESTAMP}" > "$REPORT_FILE"
echo "" >> "$REPORT_FILE"
echo "**Data**: $(date)" >> "$REPORT_FILE"
echo "**Total de Testes**: ${TOTAL_TESTS}" >> "$REPORT_FILE"
echo "**Sucessos**: ${PASSED}" >> "$REPORT_FILE"
echo "**Falhas**: ${FAILED}" >> "$REPORT_FILE"
echo "" >> "$REPORT_FILE"

# Tabela de resultados
echo "## Resultados Detalhados" >> "$REPORT_FILE"
echo "" >> "$REPORT_FILE"
echo "| Estratégia | Página | Performance | FCP (s) | LCP (s) | CLS | TBT (s) | SI (s) | A11y | BP | SEO |" >> "$REPORT_FILE"
echo "|------------|--------|-------------|---------|---------|-----|---------|--------|------|----|-----|" >> "$REPORT_FILE"

for result in "${RESULTS[@]}"; do
    IFS='|' read -r strategy url perf fcp lcp cls tbt si a11y bp seo <<< "$result"
    echo "| ${strategy} | ${url} | ${perf} | ${fcp} | ${lcp} | ${cls} | ${tbt} | ${si} | ${a11y} | ${bp} | ${seo} |" >> "$REPORT_FILE"
done

# Estatísticas
echo "" >> "$REPORT_FILE"
echo "## Estatísticas" >> "$REPORT_FILE"
echo "" >> "$REPORT_FILE"

# Calcular médias mobile
mobile_count=0
mobile_perf_sum=0
mobile_fcp_sum=0
mobile_lcp_sum=0
mobile_cls_sum=0

for result in "${RESULTS[@]}"; do
    IFS='|' read -r strategy url perf fcp lcp cls tbt si a11y bp seo <<< "$result"
    if [ "$strategy" = "mobile" ] && [ "$perf" != "null" ]; then
        mobile_count=$((mobile_count + 1))
        mobile_perf_sum=$((mobile_perf_sum + perf))
        if [ "$fcp" != "null" ]; then
            mobile_fcp_sum=$(awk "BEGIN {printf \"%.2f\", $mobile_fcp_sum + $fcp}")
        fi
        if [ "$lcp" != "null" ]; then
            mobile_lcp_sum=$(awk "BEGIN {printf \"%.2f\", $mobile_lcp_sum + $lcp}")
        fi
        if [ "$cls" != "null" ]; then
            mobile_cls_sum=$(awk "BEGIN {printf \"%.2f\", $mobile_cls_sum + $cls}")
        fi
    fi
done

if [ $mobile_count -gt 0 ]; then
    mobile_perf_avg=$((mobile_perf_sum / mobile_count))
    mobile_fcp_avg=$(awk "BEGIN {printf \"%.2f\", $mobile_fcp_sum / $mobile_count}")
    mobile_lcp_avg=$(awk "BEGIN {printf \"%.2f\", $mobile_lcp_sum / $mobile_count}")
    mobile_cls_avg=$(awk "BEGIN {printf \"%.2f\", $mobile_cls_sum / $mobile_count}")
    
    echo "### Mobile (${mobile_count} páginas)" >> "$REPORT_FILE"
    echo "- **Performance Média**: ${mobile_perf_avg}" >> "$REPORT_FILE"
    echo "- **FCP Média**: ${mobile_fcp_avg}s" >> "$REPORT_FILE"
    echo "- **LCP Média**: ${mobile_lcp_avg}s" >> "$REPORT_FILE"
    echo "- **CLS Média**: ${mobile_cls_avg}" >> "$REPORT_FILE"
    echo "" >> "$REPORT_FILE"
fi

# Calcular médias desktop
desktop_count=0
desktop_perf_sum=0
desktop_fcp_sum=0
desktop_lcp_sum=0
desktop_cls_sum=0

for result in "${RESULTS[@]}"; do
    IFS='|' read -r strategy url perf fcp lcp cls tbt si a11y bp seo <<< "$result"
    if [ "$strategy" = "desktop" ] && [ "$perf" != "null" ]; then
        desktop_count=$((desktop_count + 1))
        desktop_perf_sum=$((desktop_sum + perf))
        if [ "$fcp" != "null" ]; then
            desktop_fcp_sum=$(awk "BEGIN {printf \"%.2f\", $desktop_fcp_sum + $fcp}")
        fi
        if [ "$lcp" != "null" ]; then
            desktop_lcp_sum=$(awk "BEGIN {printf \"%.2f\", $desktop_lcp_sum + $lcp}")
        fi
        if [ "$cls" != "null" ]; then
            desktop_cls_sum=$(awk "BEGIN {printf \"%.2f\", $desktop_cls_sum + $cls}")
        fi
    fi
done

if [ $desktop_count -gt 0 ]; then
    desktop_perf_avg=$((desktop_perf_sum / desktop_count))
    desktop_fcp_avg=$(awk "BEGIN {printf \"%.2f\", $desktop_fcp_sum / $desktop_count}")
    desktop_lcp_avg=$(awk "BEGIN {printf \"%.2f\", $desktop_lcp_sum / $desktop_count}")
    desktop_cls_avg=$(awk "BEGIN {printf \"%.2f\", $desktop_cls_sum / $desktop_count}")
    
    echo "### Desktop (${desktop_count} páginas)" >> "$REPORT_FILE"
    echo "- **Performance Média**: ${desktop_perf_avg}" >> "$REPORT_FILE"
    echo "- **FCP Média**: ${desktop_fcp_avg}s" >> "$REPORT_FILE"
    echo "- **LCP Média**: ${desktop_lcp_avg}s" >> "$REPORT_FILE"
    echo "- **CLS Média**: ${desktop_cls_avg}" >> "$REPORT_FILE"
    echo "" >> "$REPORT_FILE"
fi

echo "## Arquivos de Resultado" >> "$REPORT_FILE"
echo "" >> "$REPORT_FILE"
echo "Todos os resultados JSON estão em: \`${RESULTS_DIR}/\`" >> "$REPORT_FILE"
echo "" >> "$REPORT_FILE"
echo "Para analisar os resultados:" >> "$REPORT_FILE"
echo "\`\`\`bash" >> "$REPORT_FILE"
echo "cat ${RESULTS_DIR}/*.json | jq '.lighthouseResult.categories'" >> "$REPORT_FILE"
echo "\`\`\`" >> "$REPORT_FILE"

# Resumo final
echo ""
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${BLUE}📊 Resumo Final${NC}"
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "Total de testes: ${TOTAL_TESTS}"
echo -e "${GREEN}✅ Sucessos: ${PASSED}${NC}"
echo -e "${RED}❌ Falhas: ${FAILED}${NC}"
echo -e "${CYAN}📄 Relatório consolidado: ${REPORT_FILE}${NC}"
echo ""

