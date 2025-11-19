#!/bin/bash

# Script para otimizar TODAS as imagens grandes (>100KB)
# Foca em reduzir network payload de 3.79MB para <1.6MB
# Prioriza imagens gigantes (>1MB) primeiro

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PUBLIC_DIR="$SCRIPT_DIR/../"
IMG_DIR="$PUBLIC_DIR/img"

echo "🖼️  Otimizando TODAS as imagens grandes..."
echo "📊 Meta: Reduzir network payload de 3.79MB para <1.6MB"
echo ""

PROCESSED=0
SKIPPED=0
TOTAL_SAVED=0
TOTAL_ORIGINAL=0

# Função para otimizar uma imagem
optimize_image() {
    local IMG_PATH="$1"
    local FILENAME=$(basename "$IMG_PATH")
    local DIR=$(dirname "$IMG_PATH")
    local BASENAME="${FILENAME%.*}"
    local EXT="${FILENAME##*.}"
    
    # Skip se já tem AVIF e WebP
    if [[ -f "$DIR/$BASENAME.avif" ]] && [[ -f "$DIR/$BASENAME.webp" ]]; then
        echo "⏭️  $FILENAME já otimizado (AVIF e WebP existem)"
        SKIPPED=$((SKIPPED + 1))
        return
    fi
    
    PROCESSED=$((PROCESSED + 1))
    ORIGINAL_SIZE=$(stat -f%z "$IMG_PATH" 2>/dev/null || stat -c%s "$IMG_PATH" 2>/dev/null)
    TOTAL_ORIGINAL=$((TOTAL_ORIGINAL + ORIGINAL_SIZE))
    
    SIZE_MB=$(awk "BEGIN {printf \"%.2f\", $ORIGINAL_SIZE/1024/1024}")
    echo "[$PROCESSED] 🔄 Processando: $FILENAME (${SIZE_MB}MB)"
    
    # Converter para WebP
    if command -v cwebp &> /dev/null; then
        if [[ ! -f "$DIR/$BASENAME.webp" ]]; then
            echo "   📦 Criando WebP..."
            cwebp -q 80 -m 6 "$IMG_PATH" -o "$DIR/$BASENAME.webp" 2>/dev/null
            if [[ $? -eq 0 ]]; then
                WEBP_SIZE=$(stat -f%z "$DIR/$BASENAME.webp" 2>/dev/null || stat -c%s "$DIR/$BASENAME.webp" 2>/dev/null)
                WEBP_MB=$(awk "BEGIN {printf \"%.2f\", $WEBP_SIZE/1024/1024}")
                SAVINGS=$((ORIGINAL_SIZE - WEBP_SIZE))
                echo "   ✅ WebP criado (${WEBP_MB}MB, economia: $(awk "BEGIN {printf \"%.2f\", $SAVINGS/1024/1024}")MB)"
            fi
        fi
    fi
    
    # Converter para AVIF
    if command -v avifenc &> /dev/null; then
        if [[ ! -f "$DIR/$BASENAME.avif" ]]; then
            echo "   📦 Criando AVIF..."
            avifenc -j all -d 8 -y 444 -c aom --min 0 --max 63 -a cq-level=30 -o "$DIR/$BASENAME.avif" "$IMG_PATH" 2>/dev/null
            if [[ $? -eq 0 ]]; then
                AVIF_SIZE=$(stat -f%z "$DIR/$BASENAME.avif" 2>/dev/null || stat -c%s "$DIR/$BASENAME.avif" 2>/dev/null)
                AVIF_MB=$(awk "BEGIN {printf \"%.2f\", $AVIF_SIZE/1024/1024}")
                SAVINGS=$((ORIGINAL_SIZE - AVIF_SIZE))
                echo "   ✅ AVIF criado (${AVIF_MB}MB, economia: $(awk "BEGIN {printf \"%.2f\", $SAVINGS/1024/1024}")MB)"
            fi
        fi
    elif command -v convert &> /dev/null; then
        if [[ ! -f "$DIR/$BASENAME.avif" ]]; then
            echo "   📦 Criando AVIF (ImageMagick)..."
            convert "$IMG_PATH" -quality 75 "$DIR/$BASENAME.avif" 2>/dev/null
            if [[ $? -eq 0 ]]; then
                AVIF_SIZE=$(stat -f%z "$DIR/$BASENAME.avif" 2>/dev/null || stat -c%s "$DIR/$BASENAME.avif" 2>/dev/null)
                AVIF_MB=$(awk "BEGIN {printf \"%.2f\", $AVIF_SIZE/1024/1024}")
                SAVINGS=$((ORIGINAL_SIZE - AVIF_SIZE))
                echo "   ✅ AVIF criado (${AVIF_MB}MB, economia: $(awk "BEGIN {printf \"%.2f\", $SAVINGS/1024/1024}")MB)"
            fi
        fi
    fi
    
    # Comprimir original
    if [[ "$EXT" == "png" ]] && command -v optipng &> /dev/null; then
        echo "   📦 Comprimindo PNG original..."
        optipng -o7 -strip all "$IMG_PATH" 2>/dev/null
        COMPRESSED_SIZE=$(stat -f%z "$IMG_PATH" 2>/dev/null || stat -c%s "$IMG_PATH" 2>/dev/null)
        if [[ $ORIGINAL_SIZE -gt $COMPRESSED_SIZE ]]; then
            SAVINGS=$((ORIGINAL_SIZE - COMPRESSED_SIZE))
            TOTAL_SAVED=$((TOTAL_SAVED + SAVINGS))
            SAVINGS_MB=$(awk "BEGIN {printf \"%.2f\", $SAVINGS/1024/1024}")
            echo "   💾 PNG comprimido: ${SAVINGS_MB}MB economizados"
        fi
    elif [[ "$EXT" == "jpg" ]] || [[ "$EXT" == "jpeg" ]]; then
        if command -v jpegoptim &> /dev/null; then
            echo "   📦 Comprimindo JPG original..."
            jpegoptim --max=85 --strip-all --preserve "$IMG_PATH" 2>/dev/null
            COMPRESSED_SIZE=$(stat -f%z "$IMG_PATH" 2>/dev/null || stat -c%s "$IMG_PATH" 2>/dev/null)
            if [[ $ORIGINAL_SIZE -gt $COMPRESSED_SIZE ]]; then
                SAVINGS=$((ORIGINAL_SIZE - COMPRESSED_SIZE))
                TOTAL_SAVED=$((TOTAL_SAVED + SAVINGS))
                SAVINGS_MB=$(awk "BEGIN {printf \"%.2f\", $SAVINGS/1024/1024}")
                echo "   💾 JPG comprimido: ${SAVINGS_MB}MB economizados"
            fi
        fi
    fi
    
    echo "   ✅ Concluído: $FILENAME"
    echo ""
}

