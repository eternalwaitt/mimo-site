#!/bin/bash

# Script para validar TODAS as fases de otimização localmente
# Testa FASE 1 (CLS), FASE 2 (LCP), FASE 3 (FCP) e gera relatório completo
#
# Uso: ./build/validate-all-phases.sh
# Requer: Node.js, npx, jq (opcional mas recomendado)

set -e

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Diretório base
cd "$(dirname "$0")/.." || exit 1
BASE_DIR=$(pwd)

# URL base local
BASE_URL="http://localhost:8000"

# Página principal para testar
TEST_PAGE="/"

# Diretório para salvar resultados
RESULTS_DIR="pagespeed-results"
mkdir -p "$RESULTS_DIR"

# Timestamp para nomear arquivos
TIMESTAMP=$(date +%Y%m%d-%H%M%S)
VALIDATION_FILE="${RESULTS_DIR}/validation-all-phases-${TIMESTAMP}.json"
REPORT_FILE="${RESULTS_DIR}/validation-report-${TIMESTAMP}.md"

# PID do servidor PHP
PHP_SERVER_PID=""

# Função para iniciar servidor PHP
start_php_server() {
    echo -e "${BLUE}🚀 Iniciando servidor PHP local...${NC}"
    cd "$BASE_DIR" || exit 1
    
    # Verificar se porta 8000 já está em uso
    if lsof -ti:8000 &> /dev/null; then
        echo -e "${YELLOW}⚠️  Porta 8000 já está em uso. Tentando usar servidor existente...${NC}"
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

# Função para testar uma página com Lighthouse
test_with_lighthouse() {
    local url="$1"
    local strategy="$2"
    local full_url="${BASE_URL}${url}"
    
    echo -e "${CYAN}📊 Testando: ${full_url} (${strategy})${NC}"
    echo -e "${YELLOW}⏳ Isso pode levar 30-60 segundos...${NC}"
    
    # Nome do arquivo de resultado
    local safe_url=$(echo "$url" | sed 's/[^a-zA-Z0-9]/-/g' | sed 's/--*/-/g' | sed 's/^-//' | sed 's/-$//')
    if [ -z "$safe_url" ]; then
        safe_url="homepage"
    fi
    local result_file="${RESULTS_DIR}/lighthouse-${strategy}--${safe_url}-${TIMESTAMP}.json"
    
    # Executar Lighthouse (sem --quiet para ver progresso)
    if npx lighthouse "$full_url" \
        --only-categories=performance \
        --preset=${strategy} \
        --output=json \
        --output-path="$result_file" \
        --chrome-flags="--headless --no-sandbox" 2>&1 | grep -v "DeprecationWarning" | tail -5; then
        if [ -f "$result_file" ]; then
            echo "$result_file"
            return 0
        else
            echo ""
            return 1
        fi
    else
        echo ""
        return 1
    fi
}

# Função para extrair métricas do JSON
extract_metrics() {
    local json_file="$1"
    
    if [ ! -f "$json_file" ]; then
        echo "{}"
        return 1
    fi
    
    if command -v jq &> /dev/null; then
        jq -c '{
            performance: (.categories.performance.score * 100 | floor),
            fcp: (.audits["first-contentful-paint"].numericValue / 1000 | . * 100 | floor | . / 100),
            lcp: (.audits["largest-contentful-paint"].numericValue / 1000 | . * 100 | floor | . / 100),
            cls: (.audits["cumulative-layout-shift"].numericValue | . * 1000 | floor | . / 1000),
            tbt: (.audits["total-blocking-time"].numericValue / 1000 | . * 100 | floor | . / 100),
            si: (.audits["speed-index"].numericValue / 1000 | . * 100 | floor | . / 100)
        }' "$json_file" 2>/dev/null || echo "{}"
    else
        echo "{}"
    fi
}

