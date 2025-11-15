#!/bin/bash

# Script para testar todas as páginas usando a API do PageSpeed Insights
# Baseado em: https://developers.google.com/speed/docs/insights/rest/v5/pagespeedapi/runpagespeed
#
# Uso: ./build/pagespeed-api-test.sh [API_KEY]
# Se API_KEY não for fornecido, tentará usar variável de ambiente PAGESPEED_API_KEY

set -e

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# API Key
API_KEY="${1:-${PAGESPEED_API_KEY}}"

if [ -z "$API_KEY" ]; then
    echo -e "${RED}❌ Erro: API Key não fornecida${NC}"
    echo "Uso: $0 [API_KEY]"
    echo "Ou defina a variável de ambiente: export PAGESPEED_API_KEY='sua-chave'"
    echo ""
    echo "Para obter uma API Key:"
    echo "1. Acesse: https://console.cloud.google.com/apis/credentials"
    echo "2. Crie uma nova chave de API"
    echo "3. Habilite a API 'PageSpeed Insights API'"
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

echo -e "${BLUE}🚀 Iniciando testes do PageSpeed Insights API${NC}"
echo -e "${BLUE}📊 Testando ${#PAGES[@]} páginas em ${#STRATEGIES[@]} estratégias${NC}"
echo ""

# Contadores
TOTAL_TESTS=$((${#PAGES[@]} * ${#STRATEGIES[@]}))
CURRENT_TEST=0
PASSED=0
FAILED=0

# Função para testar uma página
test_page() {
    local url="$1"
    local strategy="$2"
    local full_url="${BASE_URL}${url}"
    
    CURRENT_TEST=$((CURRENT_TEST + 1))
    
    echo -e "${YELLOW}[$CURRENT_TEST/$TOTAL_TESTS]${NC} Testando: ${BLUE}${full_url}${NC} (${strategy})"
    
    # Nome do arquivo de resultado
    local safe_url=$(echo "$url" | sed 's/[^a-zA-Z0-9]/-/g' | sed 's/--*/-/g')
    local result_file="${RESULTS_DIR}/${strategy}-${safe_url}-${TIMESTAMP}.json"
    
    # Fazer requisição à API
    local response=$(curl -s -w "\n%{http_code}" \
        "https://pagespeedonline.googleapis.com/pagespeedonline/v5/runPagespeed?url=${full_url}&strategy=${strategy}&key=${API_KEY}")
    
    local http_code=$(echo "$response" | tail -n1)
    local body=$(echo "$response" | sed '$d')
    
    if [ "$http_code" -eq 200 ]; then
        # Salvar resultado
        echo "$body" > "$result_file"
        
        # Extrair scores
        local perf_score=$(echo "$body" | grep -o '"score":[0-9.]*' | head -1 | cut -d':' -f2 || echo "null")
        local a11y_score=$(echo "$body" | grep -o '"score":[0-9.]*' | sed -n '2p' | cut -d':' -f2 || echo "null")
        local bp_score=$(echo "$body" | grep -o '"score":[0-9.]*' | sed -n '3p' | cut -d':' -f2 || echo "null")
        local seo_score=$(echo "$body" | grep -o '"score":[0-9.]*' | sed -n '4p' | cut -d':' -f2 || echo "null")
        
        # Extrair Core Web Vitals
        local fcp=$(echo "$body" | grep -o '"first-contentful-paint":[0-9.]*' | cut -d':' -f2 || echo "null")
        local lcp=$(echo "$body" | grep -o '"largest-contentful-paint":[0-9.]*' | cut -d':' -f2 || echo "null")
        local cls=$(echo "$body" | grep -o '"cumulative-layout-shift":[0-9.]*' | cut -d':' -f2 || echo "null")
        local tbt=$(echo "$body" | grep -o '"total-blocking-time":[0-9.]*' | cut -d':' -f2 || echo "null")
        
        # Converter para segundos (se não for null)
        if [ "$fcp" != "null" ] && [ -n "$fcp" ] && [ "$fcp" != "" ]; then
            fcp=$(awk "BEGIN {printf \"%.2f\", $fcp/1000}")
        fi
        if [ "$lcp" != "null" ] && [ -n "$lcp" ] && [ "$lcp" != "" ]; then
            lcp=$(awk "BEGIN {printf \"%.2f\", $lcp/1000}")
        fi
        if [ "$tbt" != "null" ] && [ -n "$tbt" ] && [ "$tbt" != "" ]; then
            tbt=$(awk "BEGIN {printf \"%.2f\", $tbt/1000}")
        fi
        
        # Mostrar resultados
        echo -e "  ${GREEN}✅ Sucesso${NC}"
        echo -e "  📊 Performance: ${perf_score}"
        echo -e "  ♿ Accessibility: ${a11y_score}"
        echo -e "  ✅ Best Practices: ${bp_score}"
        echo -e "  🔍 SEO: ${seo_score}"
        echo -e "  ⚡ FCP: ${fcp}s | LCP: ${lcp}s | CLS: ${cls} | TBT: ${tbt}s"
        echo -e "  💾 Salvo em: ${result_file}"
        
        PASSED=$((PASSED + 1))
    else
        echo -e "  ${RED}❌ Erro HTTP $http_code${NC}"
        echo "$body" > "${result_file}.error"
        FAILED=$((FAILED + 1))
    fi
    
    echo ""
    
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

# Resumo final
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${BLUE}📊 Resumo Final${NC}"
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "Total de testes: ${TOTAL_TESTS}"
echo -e "${GREEN}✅ Sucessos: ${PASSED}${NC}"
echo -e "${RED}❌ Falhas: ${FAILED}${NC}"
echo -e "Resultados salvos em: ${RESULTS_DIR}/"
echo ""

# Gerar relatório consolidado
REPORT_FILE="${RESULTS_DIR}/report-${TIMESTAMP}.md"
echo "# Relatório PageSpeed Insights - ${TIMESTAMP}" > "$REPORT_FILE"
echo "" >> "$REPORT_FILE"
echo "## Resumo" >> "$REPORT_FILE"
echo "- Total de testes: ${TOTAL_TESTS}" >> "$REPORT_FILE"
echo "- Sucessos: ${PASSED}" >> "$REPORT_FILE"
echo "- Falhas: ${FAILED}" >> "$REPORT_FILE"
echo "" >> "$REPORT_FILE"
echo "## Arquivos de Resultado" >> "$REPORT_FILE"
echo "Todos os resultados JSON estão em: \`${RESULTS_DIR}/\`" >> "$REPORT_FILE"
echo "" >> "$REPORT_FILE"
echo "Para analisar os resultados, use:" >> "$REPORT_FILE"
echo "\`\`\`bash" >> "$REPORT_FILE"
echo "cat ${RESULTS_DIR}/*.json | jq '.lighthouseResult.categories'" >> "$REPORT_FILE"
echo "\`\`\`" >> "$REPORT_FILE"

echo -e "${GREEN}📄 Relatório consolidado salvo em: ${REPORT_FILE}${NC}"