# FASE 1: Processar imagens GIGANTES primeiro (>1MB)
echo "🔥 FASE 1: Processando imagens GIGANTES (>1MB)..."
echo ""

# REMOVIDO: ! -path "*/mobile_promocional/*" - essas imagens também precisam ser otimizadas
GIANT_IMAGES=$(find "$IMG_DIR" -type f \( -name "*.jpg" -o -name "*.png" -o -name "*.jpeg" \) ! -name "*_*" ! -path "*/bootstrap/*" -size +1M 2>/dev/null | sort -rh)

if [[ -n "$GIANT_IMAGES" ]]; then
    echo "$GIANT_IMAGES" | while read img; do
        if [[ -f "$img" ]]; then
            optimize_image "$img"
        fi
    done
else
    echo "   ℹ️  Nenhuma imagem gigante encontrada"
fi

echo ""
echo "📊 FASE 2: Processando outras imagens grandes (>100KB)..."
echo ""

# FASE 2: Processar outras imagens grandes (>100KB)
# REMOVIDO: ! -path "*/mobile_promocional/*" - essas imagens também precisam ser otimizadas
LARGE_IMAGES=$(find "$IMG_DIR" -type f \( -name "*.jpg" -o -name "*.png" -o -name "*.jpeg" \) ! -name "*_*" ! -path "*/bootstrap/*" -size +100k ! -size +1M 2>/dev/null | sort -rh)

if [[ -n "$LARGE_IMAGES" ]]; then
    COUNT=$(echo "$LARGE_IMAGES" | wc -l | tr -d ' ')
    echo "   📋 Encontradas $COUNT imagens grandes"
    echo ""
    
    echo "$LARGE_IMAGES" | while read img; do
        if [[ -f "$img" ]]; then
            optimize_image "$img"
        fi
    done
else
    echo "   ℹ️  Nenhuma imagem grande encontrada"
fi

echo ""
echo "✅ Otimização concluída!"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "   Processadas: $PROCESSED"
echo "   Já otimizadas (puladas): $SKIPPED"
if [[ $TOTAL_SAVED -gt 0 ]]; then
    TOTAL_SAVED_MB=$(awk "BEGIN {printf \"%.2f\", $TOTAL_SAVED/1024/1024}")
    echo "   Economia total: ${TOTAL_SAVED_MB}MB"
fi
if [[ $TOTAL_ORIGINAL -gt 0 ]]; then
    TOTAL_ORIGINAL_MB=$(awk "BEGIN {printf \"%.2f\", $TOTAL_ORIGINAL/1024/1024}")
    echo "   Tamanho original total: ${TOTAL_ORIGINAL_MB}MB"
fi
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "💡 Próximo passo: Re-testar no PageSpeed Insights para validar melhorias"

