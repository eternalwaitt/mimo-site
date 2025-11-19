#!/bin/bash

# Script simplificado para validar todas as fases localmente
# Extrai métricas e valida FASE 1 (CLS), FASE 2 (LCP), FASE 3 (FCP)

set -e

cd "$(dirname "$0")/.." || exit 1

RESULTS_DIR="pagespeed-results"
TIMESTAMP=$(date +%Y%m%d-%H%M%S)
REPORT_FILE="${RESULTS_DIR}/validation-report-${TIMESTAMP}.md"

echo "🔍 Validando todas as fases..."
echo ""

# Encontrar arquivos mais recentes
MOBILE_FILE=$(ls -t ${RESULTS_DIR}/validation-mobile-*.json 2>/dev/null | head -1)
DESKTOP_FILE=$(ls -t ${RESULTS_DIR}/validation-desktop-*.json 2>/dev/null | head -1)

if [ -z "$MOBILE_FILE" ] && [ -z "$DESKTOP_FILE" ]; then
    echo "❌ Nenhum arquivo de resultado encontrado"
    echo "Execute primeiro: npx lighthouse http://localhost:8000/ ..."
    exit 1
fi

# Função para validar métricas
validate_phase() {
    local file="$1"
    local strategy="$2"
    
    if [ ! -f "$file" ]; then
        return 1
    fi
    
    local perf=$(jq -r '.categories.performance.score * 100 | floor' "$file" 2>/dev/null || echo "0")
    local fcp=$(jq -r '.audits["first-contentful-paint"].numericValue / 1000' "$file" 2>/dev/null || echo "999")
    local lcp=$(jq -r '.audits["largest-contentful-paint"].numericValue / 1000' "$file" 2>/dev/null || echo "999")
    local cls=$(jq -r '.audits["cumulative-layout-shift"].numericValue' "$file" 2>/dev/null || echo "999")
    local tbt=$(jq -r '.audits["total-blocking-time"].numericValue / 1000' "$file" 2>/dev/null || echo "999")
    local si=$(jq -r '.audits["speed-index"].numericValue / 1000' "$file" 2>/dev/null || echo "999")
    
    local strategy_upper=$(echo "$strategy" | tr '[:lower:]' '[:upper:]')
    echo "### $strategy_upper"
    echo ""
    echo "| Métrica | Valor | Meta | Status |"
    echo "|---------|-------|------|--------|"
    
    # Performance
    if [ "$perf" -ge 90 ]; then
        echo "| **Performance** | $perf | 90+ | ✅ |"
    else
        echo "| **Performance** | $perf | 90+ | ❌ |"
    fi
    
    # FCP (FASE 3)
    if (( $(echo "$fcp < 1.8" | bc -l) )); then
        echo "| **FCP** (FASE 3) | ${fcp}s | <1.8s | ✅ |"
    else
        echo "| **FCP** (FASE 3) | ${fcp}s | <1.8s | ❌ |"
    fi
    
    # LCP (FASE 2)
    if (( $(echo "$lcp < 2.5" | bc -l) )); then
        echo "| **LCP** (FASE 2) | ${lcp}s | <2.5s | ✅ |"
    else
        echo "| **LCP** (FASE 2) | ${lcp}s | <2.5s | ❌ |"
    fi
    
    # CLS (FASE 1)
    if (( $(echo "$cls < 0.1" | bc -l) )); then
        echo "| **CLS** (FASE 1) | $cls | <0.1 | ✅ |"
    else
        echo "| **CLS** (FASE 1) | $cls | <0.1 | ❌ |"
    fi
    
    # TBT
    if (( $(echo "$tbt < 0.2" | bc -l) )); then
        echo "| **TBT** | ${tbt}s | <200ms | ✅ |"
    else
        echo "| **TBT** | ${tbt}s | <200ms | ❌ |"
    fi
    
    # SI
    if (( $(echo "$si < 3.4" | bc -l) )); then
        echo "| **SI** | ${si}s | <3.4s | ✅ |"
    else
        echo "| **SI** | ${si}s | <3.4s | ❌ |"
    fi
    
    echo ""
    echo "#### Validação das Fases"
    echo ""
    echo "| Fase | Objetivo | Status |"
    echo "|------|----------|--------|"
    
    # FASE 1
    if (( $(echo "$cls < 0.1" | bc -l) )); then
        echo "| **FASE 1** | CLS < 0.1 | ✅ **PASSOU** |"
    else
        echo "| **FASE 1** | CLS < 0.1 | ❌ **FALHOU** ($cls) |"
    fi
    
    # FASE 2
    if (( $(echo "$lcp < 2.5" | bc -l) )); then
        echo "| **FASE 2** | LCP < 2.5s | ✅ **PASSOU** |"
    else
        echo "| **FASE 2** | LCP < 2.5s | ❌ **FALHOU** (${lcp}s) |"
    fi
    
    # FASE 3
    if (( $(echo "$fcp < 1.8" | bc -l) )); then
        echo "| **FASE 3** | FCP < 1.8s | ✅ **PASSOU** |"
    else
        echo "| **FASE 3** | FCP < 1.8s | ❌ **FALHOU** (${fcp}s) |"
    fi
    
    echo ""
}

# Gerar relatório
{
    echo "# Relatório de Validação - Todas as Fases"
    echo ""
    echo "**Data**: $(date '+%Y-%m-%d %H:%M:%S')"
    echo "**URL Testada**: http://localhost:8000/"
    echo "**Timestamp**: $TIMESTAMP"
    echo ""
    echo "---"
    echo ""
    echo "## 📊 Resultados"
    echo ""
    
    if [ -n "$MOBILE_FILE" ]; then
        validate_phase "$MOBILE_FILE" "mobile"
    fi
    
    if [ -n "$DESKTOP_FILE" ]; then
        validate_phase "$DESKTOP_FILE" "desktop"
    fi
    
    echo "---"
    echo ""
    echo "## 📁 Arquivos"
    echo ""
    [ -n "$MOBILE_FILE" ] && echo "- Mobile: \`$MOBILE_FILE\`"
    [ -n "$DESKTOP_FILE" ] && echo "- Desktop: \`$DESKTOP_FILE\`"
    echo ""
} > "$REPORT_FILE"

echo "✅ Relatório gerado: $REPORT_FILE"
echo ""
cat "$REPORT_FILE"

