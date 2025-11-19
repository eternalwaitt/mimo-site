#!/bin/bash

# Script para verificar se arquivos minificados/purgados foram deployados em produção
# Uso: ./build/verify-deployment.sh

set -e

PROD_URL="https://minhamimo.com.br"
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"

echo "🔍 Verificando deploy de arquivos otimizados em produção..."
echo "URL: $PROD_URL"
echo ""

# Cores para output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Função para verificar se arquivo existe e retorna tamanho
check_file() {
    local URL="$1"
    local EXPECTED_TYPE="$2" # "minified" ou "purged"
    
    echo -n "   Verificando: $URL ... "
    
    # Fazer request e pegar status code e content-type
    RESPONSE=$(curl -s -o /dev/null -w "%{http_code}|%{content_type}|%{size_download}" "$URL" 2>/dev/null || echo "000|||0")
    HTTP_CODE=$(echo "$RESPONSE" | cut -d'|' -f1)
    CONTENT_TYPE=$(echo "$RESPONSE" | cut -d'|' -f2)
    SIZE=$(echo "$RESPONSE" | cut -d'|' -f3)
    
    if [ "$HTTP_CODE" = "200" ]; then
        SIZE_KB=$((SIZE / 1024))
        echo -e "${GREEN}✅ OK${NC} (${SIZE_KB} KiB, $CONTENT_TYPE)"
        return 0
    else
        echo -e "${RED}❌ FALHOU${NC} (HTTP $HTTP_CODE)"
        return 1
    fi
}

# Verificar arquivos minificados
echo "📦 ARQUIVOS MINIFICADOS:"
echo ""

# CSS minificados
check_file "$PROD_URL/minified/product.min.css?v=20251115-2" "minified"
check_file "$PROD_URL/minified/servicos.min.css?v=20251115-2" "minified"
check_file "$PROD_URL/minified/form-main.min.css?v=20251115-2" "minified"

# JS minificados
check_file "$PROD_URL/minified/main.min.js?v=20251115-2" "minified"
check_file "$PROD_URL/minified/dark-mode.min.js?v=20251115-2" "minified"
check_file "$PROD_URL/minified/form-main.min.js?v=20251115-2" "minified"

echo ""
echo "🧹 ARQUIVOS PURGADOS:"
echo ""

# CSS purgados
check_file "$PROD_URL/css/purged/product.min.css?v=20251115-2" "purged"
check_file "$PROD_URL/css/purged/dark-mode.min.css?v=20251115-2" "purged"
check_file "$PROD_URL/css/purged/animations.min.css?v=20251115-2" "purged"

echo ""
echo "🔧 VERIFICANDO CONFIGURAÇÃO:"
echo ""

# Verificar se USE_MINIFIED está ativo (via source do HTML)
echo -n "   Verificando USE_MINIFIED em index.php ... "
HTML_SOURCE=$(curl -s "$PROD_URL/" 2>/dev/null || echo "")
if echo "$HTML_SOURCE" | grep -q "minified\|purged" 2>/dev/null; then
    echo -e "${GREEN}✅ Arquivos minificados/purgados sendo referenciados${NC}"
else
    echo -e "${YELLOW}⚠️  Não encontrou referências a minified/purged no HTML${NC}"
    echo "      Isso pode indicar que USE_MINIFIED=false ou arquivos não existem"
fi

echo ""
echo "🖼️  VERIFICANDO IMAGENS OTIMIZADAS:"
echo ""

# Verificar se imagens AVIF/WebP existem
check_file "$PROD_URL/img/bgheader.avif" "image"
check_file "$PROD_URL/img/bgheader.webp" "image"
check_file "$PROD_URL/img/mimo5.avif" "image"
check_file "$PROD_URL/img/mimo5.webp" "image"

echo ""
echo "📊 VERIFICANDO ASSET VERSION:"
echo ""

# Verificar asset version no HTML
echo -n "   Asset Version no HTML ... "
ASSET_VERSION=$(echo "$HTML_SOURCE" | grep -oP 'v=\K[0-9-]+' | head -1 || echo "")
if [ -n "$ASSET_VERSION" ]; then
    if [ "$ASSET_VERSION" = "20251115-2" ]; then
        echo -e "${GREEN}✅ $ASSET_VERSION (correto)${NC}"
    else
        echo -e "${YELLOW}⚠️  $ASSET_VERSION (esperado: 20251115-2)${NC}"
    fi
else
    echo -e "${RED}❌ Não encontrado${NC}"
fi

echo ""
echo "📋 VERIFICANDO RESPONSE HEADERS:"
echo ""

# Verificar headers importantes
echo -n "   Cache-Control ... "
CACHE_HEADER=$(curl -s -I "$PROD_URL/" 2>/dev/null | grep -i "cache-control" || echo "")
if [ -n "$CACHE_HEADER" ]; then
    echo -e "${GREEN}✅ Presente${NC}"
    echo "      $CACHE_HEADER"
else
    echo -e "${YELLOW}⚠️  Não encontrado${NC}"
fi

echo ""
echo "✅ Verificação concluída!"
echo ""
echo "💡 DICAS:"
echo "   - Se arquivos retornam 404, eles não foram deployados"
echo "   - Se retornam 200 mas não são minificados, verificar USE_MINIFIED"
echo "   - Se asset version está errado, verificar config.php em produção"
echo "   - Aguardar 15-30 min após deploy para cache propagar"

