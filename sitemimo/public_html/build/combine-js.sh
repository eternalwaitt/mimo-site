#!/bin/bash
# Script para combinar JavaScript não críticos em um único arquivo
# Uso: ./build/combine-js.sh

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"
OUTPUT_FILE="$PROJECT_ROOT/js/combined.min.js"

echo "🔗 Combinando JavaScript não críticos..."

# JavaScript não críticos para combinar (usar versões minificadas se existirem)
JS_FILES=(
    "$PROJECT_ROOT/minified/dark-mode.min.js"
    "$PROJECT_ROOT/minified/animations.min.js"
    "$PROJECT_ROOT/minified/bc-swipe.min.js"
)

# Fallback para versões não minificadas
FALLBACK_JS=(
    "$PROJECT_ROOT/js/dark-mode.js"
    "$PROJECT_ROOT/js/animations.js"
    "$PROJECT_ROOT/js/bc-swipe.js"
)

# Criar arquivo temporário
TEMP_FILE=$(mktemp)

# Combinar arquivos
TOTAL_SIZE=0
COMBINED_COUNT=0

for i in "${!JS_FILES[@]}"; do
    js_file="${JS_FILES[$i]}"
    fallback_file="${FALLBACK_JS[$i]}"
    
    if [ -f "$js_file" ]; then
        echo "  → Adicionando $(basename "$js_file")..."
        cat "$js_file" >> "$TEMP_FILE"
        echo ";" >> "$TEMP_FILE"
        SIZE=$(stat -f%z "$js_file" 2>/dev/null || stat -c%s "$js_file" 2>/dev/null)
        TOTAL_SIZE=$((TOTAL_SIZE + SIZE))
        COMBINED_COUNT=$((COMBINED_COUNT + 1))
    elif [ -f "$fallback_file" ]; then
        echo "  → Adicionando $(basename "$fallback_file") (não minificado)..."
        cat "$fallback_file" >> "$TEMP_FILE"
        echo ";" >> "$TEMP_FILE"
        SIZE=$(stat -f%z "$fallback_file" 2>/dev/null || stat -c%s "$fallback_file" 2>/dev/null)
        TOTAL_SIZE=$((TOTAL_SIZE + SIZE))
        COMBINED_COUNT=$((COMBINED_COUNT + 1))
    else
        echo "  ⚠️  Arquivo não encontrado: $(basename "$js_file")"
    fi
done

# Minificar arquivo combinado
if [ -f "$TEMP_FILE" ] && [ $COMBINED_COUNT -gt 0 ]; then
    echo ""
    echo "  → Minificando arquivo combinado..."
    if command -v npx &> /dev/null; then
        npx --yes terser "$TEMP_FILE" --compress --mangle --output "$OUTPUT_FILE" || {
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
        echo "✅ JavaScript combinado criado!"
        echo "   📁 Arquivo: $OUTPUT_FILE"
        echo "   📊 Tamanho: ${FINAL_SIZE_KB} KiB (original: ${TOTAL_SIZE_KB} KiB)"
        echo "   💾 Economia: ${SAVINGS_KB} KiB"
        echo "   📦 Arquivos combinados: $COMBINED_COUNT"
    fi
else
    echo "❌ Erro: Nenhum arquivo JavaScript encontrado para combinar"
    exit 1
fi

# Limpar arquivo temporário
rm -f "$TEMP_FILE"

echo ""
echo "⚠️  IMPORTANTE: Atualize os arquivos PHP para usar js/combined.min.js"
echo "   Remova os carregamentos individuais de dark-mode, animations, bc-swipe"

