#!/bin/bash

# Script para testar PageSpeed localmente usando Lighthouse
# Funciona com servidor PHP local (localhost:8000)
#
# Uso: ./build/pagespeed-test-local.sh
# Requer: Node.js e npx (vem com Node.js)

set -e

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Verificar se Node.js está instalado
if ! command -v node &> /dev/null; then
    echo -e "${RED}❌ Erro: Node.js não encontrado${NC}"
    echo "Por favor, instale Node.js: https://nodejs.org/"
    exit 1
fi

# Verificar se npx está disponível
if ! command -v npx &> /dev/null; then
    echo -e "${RED}❌ Erro: npx não encontrado${NC}"
    echo "npx vem com Node.js. Por favor, instale Node.js: https://nodejs.org/"
    exit 1
fi

# Diretório base
cd "$(dirname "$0")/.." || exit 1
BASE_DIR=$(pwd)

# URL base local
BASE_URL="http://localhost:8000"

# Páginas para testar
PAGES=(
    "/"
    "/contato.php"
    "/vagas.php"
)

# Estratégias (mobile e desktop)
STRATEGIES=("mobile" "desktop")

# Diretório para salvar resultados
RESULTS_DIR="pagespeed-results"
mkdir -p "$RESULTS_DIR"

# Timestamp para nomear arquivos
TIMESTAMP=$(date +%Y%m%d-%H%M%S)

# PID do servidor PHP
PHP_SERVER_PID=""

# Função para iniciar servidor PHP
start_php_server() {
    echo -e "${BLUE}🚀 Iniciando servidor PHP local...${NC}"
    cd "$BASE_DIR" || exit 1
    
    # Verificar se porta 8000 já está em uso
    if lsof -ti:8000 &> /dev/null; then
        echo -e "${YELLOW}⚠️  Porta 8000 já está em uso. Tentando usar servidor existente...${NC}"
        # Verificar se é um servidor PHP
        if curl -s http://localhost:8000 > /dev/null 2>&1; then
            echo -e "${GREEN}✅ Servidor PHP já está rodando${NC}"
            return 0
        else
            echo -e "${RED}❌ Porta 8000 está em uso mas não é um servidor PHP${NC}"
            exit 1
        fi
    fi
    
    # Iniciar servidor PHP em background
    php -S localhost:8000 > /dev/null 2>&1 &
    PHP_SERVER_PID=$!
    
    # Aguardar servidor iniciar
    echo -e "${YELLOW}⏳ Aguardando servidor iniciar...${NC}"
    sleep 2
    
    # Verificar se servidor está rodando
    if ! curl -s http://localhost:8000 > /dev/null 2>&1; then
        echo -e "${RED}❌ Erro: Não foi possível iniciar o servidor PHP${NC}"
        exit 1
    fi
    
    echo -e "${GREEN}✅ Servidor PHP iniciado (PID: $PHP_SERVER_PID)${NC}"
}

# Função para parar servidor PHP
stop_php_server() {
    if [ -n "$PHP_SERVER_PID" ]; then
        echo -e "${YELLOW}🛑 Parando servidor PHP...${NC}"
        kill $PHP_SERVER_PID 2>/dev/null || true
        wait $PHP_SERVER_PID 2>/dev/null || true
        echo -e "${GREEN}✅ Servidor PHP parado${NC}"
    fi
}

# Trap para garantir que servidor seja parado ao sair
trap stop_php_server EXIT INT TERM

# Função para testar uma página
test_page() {
    local url="$1"
    local strategy="$2"
    local full_url="${BASE_URL}${url}"
    
    echo -e "${YELLOW}📊 Testando: ${BLUE}${full_url}${NC} (${strategy})"
    
    # Nome do arquivo de resultado
    local safe_url=$(echo "$url" | sed 's/[^a-zA-Z0-9]/-/g' | sed 's/--*/-/g' | sed 's/^-//' | sed 's/-$//')
    if [ -z "$safe_url" ]; then
        safe_url="homepage"
    fi
    local result_file="${RESULTS_DIR}/local-${strategy}--${safe_url}-${TIMESTAMP}.json"
    
    # Executar Lighthouse
    local lighthouse_cmd="npx lighthouse \"$full_url\" \
        --only-categories=performance \
        --preset=${strategy} \
        --output=json \
        --output-path=\"$result_file\" \
        --chrome-flags=\"--headless --no-sandbox\" \
        --quiet"
    
    if eval "$lighthouse_cmd" > /dev/null 2>&1; then
        # Extrair métricas
        if command -v jq &> /dev/null; then
            local perf_score=$(jq -r '.categories.performance.score // "null"' "$result_file" | awk '{if ($1 == "null") print "null"; else printf "%.0f", $1*100}')
            local fcp=$(jq -r '.audits["first-contentful-paint"].numericValue // "null"' "$result_file" | awk '{if ($1 == "null") print "null"; else printf "%.2f", $1/1000}')
            local lcp=$(jq -r '.audits["largest-contentful-paint"].numericValue // "null"' "$result_file" | awk '{if ($1 == "null") print "null"; else printf "%.2f", $1/1000}')
            local cls=$(jq -r '.audits["cumulative-layout-shift"].numericValue // "null"' "$result_file")
            local tbt=$(jq -r '.audits["total-blocking-time"].numericValue // "null"' "$result_file" | awk '{if ($1 == "null") print "null"; else printf "%.2f", $1/1000}')
            local si=$(jq -r '.audits["speed-index"].numericValue // "null"' "$result_file" | awk '{if ($1 == "null") print "null"; else printf "%.2f", $1/1000}')
            
            echo -e "${GREEN}✅${NC} Performance: ${GREEN}${perf_score}${NC} | FCP: ${fcp}s | LCP: ${lcp}s | CLS: ${cls} | TBT: ${tbt}s | SI: ${si}s"
        else
            echo -e "${GREEN}✅${NC} Resultado salvo em: ${BLUE}${result_file}${NC}"
        fi
        return 0
    else
        echo -e "${RED}❌${NC} Erro ao testar ${full_url}"
        return 1
    fi
}

