#!/bin/bash

# Workflow completo: Testes + Análise + Extração de Problemas
# Uso: ./build/pagespeed-complete-workflow.sh [API_KEY]

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PUBLIC_HTML_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"

cd "$PUBLIC_HTML_DIR"

API_KEY="${1:-${PAGESPEED_API_KEY}}"

if [ -z "$API_KEY" ]; then
    echo "❌ Erro: API Key não fornecida"
    echo ""
    echo "Para obter uma API Key:"
    echo "1. Acesse: https://console.cloud.google.com/apis/credentials"
    echo "2. Crie uma nova chave de API"
    echo "3. Habilite a API 'PageSpeed Insights API'"
    echo ""
    echo "Uso:"
    echo "  export PAGESPEED_API_KEY='sua-chave'"
    echo "  ./build/pagespeed-complete-workflow.sh"
    echo ""
    echo "Ou:"
    echo "  ./build/pagespeed-complete-workflow.sh SUA_CHAVE"
    exit 1
fi

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🚀 PageSpeed Insights - Workflow Completo"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

# Fase 1: Executar Testes
echo "📊 FASE 1: Executando testes em todas as páginas..."
echo ""
"$SCRIPT_DIR/pagespeed-api-test.sh" "$API_KEY"

# Fase 2: Analisar Resultados
if [ -d "pagespeed-results" ] && [ "$(ls -A pagespeed-results/*.json 2>/dev/null)" ]; then
    echo ""
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    echo "📈 FASE 2: Analisando resultados..."
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    echo ""
    "$SCRIPT_DIR/pagespeed-analyze.sh" pagespeed-results
    
    # Fase 3: Extrair Problemas
    echo ""
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    echo "🔍 FASE 3: Extraindo oportunidades de melhoria..."
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    echo ""
    "$SCRIPT_DIR/pagespeed-extract-issues.sh" pagespeed-results
    
    echo ""
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    echo "✅ Workflow completo finalizado!"
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    echo ""
    echo "📁 Resultados salvos em: pagespeed-results/"
    echo "📄 Relatórios gerados:"
    ls -1 pagespeed-results/*.md 2>/dev/null | sed 's/^/   - /' || echo "   (nenhum relatório encontrado)"
else
    echo "⚠️  Nenhum resultado encontrado para análise"
fi

