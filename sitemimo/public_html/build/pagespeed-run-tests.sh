#!/bin/bash

# Script wrapper para executar testes do PageSpeed Insights
# Uso: ./build/pagespeed-run-tests.sh [API_KEY]

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PUBLIC_HTML_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"

cd "$PUBLIC_HTML_DIR"

# Verificar se API key foi fornecida
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
    echo "  ./build/pagespeed-run-tests.sh"
    echo ""
    echo "Ou:"
    echo "  ./build/pagespeed-run-tests.sh SUA_CHAVE"
    exit 1
fi

echo "🚀 Iniciando testes do PageSpeed Insights..."
echo ""

# Executar testes
"$SCRIPT_DIR/pagespeed-api-test.sh" "$API_KEY"

# Analisar resultados
if [ -d "pagespeed-results" ] && [ "$(ls -A pagespeed-results/*.json 2>/dev/null)" ]; then
    echo ""
    echo "📊 Analisando resultados..."
    "$SCRIPT_DIR/pagespeed-analyze.sh" pagespeed-results
fi

echo ""
echo "✅ Testes concluídos!"
echo "📁 Resultados salvos em: pagespeed-results/"

