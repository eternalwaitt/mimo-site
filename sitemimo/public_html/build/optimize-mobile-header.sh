#!/bin/bash
# Script para otimizar header_dezembro_mobile.png (LCP element no mobile)
# Esta imagem é crítica para LCP no mobile e precisa ser otimizada

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PUBLIC_DIR="$SCRIPT_DIR/../"
IMG_DIR="$PUBLIC_DIR/img"
IMG_FILE="$IMG_DIR/header_dezembro_mobile.png"

echo "🖼️  Otimizando header_dezembro_mobile.png (LCP element mobile)..."

if [ ! -f "$IMG_FILE" ]; then
    echo "❌ Arquivo não encontrado: $IMG_FILE"
    exit 1
fi

# Verificar tamanho original
ORIGINAL_SIZE=$(stat -f%z "$IMG_FILE" 2>/dev/null || stat -c%s "$IMG_FILE" 2>/dev/null)
echo "📊 Tamanho original: $(numfmt --to=iec-i --suffix=B $ORIGINAL_SIZE 2>/dev/null || echo "${ORIGINAL_SIZE} bytes")"

# Comprimir PNG original
echo "📸 Comprimindo PNG..."
optipng -o2 -strip all "$IMG_FILE" || echo "⚠️  optipng não disponível, pulando compressão PNG"

# Verificar tamanho após compressão
COMPRESSED_SIZE=$(stat -f%z "$IMG_FILE" 2>/dev/null || stat -c%s "$IMG_FILE" 2>/dev/null)
SAVINGS=$((ORIGINAL_SIZE - COMPRESSED_SIZE))
echo "📊 Tamanho após compressão: $(numfmt --to=iec-i --suffix=B $COMPRESSED_SIZE 2>/dev/null || echo "${COMPRESSED_SIZE} bytes")"
echo "💾 Economia: $(numfmt --to=iec-i --suffix=B $SAVINGS 2>/dev/null || echo "${SAVINGS} bytes")"

# Converter para WebP
echo "🔄 Convertendo para WebP..."
if command -v cwebp >/dev/null 2>&1; then
    cwebp -q 85 -m 6 "$IMG_FILE" -o "$IMG_DIR/header_dezembro_mobile.webp"
    WEBP_SIZE=$(stat -f%z "$IMG_DIR/header_dezembro_mobile.webp" 2>/dev/null || stat -c%s "$IMG_DIR/header_dezembro_mobile.webp" 2>/dev/null)
    echo "✅ WebP criado: $(numfmt --to=iec-i --suffix=B $WEBP_SIZE 2>/dev/null || echo "${WEBP_SIZE} bytes")"
else
    echo "⚠️  cwebp não encontrado. Instale: brew install webp"
fi

# Converter para AVIF
echo "🔄 Convertendo para AVIF..."
if command -v avifenc >/dev/null 2>&1; then
    avifenc --min 0 --max 63 -a end-usage=q -a cq-level=30 -a tune=ssim "$IMG_FILE" "$IMG_DIR/header_dezembro_mobile.avif"
    AVIF_SIZE=$(stat -f%z "$IMG_DIR/header_dezembro_mobile.avif" 2>/dev/null || stat -c%s "$IMG_DIR/header_dezembro_mobile.avif" 2>/dev/null)
    echo "✅ AVIF criado: $(numfmt --to=iec-i --suffix=B $AVIF_SIZE 2>/dev/null || echo "${AVIF_SIZE} bytes")"
elif command -v magick >/dev/null 2>&1; then
    magick "$IMG_FILE" -quality 80 "$IMG_DIR/header_dezembro_mobile.avif"
    AVIF_SIZE=$(stat -f%z "$IMG_DIR/header_dezembro_mobile.avif" 2>/dev/null || stat -c%s "$IMG_DIR/header_dezembro_mobile.avif" 2>/dev/null)
    echo "✅ AVIF criado (ImageMagick): $(numfmt --to=iec-i --suffix=B $AVIF_SIZE 2>/dev/null || echo "${AVIF_SIZE} bytes")"
else
    echo "⚠️  avifenc/ImageMagick não encontrado. Instale: brew install libavif ou brew install imagemagick"
fi

echo ""
echo "✅ Otimização concluída!"
echo "💡 Próximo passo: Atualizar CSS para usar WebP/AVIF com fallback PNG"

