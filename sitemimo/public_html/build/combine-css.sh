#!/bin/bash
# Script para combinar CSS não críticos em um único arquivo
# Uso: ./build/combine-css.sh

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"
OUTPUT_FILE="$PROJECT_ROOT/css/combined-non-critical.min.css"

echo "🔗 Combinando CSS não críticos..."

# CSS não críticos para combinar (usar versões purgadas e minificadas se existirem)
CSS_FILES=(
    "$PROJECT_ROOT/css/purged/dark-mode.min.css"
    "$PROJECT_ROOT/css/purged/animations.min.css"
    "$PROJECT_ROOT/css/purged/mobile-ui-improvements.min.css"
    "$PROJECT_ROOT/css/purged/accessibility-fixes.min.css"
)

# Fallback para versões não minificadas
FALLBACK_CSS=(
    "$PROJECT_ROOT/css/purged/dark-mode.css"
    "$PROJECT_ROOT/css/purged/animations.css"
    "$PROJECT_ROOT/css/purged/mobile-ui-improvements.css"
    "$PROJECT_ROOT/css/purged/accessibility-fixes.css"
)

# Criar arquivo temporário
TEMP_FILE=$(mktemp)

# Combinar arquivos
TOTAL_SIZE=0
COMBINED_COUNT=0

for i in "${!CSS_FILES[@]}"; do
    css_file="${CSS_FILES[$i]}"
    fallback_file="${FALLBACK_CSS[$i]}"
    
    if [ -f "$css_file" ]; then
        echo "  → Adicionando $(basename "$css_file")..."
        cat "$css_file" >> "$TEMP_FILE"
        echo "" >> "$TEMP_FILE"
        SIZE=$(stat -f%z "$css_file" 2>/dev/null || stat -c%s "$css_file" 2>/dev/null)
        TOTAL_SIZE=$((TOTAL_SIZE + SIZE))
        COMBINED_COUNT=$((COMBINED_COUNT + 1))
    elif [ -f "$fallback_file" ]; then
        echo "  → Adicionando $(basename "$fallback_file") (não minificado)..."
        cat "$fallback_file" >> "$TEMP_FILE"
        echo "" >> "$TEMP_FILE"
        SIZE=$(stat -f%z "$fallback_file" 2>/dev/null || stat -c%s "$fallback_file" 2>/dev/null)
        TOTAL_SIZE=$((TOTAL_SIZE + SIZE))
        COMBINED_COUNT=$((COMBINED_COUNT + 1))
    else
        echo "  ⚠️  Arquivo não encontrado: $(basename "$css_file")"
    fi
done

# Minificar arquivo combinado
if [ -f "$TEMP_FILE" ] && [ $COMBINED_COUNT -gt 0 ]; then
    echo ""
    echo "  → Minificando arquivo combinado..."
    if command -v npx &> /dev/null; then
        npx --yes csso-cli "$TEMP_FILE" --output "$OUTPUT_FILE" || {
            echo "⚠️  Aviso: Falha ao minificar, usando versão não minificada"
            cp "$TEMP_FILE" "$OUTPUT_FILE"
        }
    else
        echo "⚠️  Aviso: npx não encontrado, usando versão não minificada"
        cp "$TEMP_FILE" "$OUTPUT_FILE"
    fi
    
    # Calcular tamanho final
    if [ -f "$OUTPUT_FILE" ]; then
        FINAL_SIZE=$(stat -f%z "$OUTPUT_FILE" 2>/dev/null || stat -c%s "$OUTPUT_FILE" 2>/dev/null)
        FINAL_SIZE_KB=$((FINAL_SIZE / 1024))
        TOTAL_SIZE_KB=$((TOTAL_SIZE / 1024))
        SAVINGS=$((TOTAL_SIZE - FINAL_SIZE))
        SAVINGS_KB=$((SAVINGS / 1024))
        
        echo ""
        echo "✅ CSS combinado criado!"
        echo "   📁 Arquivo: $OUTPUT_FILE"
        echo "   📊 Tamanho: ${FINAL_SIZE_KB} KiB (original: ${TOTAL_SIZE_KB} KiB)"
        echo "   💾 Economia: ${SAVINGS_KB} KiB"
        echo "   📦 Arquivos combinados: $COMBINED_COUNT"
    fi
else
    echo "❌ Erro: Nenhum arquivo CSS encontrado para combinar"
    exit 1
fi

# Limpar arquivo temporário
rm -f "$TEMP_FILE"

echo ""
echo "⚠️  IMPORTANTE: Atualize os arquivos PHP para usar css/combined-non-critical.min.css"
echo "   Remova os carregamentos individuais de dark-mode, animations, mobile-ui-improvements, accessibility-fixes"