# Função para validar métricas
validate_metrics() {
    local metrics="$1"
    local phase="$2"
    
    if [ "$metrics" = "{}" ]; then
        echo "❌"
        return 1
    fi
    
    if command -v jq &> /dev/null; then
        local perf=$(echo "$metrics" | jq -r '.performance // 0')
        local fcp=$(echo "$metrics" | jq -r '.fcp // 999')
        local lcp=$(echo "$metrics" | jq -r '.lcp // 999')
        local cls=$(echo "$metrics" | jq -r '.cls // 999')
        local tbt=$(echo "$metrics" | jq -r '.tbt // 999')
        
        case "$phase" in
            "FASE1")
                # FASE 1: CLS < 0.1
                if (( $(echo "$cls < 0.1" | bc -l 2>/dev/null || echo 0) )); then
                    echo "✅"
                else
                    echo "❌"
                fi
                ;;
            "FASE2")
                # FASE 2: LCP < 2.5s
                if (( $(echo "$lcp < 2.5" | bc -l 2>/dev/null || echo 0) )); then
                    echo "✅"
                else
                    echo "❌"
                fi
                ;;
            "FASE3")
                # FASE 3: FCP < 1.8s
                if (( $(echo "$fcp < 1.8" | bc -l 2>/dev/null || echo 0) )); then
                    echo "✅"
                else
                    echo "❌"
                fi
                ;;
            *)
                echo "❓"
                ;;
        esac
    else
        echo "❓"
    fi
}

# Iniciar servidor PHP
start_php_server

echo ""
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${BLUE}🔍 VALIDAÇÃO COMPLETA - TODAS AS FASES${NC}"
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo ""

# Testar mobile e desktop
STRATEGIES=("mobile" "desktop")
VALIDATION_RESULTS="{"

for strategy in "${STRATEGIES[@]}"; do
    echo -e "${YELLOW}📱 Testando estratégia: ${strategy}${NC}"
    
    # Testar página
    result_file=$(test_with_lighthouse "$TEST_PAGE" "$strategy")
    
    if [ -n "$result_file" ] && [ -f "$result_file" ]; then
        echo -e "${GREEN}✅ Teste concluído${NC}"
        
        # Extrair métricas
        metrics=$(extract_metrics "$result_file")
        
        # Validar cada fase
        fase1_status=$(validate_metrics "$metrics" "FASE1")
        fase2_status=$(validate_metrics "$metrics" "FASE2")
        fase3_status=$(validate_metrics "$metrics" "FASE3")
        
        echo -e "  FASE 1 (CLS < 0.1): ${fase1_status}"
        echo -e "  FASE 2 (LCP < 2.5s): ${fase2_status}"
        echo -e "  FASE 3 (FCP < 1.8s): ${fase3_status}"
        
        # Adicionar aos resultados
        if command -v jq &> /dev/null; then
            VALIDATION_RESULTS=$(echo "$VALIDATION_RESULTS" | jq -c --arg s "$strategy" --argjson m "$metrics" --arg f1 "$fase1_status" --arg f2 "$fase2_status" --arg f3 "$fase3_status" \
                ". + {(\$s): {\"metrics\": \$m, \"fase1\": \$f1, \"fase2\": \$f2, \"fase3\": \$f3}}")
        fi
    else
        echo -e "${RED}❌ Erro ao testar${NC}"
    fi
    
    echo ""
done

VALIDATION_RESULTS=$(echo "$VALIDATION_RESULTS" | jq -c '. + {"timestamp": "'"$TIMESTAMP"'", "url": "'"${BASE_URL}${TEST_PAGE}"'"}')

# Salvar resultados JSON
echo "$VALIDATION_RESULTS" > "$VALIDATION_FILE"
echo -e "${GREEN}✅ Resultados salvos em: ${VALIDATION_FILE}${NC}"

