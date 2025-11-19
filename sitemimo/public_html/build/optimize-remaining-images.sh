#!/bin/bash

# Script para otimizar imagens restantes que ainda não foram convertidas para AVIF/WebP
# Foca nas imagens que aparecem no PageSpeed Insights como oportunidades de melhoria

echo "🖼️  Otimizando imagens restantes..."

IMG_DIR="../img"
PROCESSED=0
SKIPPED=0
TOTAL_SAVED=0

# Lista de imagens prioritárias (baseado no PageSpeed Insights)
PRIORITY_IMAGES=(
    "mimo5.png"
    "categoria_facial.png"
    "menu_estetica_corporal.png"
    "MENU-ESMALTERIA.png"
    "menu_salao.png"
    "micro.png"
    "categoria_cilios.png"
    "esmalteria.png"
    "corporal.png"
    "salao.png"
    "facial.png"
    "cilios.png"
    "bgheader.jpg"
    "bgheader.png"
    "header_dezembro_mobile.png"
    "logobranco1.png"
)

# Encontrar outras imagens grandes que precisam otimização
echo "🔍 Buscando outras imagens grandes (>100KB) que precisam otimização..."
LARGE_IMAGES=$(find "$IMG_DIR" -type f \( -name "*.jpg" -o -name "*.png" \) ! -name "*_*" ! -path "*/mobile_promocional/*" ! -path "*/bootstrap/*" -size +100k 2>/dev/null | head -10)

if [[ -n "$LARGE_IMAGES" ]]; then
    echo "📋 Imagens grandes encontradas:"
    echo "$LARGE_IMAGES" | while read img; do
        if [[ -f "$img" ]]; then
            BASENAME=$(basename "$img")
            DIR=$(dirname "$img")
            EXT="${BASENAME##*.}"
            BASENAME_NO_EXT="${BASENAME%.*}"
            
            # Verificar se já tem AVIF/WebP
            if [[ ! -f "$DIR/$BASENAME_NO_EXT.avif" ]] || [[ ! -f "$DIR/$BASENAME_NO_EXT.webp" ]]; then
                echo "   - $BASENAME (precisa otimização)"
                optimize_image "$img"
            fi
        fi
    done
fi

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
    echo "[$PROCESSED] 🔄 Processando: $FILENAME"
    
    # Converter para WebP
    if command -v cwebp &> /dev/null; then
        if [[ ! -f "$DIR/$BASENAME.webp" ]]; then
            cwebp -q 80 "$IMG_PATH" -o "$DIR/$BASENAME.webp" 2>/dev/null
            if [[ $? -eq 0 ]]; then
                echo "   ✅ WebP criado"
            fi
        fi
    fi
    
    # Converter para AVIF
    if command -v avifenc &> /dev/null; then
        if [[ ! -f "$DIR/$BASENAME.avif" ]]; then
            avifenc -j all -d 8 -y 444 -c aom --min 0 --max 63 -a cq-level=30 -o "$DIR/$BASENAME.avif" "$IMG_PATH" 2>/dev/null
            if [[ $? -eq 0 ]]; then
                echo "   ✅ AVIF criado"
            fi
        fi
    elif command -v convert &> /dev/null; then
        if [[ ! -f "$DIR/$BASENAME.avif" ]]; then
            convert "$IMG_PATH" -quality 75 "$DIR/$BASENAME.avif" 2>/dev/null
            if [[ $? -eq 0 ]]; then
                echo "   ✅ AVIF criado (ImageMagick)"
            fi
        fi
    fi
    
    # Comprimir original
    if [[ "$EXT" == "png" ]] && command -v optipng &> /dev/null; then
        ORIGINAL_SIZE=$(stat -f%z "$IMG_PATH" 2>/dev/null || stat -c%s "$IMG_PATH" 2>/dev/null)
        optipng -o7 "$IMG_PATH" 2>/dev/null
        COMPRESSED_SIZE=$(stat -f%z "$IMG_PATH" 2>/dev/null || stat -c%s "$IMG_PATH" 2>/dev/null)
        if [[ $ORIGINAL_SIZE -gt $COMPRESSED_SIZE ]]; then
            SAVINGS=$((ORIGINAL_SIZE - COMPRESSED_SIZE))
            TOTAL_SAVED=$((TOTAL_SAVED + SAVINGS))
            echo "   💾 PNG comprimido: $(numfmt --to=iec-i --suffix=B $SAVINGS 2>/dev/null || echo "${SAVINGS}B")"
        fi
    elif [[ "$EXT" == "jpg" ]] || [[ "$EXT" == "jpeg" ]]; then
        if command -v jpegoptim &> /dev/null; then
            ORIGINAL_SIZE=$(stat -f%z "$IMG_PATH" 2>/dev/null || stat -c%s "$IMG_PATH" 2>/dev/null)
            jpegoptim --max=85 --strip-all "$IMG_PATH" 2>/dev/null
            COMPRESSED_SIZE=$(stat -f%z "$IMG_PATH" 2>/dev/null || stat -c%s "$IMG_PATH" 2>/dev/null)
            if [[ $ORIGINAL_SIZE -gt $COMPRESSED_SIZE ]]; then
                SAVINGS=$((ORIGINAL_SIZE - COMPRESSED_SIZE))
                TOTAL_SAVED=$((TOTAL_SAVED + SAVINGS))
                echo "   💾 JPG comprimido: $(numfmt --to=iec-i --suffix=B $SAVINGS 2>/dev/null || echo "${SAVINGS}B")"
            fi
        fi
    fi
    
    echo "   ✅ Concluído: $FILENAME"
    echo ""
}

# Processar imagens prioritárias primeiro
echo "📋 Processando imagens prioritárias..."
for img in "${PRIORITY_IMAGES[@]}"; do
    if [[ -f "$IMG_DIR/$img" ]]; then
        optimize_image "$IMG_DIR/$img"
    fi
done

echo ""
echo "✅ Otimização concluída!"
echo "   Processadas: $PROCESSED"
echo "   Já otimizadas (puladas): $SKIPPED"
if [[ $TOTAL_SAVED -gt 0 ]]; then
    echo "   Economia total: $(numfmt --to=iec-i --suffix=B $TOTAL_SAVED 2>/dev/null || echo "${TOTAL_SAVED}B")"
fi