# Iniciar servidor PHP
start_php_server

echo ""
echo -e "${BLUE}🚀 Iniciando testes do Lighthouse local${NC}"
echo -e "${BLUE}📊 Testando ${#PAGES[@]} páginas em ${#STRATEGIES[@]} estratégias${NC}"
echo ""

# Contadores
TOTAL_TESTS=$((${#PAGES[@]} * ${#STRATEGIES[@]}))
CURRENT_TEST=0
PASSED=0
FAILED=0

# Testar cada página em cada estratégia
for strategy in "${STRATEGIES[@]}"; do
    for page in "${PAGES[@]}"; do
        CURRENT_TEST=$((CURRENT_TEST + 1))
        if test_page "$page" "$strategy"; then
            PASSED=$((PASSED + 1))
        else
            FAILED=$((FAILED + 1))
        fi
        echo ""
    done
done

# Resumo
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${BLUE}📊 Resumo dos Testes${NC}"
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "Total: ${TOTAL_TESTS} testes"
echo -e "${GREEN}✅ Passou: ${PASSED}${NC}"
if [ $FAILED -gt 0 ]; then
    echo -e "${RED}❌ Falhou: ${FAILED}${NC}"
fi
echo -e "Resultados salvos em: ${BLUE}${RESULTS_DIR}/${NC}"
echo ""

# Gerar relatório consolidado se jq estiver disponível
if command -v jq &> /dev/null; then
    echo -e "${BLUE}📝 Gerando relatório consolidado...${NC}"
    REPORT_FILE="${RESULTS_DIR}/local-report-${TIMESTAMP}.md"
    
    {
        echo "# Relatório PageSpeed Local - $(date '+%Y-%m-%d %H:%M:%S')"
        echo ""
        echo "## Resumo"
        echo ""
        echo "| Página | Estratégia | Performance | FCP | LCP | CLS | TBT | SI |"
        echo "|--------|-----------|-------------|-----|-----|-----|-----|-----|"
        
        for strategy in "${STRATEGIES[@]}"; do
            for page in "${PAGES[@]}"; do
                safe_url=$(echo "$page" | sed 's/[^a-zA-Z0-9]/-/g' | sed 's/--*/-/g' | sed 's/^-//' | sed 's/-$//')
                if [ -z "$safe_url" ]; then
                    safe_url="homepage"
                fi
                result_file="${RESULTS_DIR}/local-${strategy}--${safe_url}-${TIMESTAMP}.json"
                
                if [ -f "$result_file" ]; then
                    perf_score=$(jq -r '.categories.performance.score // "null"' "$result_file" | awk '{if ($1 == "null") print "null"; else printf "%.0f", $1*100}')
                    fcp=$(jq -r '.audits["first-contentful-paint"].numericValue // "null"' "$result_file" | awk '{if ($1 == "null") print "null"; else printf "%.2f", $1/1000}')
                    lcp=$(jq -r '.audits["largest-contentful-paint"].numericValue // "null"' "$result_file" | awk '{if ($1 == "null") print "null"; else printf "%.2f", $1/1000}')
                    cls=$(jq -r '.audits["cumulative-layout-shift"].numericValue // "null"' "$result_file")
                    tbt=$(jq -r '.audits["total-blocking-time"].numericValue // "null"' "$result_file" | awk '{if ($1 == "null") print "null"; else printf "%.2f", $1/1000}')
                    si=$(jq -r '.audits["speed-index"].numericValue // "null"' "$result_file" | awk '{if ($1 == "null") print "null"; else printf "%.2f", $1/1000}')
                    
                    echo "| $page | $strategy | $perf_score | ${fcp}s | ${lcp}s | $cls | ${tbt}s | ${si}s |"
                fi
            done
        done
        
        echo ""
        echo "## Arquivos de Resultado"
        echo ""
        echo "Todos os resultados JSON estão em: \`${RESULTS_DIR}/\`"
        echo ""
        echo "Para visualizar um resultado específico:"
        echo "\`\`\`bash"
        echo "cat ${RESULTS_DIR}/local-<strategy>--<page>-${TIMESTAMP}.json | jq"
        echo "\`\`\`"
    } > "$REPORT_FILE"
    
    echo -e "${GREEN}✅ Relatório gerado: ${BLUE}${REPORT_FILE}${NC}"
fi

echo ""
echo -e "${GREEN}✅ Testes concluídos!${NC}"

