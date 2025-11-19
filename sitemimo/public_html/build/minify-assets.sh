#!/bin/bash
# Script para minificar CSS e JavaScript
# Uso: ./build/minify-assets.sh

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"
MINIFIED_DIR="$PROJECT_ROOT/minified"

echo "🔨 Minificando assets..."

# Criar diretório minified se não existir
mkdir -p "$MINIFIED_DIR"

# Verificar se terser está instalado (para JS)
if ! command -v terser &> /dev/null; then
    echo "⚠️  terser não encontrado. Instalando via npm..."
    npm install -g terser
fi

# Verificar se csso está instalado (para CSS)
if ! command -v csso-cli &> /dev/null; then
    echo "⚠️  csso-cli não encontrado. Instalando via npm..."
    npm install -g csso-cli
fi

# Minificar JavaScript
echo "📦 Minificando JavaScript..."
for js_file in "$PROJECT_ROOT/js"/*.js; do
    if [ -f "$js_file" ]; then
        filename=$(basename "$js_file")
        output_file="$MINIFIED_DIR/${filename%.js}.min.js"
        
        echo "  → $filename"
        terser "$js_file" -o "$output_file" \
            --compress \
            --mangle \
            --comments false \
            --source-map "url=${filename%.js}.min.js.map"
        
        # Calcular economia
        original_size=$(stat -f%z "$js_file" 2>/dev/null || stat -c%s "$js_file" 2>/dev/null)
        minified_size=$(stat -f%z "$output_file" 2>/dev/null || stat -c%s "$output_file" 2>/dev/null)
        savings=$((original_size - minified_size))
        savings_percent=$((savings * 100 / original_size))
        
        echo "    ✓ Economia: ${savings} bytes (${savings_percent}%)"
    fi
done

# Minificar CSS
echo "🎨 Minificando CSS..."
for css_file in "$PROJECT_ROOT/css"/*.css "$PROJECT_ROOT"/*.css; do
    if [ -f "$css_file" ] && [[ "$css_file" != *".min.css" ]]; then
        filename=$(basename "$css_file")
        output_file="$MINIFIED_DIR/${filename%.css}.min.css"
        
        echo "  → $filename"
        csso "$css_file" --output "$output_file" --source-map file
        
        # Calcular economia
        original_size=$(stat -f%z "$css_file" 2>/dev/null || stat -c%s "$css_file" 2>/dev/null)
        minified_size=$(stat -f%z "$output_file" 2>/dev/null || stat -c%s "$output_file" 2>/dev/null)
        savings=$((original_size - minified_size))
        savings_percent=$((savings * 100 / original_size))
        
        echo "    ✓ Economia: ${savings} bytes (${savings_percent}%)"
    fi
done

# Minificar CSS em subdiretórios
if [ -d "$PROJECT_ROOT/css/modules" ]; then
    echo "📁 Minificando CSS modules..."
    for css_file in "$PROJECT_ROOT/css/modules"/*.css; do
        if [ -f "$css_file" ] && [[ "$css_file" != *".min.css" ]]; then
            filename=$(basename "$css_file")
            output_file="$MINIFIED_DIR/${filename%.css}.min.css"
            
            echo "  → modules/$filename"
            csso "$css_file" --output "$output_file" --source-map file
            
            original_size=$(stat -f%z "$css_file" 2>/dev/null || stat -c%s "$css_file" 2>/dev/null)
            minified_size=$(stat -f%z "$output_file" 2>/dev/null || stat -c%s "$output_file" 2>/dev/null)
            savings=$((original_size - minified_size))
            savings_percent=$((savings * 100 / original_size))
            
            echo "    ✓ Economia: ${savings} bytes (${savings_percent}%)"
        fi
    done
fi

echo ""
echo "✅ Minificação concluída!"
echo "📊 Arquivos minificados salvos em: $MINIFIED_DIR"

