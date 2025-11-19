#!/bin/bash

# Script para otimizar imagens grandes que ainda não têm AVIF/WebP
# Foca nas imagens identificadas pelo PageSpeed Insights

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PUBLIC_DIR="$SCRIPT_DIR/../"

echo "🖼️  Otimizando imagens grandes sem AVIF/WebP..."
echo ""

# Lista de imagens grandes que precisam otimização
IMAGES_TO_OPTIMIZE=(
    "img/mobile_promocional/jan/promo_janeiro_estetica.png"
    "img/mobile_promocional/jan/promo_janeiro_cilios.png"
    "img/mobile_promocional/jan/promo_janeiro_cronograma-capilar.png"
    "img/mobile_promocional/jan/promo_janeiro_ferias1.png"
    "img/mobile_promocional/jan/promo_janeiro_ferias3.png"
    "img/mobile_promocional/jan/promo_janeiro_esmalteria.png"
    "img/mobile_promocional/dez/combofda_01.png"
    "img/mobile_promocional/dez/combofda_02.png"
    "img/mobile_promocional/dez/combofda_03.png"
    "img/mobile_promocional/dez/combofda_04.png"
    "img/mobile_promocional/dez/combofda_05.png"
    "img/categoria_facial.jpg"
    "img/cilios.png"
    "img/categoria_cilios.png"
    "img/facial.png"
    "img/menu_estetica_corporal.png"
)

PROCESSED=0
SKIPPED=0

for img_path in "${IMAGES_TO_OPTIMIZE[@]}"; do
    if [ ! -f "$PUBLIC_DIR/$img_path" ]; then
        echo "⚠️  Arquivo não encontrado: $img_path"
        continue
    fi
    
    FILENAME=$(basename "$img_path")
    DIR=$(dirname "$PUBLIC_DIR/$img_path")
    BASENAME="${FILENAME%.*}"
    
    # Verificar se já tem AVIF e WebP
    if [ -f "$DIR/$BASENAME.avif" ] && [ -f "$DIR/$BASENAME.webp" ]; then
        echo "⏭️  $FILENAME já otimizado"
        SKIPPED=$((SKIPPED + 1))
        continue
    fi
    
    PROCESSED=$((PROCESSED + 1))
    ORIGINAL_SIZE=$(stat -f%z "$PUBLIC_DIR/$img_path" 2>/dev/null || stat -c%s "$PUBLIC_DIR/$img_path" 2>/dev/null)
    SIZE_MB=$(awk "BEGIN {printf \"%.2f\", $ORIGINAL_SIZE/1024/1024}")
    
    echo "[$PROCESSED] 🔄 Processando: $FILENAME (${SIZE_MB}MB)"
    
    # Converter para WebP
    if command -v cwebp &> /dev/null; then
        if [ ! -f "$DIR/$BASENAME.webp" ]; then
            echo "   📦 Criando WebP..."
            cwebp -q 80 -m 6 "$PUBLIC_DIR/$img_path" -o "$DIR/$BASENAME.webp" 2>/dev/null
            if [ $? -eq 0 ]; then
                WEBP_SIZE=$(stat -f%z "$DIR/$BASENAME.webp" 2>/dev/null || stat -c%s "$DIR/$BASENAME.webp" 2>/dev/null)
                WEBP_MB=$(awk "BEGIN {printf \"%.2f\", $WEBP_SIZE/1024/1024}")
                SAVINGS=$((ORIGINAL_SIZE - WEBP_SIZE))
                echo "   ✅ WebP criado (${WEBP_MB}MB, economia: $(awk "BEGIN {printf \"%.2f\", $SAVINGS/1024/1024}")MB)"
            fi
        fi
    fi
    
    # Converter para AVIF
    if command -v avifenc &> /dev/null; then
        if [ ! -f "$DIR/$BASENAME.avif" ]; then
            echo "   📦 Criando AVIF..."
            avifenc -j all -d 8 -y 444 -c aom --min 0 --max 63 -a cq-level=30 -o "$DIR/$BASENAME.avif" "$PUBLIC_DIR/$img_path" 2>/dev/null
            if [ $? -eq 0 ]; then
                AVIF_SIZE=$(stat -f%z "$DIR/$BASENAME.avif" 2>/dev/null || stat -c%s "$DIR/$BASENAME.avif" 2>/dev/null)
                AVIF_MB=$(awk "BEGIN {printf \"%.2f\", $AVIF_SIZE/1024/1024}")
                SAVINGS=$((ORIGINAL_SIZE - AVIF_SIZE))
                echo "   ✅ AVIF criado (${AVIF_MB}MB, economia: $(awk "BEGIN {printf \"%.2f\", $SAVINGS/1024/1024}")MB)"
            fi
        fi
    elif command -v convert &> /dev/null; then
        if [ ! -f "$DIR/$BASENAME.avif" ]; then
            echo "   📦 Criando AVIF (ImageMagick)..."
            convert "$PUBLIC_DIR/$img_path" -quality 75 "$DIR/$BASENAME.avif" 2>/dev/null
            if [ $? -eq 0 ]; then
                AVIF_SIZE=$(stat -f%z "$DIR/$BASENAME.avif" 2>/dev/null || stat -c%s "$DIR/$BASENAME.avif" 2>/dev/null)
                AVIF_MB=$(awk "BEGIN {printf \"%.2f\", $AVIF_SIZE/1024/1024}")
                SAVINGS=$((ORIGINAL_SIZE - AVIF_SIZE))
                echo "   ✅ AVIF criado (${AVIF_MB}MB, economia: $(awk "BEGIN {printf \"%.2f\", $SAVINGS/1024/1024}")MB)"
            fi
        fi
    fi
    
    echo "   ✅ Concluído: $FILENAME"
    echo ""
done

echo "✅ Otimização concluída!"
echo "   Processadas: $PROCESSED"
echo "   Já otimizadas: $SKIPPED"

