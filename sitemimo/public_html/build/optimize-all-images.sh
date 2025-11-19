#!/bin/bash

# Script para otimizar TODAS as imagens grandes do site
# Converte para WebP e AVIF, comprime PNG/JPG
# 
# Uso: ./optimize-all-images.sh

set -e  # Exit on error
set -o pipefail  # Exit on pipe failure

IMG_DIR="../img"
TOTAL_SAVED=0
TOTAL_FILES=0
PROCESSED=0
START_TIME=$(date +%s)

# Timeout para comandos (5 minutos por imagem)
# macOS não tem timeout nativo, usar gtimeout se disponível, senão sem timeout
if command -v gtimeout &> /dev/null; then
    TIMEOUT_CMD="gtimeout 300"
elif command -v timeout &> /dev/null; then
    TIMEOUT_CMD="timeout 300"
else
    # Sem timeout - confiar que os comandos terminam
    TIMEOUT_CMD=""
fi

echo "🖼️  Otimizando TODAS as imagens grandes do site..."
echo "📅 Início: $(date)"
echo ""

# Função para otimizar uma imagem
optimize_image() {
    local IMG_PATH="$1"
    local FILENAME=$(basename "$IMG_PATH")
    local DIR=$(dirname "$IMG_PATH")
    local BASENAME="${FILENAME%.*}"
    local EXT="${FILENAME##*.}"
    
    PROCESSED=$((PROCESSED + 1))
    echo "[$PROCESSED] 🔄 Processando: $FILENAME"
    
    # Pular se já for WebP ou AVIF
    if [[ "$EXT" == "webp" ]] || [[ "$EXT" == "avif" ]]; then
        echo "   ⏭️  Pulado (já é formato otimizado)"
        return
    fi
    
    # Pular se já existir versão otimizada
    if [[ -f "$DIR/$BASENAME.webp" ]] && [[ -f "$DIR/$BASENAME.avif" ]]; then
        echo "   ⏭️  Já otimizado (WebP e AVIF existem)"
        return
    fi
    
    TOTAL_FILES=$((TOTAL_FILES + 1))
    
    if [[ ! -f "$IMG_PATH" ]]; then
        echo "   ❌ Arquivo não encontrado: $IMG_PATH"
        return
    fi
    
    ORIGINAL_SIZE=$(stat -f%z "$IMG_PATH" 2>/dev/null || stat -c%s "$IMG_PATH" 2>/dev/null)
    ORIGINAL_SIZE_MB=$(echo "scale=2; $ORIGINAL_SIZE / 1048576" | bc 2>/dev/null || echo "?")
    
    echo "   📊 Tamanho original: ${ORIGINAL_SIZE_MB}MB ($(numfmt --to=iec-i --suffix=B $ORIGINAL_SIZE 2>/dev/null || echo "${ORIGINAL_SIZE}B"))"
    
    # 1. Comprimir PNG
    if [[ "$EXT" == "png" ]] && command -v optipng &> /dev/null; then
        echo "   🔧 Comprimindo PNG..."
        if [[ -n "$TIMEOUT_CMD" ]]; then
            if $TIMEOUT_CMD optipng -o7 -quiet "$IMG_PATH" 2>&1 | head -5; then
                SUCCESS=true
            else
                SUCCESS=false
            fi
        else
            if optipng -o7 -quiet "$IMG_PATH" 2>&1 | head -5; then
                SUCCESS=true
            else
                SUCCESS=false
            fi
        fi
        if [[ "$SUCCESS" == "true" ]]; then
            COMPRESSED_SIZE=$(stat -f%z "$IMG_PATH" 2>/dev/null || stat -c%s "$IMG_PATH" 2>/dev/null)
            SAVINGS=$((ORIGINAL_SIZE - COMPRESSED_SIZE))
            if [[ $SAVINGS -gt 0 ]]; then
                TOTAL_SAVED=$((TOTAL_SAVED + SAVINGS))
                echo "   ✅ PNG comprimido: $(numfmt --to=iec-i --suffix=B $SAVINGS 2>/dev/null || echo "${SAVINGS}B") economizados"
            fi
        else
            echo "   ⚠️  Timeout ou erro na compressão PNG"
        fi
    fi
    
    # 2. Comprimir JPG
    if [[ "$EXT" == "jpg" ]] || [[ "$EXT" == "jpeg" ]]; then
        echo "   🔧 Comprimindo JPG..."
        if command -v jpegoptim &> /dev/null; then
            if [[ -n "$TIMEOUT_CMD" ]]; then
                $TIMEOUT_CMD jpegoptim --max=85 --strip-all --quiet "$IMG_PATH" 2>&1 | head -5 && echo "   ✅ JPG comprimido" || echo "   ⚠️  Timeout ou erro na compressão JPG"
            else
                jpegoptim --max=85 --strip-all --quiet "$IMG_PATH" 2>&1 | head -5 && echo "   ✅ JPG comprimido" || echo "   ⚠️  Erro na compressão JPG"
            fi
        elif command -v convert &> /dev/null; then
            if [[ -n "$TIMEOUT_CMD" ]]; then
                $TIMEOUT_CMD convert "$IMG_PATH" -quality 85 -strip "$IMG_PATH.tmp" && mv "$IMG_PATH.tmp" "$IMG_PATH" 2>&1 | head -5 && echo "   ✅ JPG comprimido (ImageMagick)" || echo "   ⚠️  Timeout ou erro na compressão JPG"
            else
                convert "$IMG_PATH" -quality 85 -strip "$IMG_PATH.tmp" && mv "$IMG_PATH.tmp" "$IMG_PATH" 2>&1 | head -5 && echo "   ✅ JPG comprimido (ImageMagick)" || echo "   ⚠️  Erro na compressão JPG"
            fi
        fi
    fi
    
    # 3. Converter para WebP
    if command -v cwebp &> /dev/null; then
        WEBP_PATH="$DIR/$BASENAME.webp"
        if [[ ! -f "$WEBP_PATH" ]]; then
            echo "   🔄 Convertendo para WebP..."
            if [[ -n "$TIMEOUT_CMD" ]]; then
                if $TIMEOUT_CMD cwebp -q 80 -quiet "$IMG_PATH" -o "$WEBP_PATH" 2>&1 | head -5; then
                    SUCCESS=true
                else
                    SUCCESS=false
                fi
            else
                if cwebp -q 80 -quiet "$IMG_PATH" -o "$WEBP_PATH" 2>&1 | head -5; then
                    SUCCESS=true
                else
                    SUCCESS=false
                fi
            fi
            if [[ "$SUCCESS" == "true" ]]; then
                if [[ -f "$WEBP_PATH" ]]; then
                    WEBP_SIZE=$(stat -f%z "$WEBP_PATH" 2>/dev/null || stat -c%s "$WEBP_PATH" 2>/dev/null)
                    WEBP_SAVINGS=$((ORIGINAL_SIZE - WEBP_SIZE))
                    if [[ $WEBP_SAVINGS -gt 0 ]]; then
                        TOTAL_SAVED=$((TOTAL_SAVED + WEBP_SAVINGS))
                        WEBP_SIZE_MB=$(echo "scale=2; $WEBP_SIZE / 1048576" | bc 2>/dev/null || echo "?")
                        echo "   ✅ WebP criado: ${WEBP_SIZE_MB}MB ($(numfmt --to=iec-i --suffix=B $WEBP_SAVINGS 2>/dev/null || echo "${WEBP_SAVINGS}B") economizados)"
                    fi
                fi
            else
                echo "   ⚠️  Timeout ou erro na conversão WebP"
            fi
        else
            echo "   ⏭️  WebP já existe"
        fi
    fi
    
    # 4. Converter para AVIF
    if command -v avifenc &> /dev/null; then
        AVIF_PATH="$DIR/$BASENAME.avif"
        if [[ ! -f "$AVIF_PATH" ]]; then
            echo "   🔄 Convertendo para AVIF..."
            if [[ -n "$TIMEOUT_CMD" ]]; then
                if $TIMEOUT_CMD avifenc -j all -d 8 -y 444 -c aom --min 0 --max 63 -a cq-level=30 -o "$AVIF_PATH" "$IMG_PATH" 2>&1 | head -5; then
                    SUCCESS=true
                else
                    SUCCESS=false
                fi
            else
                if avifenc -j all -d 8 -y 444 -c aom --min 0 --max 63 -a cq-level=30 -o "$AVIF_PATH" "$IMG_PATH" 2>&1 | head -5; then
                    SUCCESS=true
                else
                    SUCCESS=false
                fi
            fi
            if [[ "$SUCCESS" == "true" ]]; then
                if [[ -f "$AVIF_PATH" ]]; then
                    AVIF_SIZE=$(stat -f%z "$AVIF_PATH" 2>/dev/null || stat -c%s "$AVIF_PATH" 2>/dev/null)
                    AVIF_SAVINGS=$((ORIGINAL_SIZE - AVIF_SIZE))
                    if [[ $AVIF_SAVINGS -gt 0 ]]; then
                        TOTAL_SAVED=$((TOTAL_SAVED + AVIF_SAVINGS))
                        AVIF_SIZE_MB=$(echo "scale=2; $AVIF_SIZE / 1048576" | bc 2>/dev/null || echo "?")
                        echo "   ✅ AVIF criado: ${AVIF_SIZE_MB}MB ($(numfmt --to=iec-i --suffix=B $AVIF_SAVINGS 2>/dev/null || echo "${AVIF_SAVINGS}B") economizados)"
                    fi
                fi
            else
                echo "   ⚠️  Timeout ou erro na conversão AVIF"
            fi
        else
            echo "   ⏭️  AVIF já existe"
        fi
    elif command -v convert &> /dev/null; then
        AVIF_PATH="$DIR/$BASENAME.avif"
        if [[ ! -f "$AVIF_PATH" ]]; then
            echo "   🔄 Convertendo para AVIF (ImageMagick)..."
            if [[ -n "$TIMEOUT_CMD" ]]; then
                if $TIMEOUT_CMD convert "$IMG_PATH" -quality 75 "$AVIF_PATH" 2>&1 | head -5; then
                    SUCCESS=true
                else
                    SUCCESS=false
                fi
            else
                if convert "$IMG_PATH" -quality 75 "$AVIF_PATH" 2>&1 | head -5; then
                    SUCCESS=true
                else
                    SUCCESS=false
                fi
            fi
            if [[ "$SUCCESS" == "true" ]]; then
                if [[ -f "$AVIF_PATH" ]]; then
                    AVIF_SIZE=$(stat -f%z "$AVIF_PATH" 2>/dev/null || stat -c%s "$AVIF_PATH" 2>/dev/null)
                    AVIF_SAVINGS=$((ORIGINAL_SIZE - AVIF_SIZE))
                    if [[ $AVIF_SAVINGS -gt 0 ]]; then
                        TOTAL_SAVED=$((TOTAL_SAVED + AVIF_SAVINGS))
                        AVIF_SIZE_MB=$(echo "scale=2; $AVIF_SIZE / 1048576" | bc 2>/dev/null || echo "?")
                        echo "   ✅ AVIF criado (ImageMagick): ${AVIF_SIZE_MB}MB"
                    fi
                fi
            else
                echo "   ⚠️  Timeout ou erro na conversão AVIF"
            fi
        else
            echo "   ⏭️  AVIF já existe"
        fi
    fi
    
    echo "   ✅ Concluído: $FILENAME"
    echo ""
}

