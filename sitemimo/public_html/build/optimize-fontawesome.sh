#!/bin/bash
# Script para identificar ícones Font Awesome usados e criar build customizado
# Uso: ./build/optimize-fontawesome.sh

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"

echo "🔍 Identificando ícones Font Awesome usados..."

# Extrair todos os ícones fa-* usados
ICONS=$(grep -roh "fa-[a-z0-9-]\+" "$PROJECT_ROOT" --include="*.php" --include="*.html" | sort -u | sed 's/fa-//')

echo ""
echo "📊 Ícones Font Awesome encontrados:"
echo "$ICONS" | while read icon; do
    if [ -n "$icon" ]; then
        echo "   - fa-$icon"
    fi
done

ICON_COUNT=$(echo "$ICONS" | grep -v '^$' | wc -l | tr -d ' ')

echo ""
echo "✅ Total: $ICON_COUNT ícones únicos"
echo ""
echo "💡 RECOMENDAÇÃO:"
echo "   Font Awesome completo: ~70 KiB (CSS)"
echo "   Build customizado: ~$((ICON_COUNT * 2)) KiB (estimado)"
echo ""
echo "   Opções:"
echo "   1. Usar apenas SVG inline para os ícones usados (melhor performance)"
echo "   2. Criar build customizado do Font Awesome (se muitos ícones)"
echo ""
echo "   Ícones principais identificados:"
echo "   - fa-instagram (footer)"
echo "   - fa-facebook-f (footer)"
echo "   - fa-whatsapp (footer)"
echo ""
echo "⚠️  NOTA: Para melhor performance, considere substituir por SVG inline"
echo "   Isso elimina completamente a dependência do Font Awesome CSS (~70 KiB)"

