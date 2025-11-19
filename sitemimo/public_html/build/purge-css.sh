#!/bin/bash
# Script para remover CSS não utilizado usando PurgeCSS
# Uso: ./build/purge-css.sh

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"
OUTPUT_DIR="$PROJECT_ROOT/css/purged"
CONFIG_FILE="$PROJECT_ROOT/purgecss.config.js"

echo "🧹 Removendo CSS não utilizado com PurgeCSS..."

# Verificar se purgecss está instalado
if ! command -v purgecss &> /dev/null; then
    echo "⚠️  purgecss não encontrado. Instalando via npm..."
    npm install -g purgecss
fi

# Criar diretório de saída
mkdir -p "$OUTPUT_DIR"

# CSS para purgar
CSS_FILES=(
    "$PROJECT_ROOT/product.css"
    "$PROJECT_ROOT/css/modules/dark-mode.css"
    "$PROJECT_ROOT/css/modules/animations.css"
    "$PROJECT_ROOT/css/modules/mobile-ui-improvements.css"
    "$PROJECT_ROOT/css/modules/accessibility-fixes.css"
)

# Verificar se arquivo de configuração existe
if [ -f "$CONFIG_FILE" ]; then
    echo "  → Usando configuração: $CONFIG_FILE"
    USE_CONFIG=true
else
    echo "  ⚠️  Arquivo de configuração não encontrado, usando parâmetros padrão"
    USE_CONFIG=false
fi

# Purgar cada arquivo CSS
for css_file in "${CSS_FILES[@]}"; do
    if [ -f "$css_file" ]; then
        filename=$(basename "$css_file")
        output_file="$OUTPUT_DIR/$filename"
        
        echo ""
        echo "  → Purificando $filename..."
        
        # Obter tamanho original
        original_size=$(stat -f%z "$css_file" 2>/dev/null || stat -c%s "$css_file" 2>/dev/null)
        
        # Executar PurgeCSS
        if [ "$USE_CONFIG" = true ]; then
            # Usar configuração do arquivo
            purgecss --config "$CONFIG_FILE" --css "$css_file" --output "$OUTPUT_DIR" || {
                echo "    ⚠️  Erro ao usar config, tentando método alternativo..."
                purgecss \
                    --css "$css_file" \
                    --content "$PROJECT_ROOT/**/*.php" \
                    --output "$OUTPUT_DIR" \
                    --font-face \
                    --keyframes \
                    --variables \
                    --safelist "carousel" "fade-in" "fade-out" "dark-mode" "light-mode" "visible" "hidden" "testimonial" "review" "fa-" "fas" "far" "fab"
            }
        else
            # Usar parâmetros padrão
            purgecss \
                --css "$css_file" \
                --content "$PROJECT_ROOT/**/*.php" \
                --output "$OUTPUT_DIR" \
                --font-face \
                --keyframes \
                --variables \
                --safelist "carousel" "fade-in" "fade-out" "dark-mode" "light-mode" "visible" "hidden" "testimonial" "review" "fa-" "fas" "far" "fab"
        fi
        
        # Calcular economia
        if [ -f "$output_file" ]; then
            purged_size=$(stat -f%z "$output_file" 2>/dev/null || stat -c%s "$output_file" 2>/dev/null)
            savings=$((original_size - purged_size))
            savings_percent=$((savings * 100 / original_size))
            
            echo "    ✓ Original: ${original_size} bytes (${original_size} / 1024) KiB"
            echo "    ✓ Purged: ${purged_size} bytes ($((purged_size / 1024)) KiB)"
            echo "    ✓ Economia: ${savings} bytes ($((savings / 1024)) KiB - ${savings_percent}%)"
        else
            echo "    ⚠️  Arquivo purificado não encontrado em $output_file"
        fi
    else
        echo "  ⚠️  Arquivo não encontrado: $css_file"
    fi
done

echo ""
echo "✅ PurgeCSS concluído!"
echo "📊 Arquivos purificados salvos em: $OUTPUT_DIR"
echo ""
echo "⚠️  IMPORTANTE: Revise os arquivos purificados antes de usar em produção!"
echo "   Alguns estilos podem ser removidos incorretamente se usados via JavaScript."