# Gerar relatório Markdown
{
    echo "# Relatório de Validação - Todas as Fases"
    echo ""
    echo "**Data**: $(date '+%Y-%m-%d %H:%M:%S')"
    echo "**URL Testada**: ${BASE_URL}${TEST_PAGE}"
    echo "**Timestamp**: $TIMESTAMP"
    echo ""
    echo "---"
    echo ""
    echo "## 📊 Resultados por Estratégia"
    echo ""
    
    for strategy in "${STRATEGIES[@]}"; do
        echo "### ${strategy^}"
        echo ""
        
        result_file="${RESULTS_DIR}/lighthouse-${strategy}--homepage-${TIMESTAMP}.json"
        if [ -f "$result_file" ] && command -v jq &> /dev/null; then
            perf=$(jq -r '.categories.performance.score * 100 | floor' "$result_file" 2>/dev/null || echo "N/A")
            fcp=$(jq -r '.audits["first-contentful-paint"].numericValue / 1000 | . * 100 | floor | . / 100' "$result_file" 2>/dev/null || echo "N/A")
            lcp=$(jq -r '.audits["largest-contentful-paint"].numericValue / 1000 | . * 100 | floor | . / 100' "$result_file" 2>/dev/null || echo "N/A")
            cls=$(jq -r '.audits["cumulative-layout-shift"].numericValue' "$result_file" 2>/dev/null || echo "N/A")
            tbt=$(jq -r '.audits["total-blocking-time"].numericValue / 1000 | . * 100 | floor | . / 100' "$result_file" 2>/dev/null || echo "N/A")
            si=$(jq -r '.audits["speed-index"].numericValue / 1000 | . * 100 | floor | . / 100' "$result_file" 2>/dev/null || echo "N/A")
            
            echo "| Métrica | Valor | Meta | Status |"
            echo "|---------|-------|------|--------|"
            echo "| **Performance** | $perf | 90+ | $([ "$perf" != "N/A" ] && [ "$perf" -ge 90 ] && echo "✅" || echo "❌") |"
            echo "| **FCP** | ${fcp}s | <1.8s | $([ "$fcp" != "N/A" ] && (( $(echo "$fcp < 1.8" | bc -l 2>/dev/null || echo 0) )) && echo "✅" || echo "❌") |"
            echo "| **LCP** | ${lcp}s | <2.5s | $([ "$lcp" != "N/A" ] && (( $(echo "$lcp < 2.5" | bc -l 2>/dev/null || echo 0) )) && echo "✅" || echo "❌") |"
            echo "| **CLS** | $cls | <0.1 | $([ "$cls" != "N/A" ] && (( $(echo "$cls < 0.1" | bc -l 2>/dev/null || echo 0) )) && echo "✅" || echo "❌") |"
            echo "| **TBT** | ${tbt}s | <200ms | $([ "$tbt" != "N/A" ] && (( $(echo "$tbt < 0.2" | bc -l 2>/dev/null || echo 0) )) && echo "✅" || echo "❌") |"
            echo "| **SI** | ${si}s | <3.4s | $([ "$si" != "N/A" ] && (( $(echo "$si < 3.4" | bc -l 2>/dev/null || echo 0) )) && echo "✅" || echo "❌") |"
            echo ""
            
            # Status das fases
            echo "### Validação das Fases"
            echo ""
            echo "| Fase | Objetivo | Status |"
            echo "|------|----------|--------|"
            echo "| **FASE 1** | CLS < 0.1 | $([ "$cls" != "N/A" ] && (( $(echo "$cls < 0.1" | bc -l 2>/dev/null || echo 0) )) && echo "✅ PASSOU" || echo "❌ FALHOU") |"
            echo "| **FASE 2** | LCP < 2.5s | $([ "$lcp" != "N/A" ] && (( $(echo "$lcp < 2.5" | bc -l 2>/dev/null || echo 0) )) && echo "✅ PASSOU" || echo "❌ FALHOU") |"
            echo "| **FASE 3** | FCP < 1.8s | $([ "$fcp" != "N/A" ] && (( $(echo "$fcp < 1.8" | bc -l 2>/dev/null || echo 0) )) && echo "✅ PASSOU" || echo "❌ FALHOU") |"
            echo ""
        fi
    done
    
    echo "---"
    echo ""
    echo "## 📁 Arquivos de Resultado"
    echo ""
    echo "- JSON: \`${VALIDATION_FILE}\`"
    echo "- Lighthouse: \`${RESULTS_DIR}/lighthouse-*-${TIMESTAMP}.json\`"
    echo ""
    echo "## 🔍 Como Visualizar"
    echo ""
    echo "Para visualizar um resultado JSON:"
    echo "\`\`\`bash"
    echo "cat ${RESULTS_DIR}/lighthouse-mobile--homepage-${TIMESTAMP}.json | jq"
    echo "\`\`\`"
    echo ""
} > "$REPORT_FILE"

echo -e "${GREEN}✅ Relatório gerado: ${REPORT_FILE}${NC}"
echo ""
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${GREEN}✅ Validação completa!${NC}"
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo ""
echo -e "📊 Relatório: ${CYAN}${REPORT_FILE}${NC}"
echo -e "📁 JSON: ${CYAN}${VALIDATION_FILE}${NC}"

