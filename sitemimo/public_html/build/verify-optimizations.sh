#!/bin/bash
# Script para verificar se todas as otimizações foram aplicadas
# Uso: ./build/verify-optimizations.sh

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"

echo "🔍 Verificando otimizações..."
echo ""

ERRORS=0
WARNINGS=0

# Verificar Bootstrap custom
if [ -f "$PROJECT_ROOT/bootstrap/bootstrap-custom.min.js" ]; then
    SIZE=$(stat -f%z "$PROJECT_ROOT/bootstrap/bootstrap-custom.min.js" 2>/dev/null || stat -c%s "$PROJECT_ROOT/bootstrap/bootstrap-custom.min.js" 2>/dev/null)
    SIZE_KB=$((SIZE / 1024))
    echo "✅ Bootstrap custom: ${SIZE_KB} KiB"
else
    echo "❌ Bootstrap custom não encontrado"
    ERRORS=$((ERRORS + 1))
fi

# Verificar CSS purgados e minificados
PURGED_CSS=(
    "css/purged/product.min.css"
    "css/purged/dark-mode.min.css"
    "css/purged/animations.min.css"
    "css/purged/mobile-ui-improvements.min.css"
    "css/purged/accessibility-fixes.min.css"
)

echo ""
echo "📊 CSS Purgado e Minificado:"
for css in "${PURGED_CSS[@]}"; do
    if [ -f "$PROJECT_ROOT/$css" ]; then
        SIZE=$(stat -f%z "$PROJECT_ROOT/$css" 2>/dev/null || stat -c%s "$PROJECT_ROOT/$css" 2>/dev/null)
        SIZE_KB=$((SIZE / 1024))
        echo "   ✅ $css: ${SIZE_KB} KiB"
    else
        echo "   ⚠️  $css: não encontrado"
        WARNINGS=$((WARNINGS + 1))
    fi
done

# Verificar JS minificados
MINIFIED_JS=(
    "minified/main.min.js"
    "minified/dark-mode.min.js"
    "minified/animations.min.js"
    "minified/bc-swipe.min.js"
    "minified/form-main.min.js"
)

echo ""
echo "📊 JavaScript Minificado:"
for js in "${MINIFIED_JS[@]}"; do
    if [ -f "$PROJECT_ROOT/$js" ]; then
        SIZE=$(stat -f%z "$PROJECT_ROOT/$js" 2>/dev/null || stat -c%s "$PROJECT_ROOT/$js" 2>/dev/null)
        SIZE_KB=$((SIZE / 1024))
        echo "   ✅ $js: ${SIZE_KB} KiB"
    else
        echo "   ⚠️  $js: não encontrado"
        WARNINGS=$((WARNINGS + 1))
    fi
done

# Verificar arquivos combinados
echo ""
echo "📊 Arquivos Combinados:"
if [ -f "$PROJECT_ROOT/css/combined-non-critical.min.css" ]; then
    SIZE=$(stat -f%z "$PROJECT_ROOT/css/combined-non-critical.min.css" 2>/dev/null || stat -c%s "$PROJECT_ROOT/css/combined-non-critical.min.css" 2>/dev/null)
    SIZE_KB=$((SIZE / 1024))
    echo "   ✅ css/combined-non-critical.min.css: ${SIZE_KB} KiB"
else
    echo "   ⚠️  css/combined-non-critical.min.css: não encontrado"
    WARNINGS=$((WARNINGS + 1))
fi

if [ -f "$PROJECT_ROOT/js/combined.min.js" ]; then
    SIZE=$(stat -f%z "$PROJECT_ROOT/js/combined.min.js" 2>/dev/null || stat -c%s "$PROJECT_ROOT/js/combined.min.js" 2>/dev/null)
    SIZE_KB=$((SIZE / 1024))
    echo "   ✅ js/combined.min.js: ${SIZE_KB} KiB"
else
    echo "   ⚠️  js/combined.min.js: não encontrado"
    WARNINGS=$((WARNINGS + 1))
fi

# Verificar imagens AVIF/WebP LCP
echo ""
echo "📊 Imagens LCP (AVIF/WebP):"
LCP_IMAGES=(
    "img/header_dezembro_mobile.avif"
    "img/bgheader.avif"
    "img/mimo5.avif"
)

for img in "${LCP_IMAGES[@]}"; do
    if [ -f "$PROJECT_ROOT/$img" ]; then
        SIZE=$(stat -f%z "$PROJECT_ROOT/$img" 2>/dev/null || stat -c%s "$PROJECT_ROOT/$img" 2>/dev/null)
        SIZE_KB=$((SIZE / 1024))
        echo "   ✅ $img: ${SIZE_KB} KiB"
    else
        echo "   ⚠️  $img: não encontrado"
        WARNINGS=$((WARNINGS + 1))
    fi
done

# Verificar config.php
echo ""
echo "📊 Configuração:"
if grep -q "USE_MINIFIED.*true" "$PROJECT_ROOT/config.php" 2>/dev/null; then
    echo "   ✅ USE_MINIFIED: true"
else
    echo "   ⚠️  USE_MINIFIED: não está true"
    WARNINGS=$((WARNINGS + 1))
fi

if grep -q "ASSET_VERSION" "$PROJECT_ROOT/config.php" 2>/dev/null; then
    VERSION=$(grep "ASSET_VERSION" "$PROJECT_ROOT/config.php" | head -1 | sed "s/.*'\([^']*\)'.*/\1/")
    echo "   ✅ ASSET_VERSION: $VERSION"
else
    echo "   ⚠️  ASSET_VERSION: não encontrado"
    WARNINGS=$((WARNINGS + 1))
fi

# Resumo
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
if [ $ERRORS -eq 0 ] && [ $WARNINGS -eq 0 ]; then
    echo "✅ Todas as otimizações verificadas com sucesso!"
    exit 0
elif [ $ERRORS -eq 0 ]; then
    echo "⚠️  Verificação concluída com $WARNINGS avisos"
    exit 0
else
    echo "❌ Verificação falhou com $ERRORS erros e $WARNINGS avisos"
    exit 1
fi

