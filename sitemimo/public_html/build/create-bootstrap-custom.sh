#!/bin/bash
# Script para criar build customizado do Bootstrap apenas com Carousel e Tab
# Uso: ./build/create-bootstrap-custom.sh

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"
BOOTSTRAP_DIST="$PROJECT_ROOT/bootstrap/bootstrap/js/dist"
OUTPUT_DIR="$PROJECT_ROOT/bootstrap"
OUTPUT_FILE="$OUTPUT_DIR/bootstrap-custom.min.js"

echo "🔧 Criando build customizado do Bootstrap (Carousel + Tab)..."

# Verificar se os arquivos compilados existem
if [ ! -f "$BOOTSTRAP_DIST/util.js" ]; then
    echo "❌ Erro: $BOOTSTRAP_DIST/util.js não encontrado"
    exit 1
fi

if [ ! -f "$BOOTSTRAP_DIST/carousel.js" ]; then
    echo "❌ Erro: $BOOTSTRAP_DIST/carousel.js não encontrado"
    exit 1
fi

if [ ! -f "$BOOTSTRAP_DIST/tab.js" ]; then
    echo "❌ Erro: $BOOTSTRAP_DIST/tab.js não encontrado"
    exit 1
fi

# Criar arquivo temporário para build
TEMP_FILE=$(mktemp)

# Header do arquivo
cat > "$TEMP_FILE" << 'EOF'
/**
 * Bootstrap Custom Build (v4.1.3)
 * Includes only: Carousel, Tab, Util
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 */
(function (global, factory) {
  typeof exports === 'object' && typeof module !== 'undefined' ? factory(require('jquery')) :
  typeof define === 'function' && define.amd ? define(['jquery'], factory) :
  (global = typeof globalThis !== 'undefined' ? globalThis : global || self, factory(global.jQuery));
})(this, (function ($) {
  'use strict';

  // jQuery version check
  if (typeof $ === 'undefined') {
    throw new TypeError('Bootstrap\'s JavaScript requires jQuery. jQuery must be included before Bootstrap\'s JavaScript.');
  }

  const version = $.fn.jquery.split(' ')[0].split('.');
  const minMajor = 1;
  const ltMajor = 2;
  const minMinor = 9;
  const minPatch = 1;
  const maxMajor = 4;

  if (version[0] < ltMajor && version[1] < minMinor || version[0] === minMajor && version[1] === minMinor && version[2] < minPatch || version[0] >= maxMajor) {
    throw new Error('Bootstrap\'s JavaScript requires at least jQuery v1.9.1 but less than v4.0.0');
  }

EOF

# Adicionar Util (necessário para Carousel e Tab)
echo "  // Util" >> "$TEMP_FILE"
cat "$BOOTSTRAP_DIST/util.js" >> "$TEMP_FILE"
echo "" >> "$TEMP_FILE"

# Adicionar Carousel
echo "  // Carousel" >> "$TEMP_FILE"
cat "$BOOTSTRAP_DIST/carousel.js" >> "$TEMP_FILE"
echo "" >> "$TEMP_FILE"

# Adicionar Tab
echo "  // Tab" >> "$TEMP_FILE"
cat "$BOOTSTRAP_DIST/tab.js" >> "$TEMP_FILE"
echo "" >> "$TEMP_FILE"

# Footer do arquivo
cat >> "$TEMP_FILE" << 'EOF'

  // Auto-initialize data-api components
  $(document).ready(function() {
    // Initialize carousels
    $('[data-ride="carousel"]').each(function() {
      var $carousel = $(this);
      $carousel.carousel();
    });

    // Initialize tabs
    $('[data-toggle="tab"], [data-toggle="pill"]').on('click', function(e) {
      e.preventDefault();
      $(this).tab('show');
    });
  });

}));
EOF

# Minificar usando terser
if command -v npx &> /dev/null; then
    echo "  → Minificando..."
    npx --yes terser "$TEMP_FILE" --compress --mangle --output "$OUTPUT_FILE" || {
        echo "⚠️  Aviso: Falha ao minificar, usando versão não minificada"
        cp "$TEMP_FILE" "$OUTPUT_FILE"
    }
else
    echo "⚠️  Aviso: npx não encontrado, usando versão não minificada"
    cp "$TEMP_FILE" "$OUTPUT_FILE"
fi

# Limpar arquivo temporário
rm -f "$TEMP_FILE"

# Calcular tamanho
if [ -f "$OUTPUT_FILE" ]; then
    SIZE=$(stat -f%z "$OUTPUT_FILE" 2>/dev/null || stat -c%s "$OUTPUT_FILE" 2>/dev/null)
    SIZE_KB=$((SIZE / 1024))
    ORIGINAL_SIZE=$(stat -f%z "$PROJECT_ROOT/bootstrap/bootstrap/dist/js/bootstrap.min.js" 2>/dev/null || stat -c%s "$PROJECT_ROOT/bootstrap/bootstrap/dist/js/bootstrap.min.js" 2>/dev/null)
    ORIGINAL_SIZE_KB=$((ORIGINAL_SIZE / 1024))
    SAVINGS=$((ORIGINAL_SIZE - SIZE))
    SAVINGS_KB=$((SAVINGS / 1024))
    
    echo ""
    echo "✅ Build customizado criado!"
    echo "   📁 Arquivo: $OUTPUT_FILE"
    echo "   📊 Tamanho: ${SIZE_KB} KiB (original: ${ORIGINAL_SIZE_KB} KiB)"
    echo "   💾 Economia: ${SAVINGS_KB} KiB (${SAVINGS} bytes)"
    echo ""
    echo "⚠️  IMPORTANTE: Atualize os arquivos PHP para usar bootstrap-custom.min.js"
else
    echo "❌ Erro: Falha ao criar build customizado"
    exit 1
fi
