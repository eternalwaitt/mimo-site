#!/bin/bash
# Script para comprimir imagens antes de converter para AVIF/WebP
# Otimiza imagens JPG/PNG para reduzir tamanho antes da conversão

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PUBLIC_DIR="$SCRIPT_DIR/../"
IMG_DIR="$PUBLIC_DIR/img"

echo "🖼️  Comprimindo imagens em $IMG_DIR..."

# Verificar se as ferramentas estão instaladas
command -v jpegoptim >/dev/null 2>&1 || { echo "⚠️  jpegoptim não encontrado. Instalando..."; brew install jpegoptim 2>/dev/null || echo "❌ Instale jpegoptim manualmente"; }
command -v optipng >/dev/null 2>&1 || { echo "⚠️  optipng não encontrado. Instalando..."; brew install optipng 2>/dev/null || echo "❌ Instale optipng manualmente"; }

# Comprimir JPG
echo "📸 Comprimindo imagens JPG..."
find "$IMG_DIR" -type f \( -iname "*.jpg" -o -iname "*.jpeg" \) ! -name "*-compressed.*" -exec jpegoptim --max=85 --strip-all --preserve --force {} \;

# Comprimir PNG
echo "🖼️  Comprimindo imagens PNG..."
find "$IMG_DIR" -type f -iname "*.png" ! -name "*-compressed.*" -exec optipng -o2 -strip all {} \;

echo "✅ Compressão concluída!"
echo "💡 Próximo passo: Execute generate-avif-main-images.sh para gerar versões AVIF"