# Contar total de imagens primeiro
TOTAL_IMAGES=$(find "$IMG_DIR" -type f \( -iname "*.jpg" -o -iname "*.jpeg" -o -iname "*.png" \) ! -name "_*" | wc -l | tr -d ' ')
echo "📊 Total de imagens encontradas: $TOTAL_IMAGES"
echo ""

# Processar todas as imagens recursivamente
IMAGE_COUNT=0
find "$IMG_DIR" -type f \( -iname "*.jpg" -o -iname "*.jpeg" -o -iname "*.png" \) ! -name "_*" | while read -r IMG; do
    IMAGE_COUNT=$((IMAGE_COUNT + 1))
    
    # Pular se já existir versão otimizada
    BASENAME="${IMG%.*}"
    if [[ -f "$BASENAME.webp" ]] && [[ -f "$BASENAME.avif" ]]; then
        echo "[$IMAGE_COUNT/$TOTAL_IMAGES] ⏭️  $(basename "$IMG") já otimizado"
        continue
    fi
    
    optimize_image "$IMG"
    
    # Progress update a cada 5 imagens
    if [[ $((IMAGE_COUNT % 5)) -eq 0 ]]; then
        ELAPSED=$(($(date +%s) - START_TIME))
        echo "⏱️  Progresso: $IMAGE_COUNT/$TOTAL_IMAGES imagens processadas (${ELAPSED}s decorridos)"
        echo ""
    fi
done

END_TIME=$(date +%s)
DURATION=$((END_TIME - START_TIME))
MINUTES=$((DURATION / 60))
SECONDS=$((DURATION % 60))

echo ""
echo "✅ Otimização concluída!"
echo "📅 Fim: $(date)"
echo "⏱️  Duração: ${MINUTES}m ${SECONDS}s"
echo "📊 Total de arquivos processados: $TOTAL_FILES"
if [[ $TOTAL_SAVED -gt 0 ]]; then
    TOTAL_SAVED_MB=$(echo "scale=2; $TOTAL_SAVED / 1048576" | bc 2>/dev/null || echo "?")
    echo "💾 Economia total estimada: ${TOTAL_SAVED_MB}MB ($(numfmt --to=iec-i --suffix=B $TOTAL_SAVED 2>/dev/null || echo "${TOTAL_SAVED}B"))"
fi
echo ""
echo "💡 Próximo passo: Verificar se todas as imagens estão usando picture_webp() com width/height"
