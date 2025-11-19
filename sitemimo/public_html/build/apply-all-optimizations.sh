#!/bin/bash

# Script para aplicar todas as otimizações conhecidas
# Uso: ./build/apply-all-optimizations.sh

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PUBLIC_HTML_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"

cd "$PUBLIC_HTML_DIR"

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🚀 Aplicando Todas as Otimizações"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

# 1. Minificar JavaScript
echo "📦 1. Minificando JavaScript..."
if [ -f "$SCRIPT_DIR/minify-js.sh" ]; then
    bash "$SCRIPT_DIR/minify-js.sh" || echo "  ⚠️  Minificação JS falhou (continuando...)"
else
    echo "  ⚠️  Script minify-js.sh não encontrado"
fi
echo ""

# 2. Purgar CSS não utilizado
echo "🧹 2. Removendo CSS não utilizado..."
if [ -f "$SCRIPT_DIR/purge-css.sh" ]; then
    bash "$SCRIPT_DIR/purge-css.sh" || echo "  ⚠️  Purga CSS falhou (continuando...)"
else
    echo "  ⚠️  Script purge-css.sh não encontrado"
fi
echo ""

# 3. Minificar CSS
echo "📦 3. Minificando CSS..."
if [ -f "$SCRIPT_DIR/minify-css.sh" ]; then
    bash "$SCRIPT_DIR/minify-css.sh" || echo "  ⚠️  Minificação CSS falhou (continuando...)"
else
    echo "  ⚠️  Script minify-css.sh não encontrado"
fi
echo ""

# 4. Otimizar imagens restantes
echo "🖼️  4. Otimizando imagens restantes..."
if [ -f "$SCRIPT_DIR/optimize-remaining-images.sh" ]; then
    echo "  ℹ️  Executando otimização de imagens (pode demorar)..."
    bash "$SCRIPT_DIR/optimize-remaining-images.sh" || echo "  ⚠️  Otimização de imagens falhou (continuando...)"
else
    echo "  ⚠️  Script optimize-remaining-images.sh não encontrado"
fi
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "✅ Otimizações aplicadas!"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "📝 Próximos passos:"
echo "1. Verificar se arquivos minificados foram criados em minified/"
echo "2. Verificar se CSS purgado foi criado em css/purged/"
echo "3. Atualizar config.php para USE_MINIFIED=true (se desejar)"
echo "4. Executar testes do PageSpeed Insights para validar melhorias"

