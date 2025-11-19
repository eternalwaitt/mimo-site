#!/bin/bash
# Generate AVIF versions of main images
# This script converts the most important images to AVIF format for better performance
# Usage: ./generate-avif-main-images.sh

IMG_DIR="../img"
QUALITY=80

# Check if avifenc is available
if ! command -v avifenc &> /dev/null; then
    echo "Error: avifenc not found."
    echo "Install libavif:"
    echo "  macOS: brew install libavif"
    echo "  Linux: sudo apt-get install libavif-bin"
    exit 1
fi

echo "Generating AVIF versions of main images..."

# Main images to convert (hero, about, categories, services)
MAIN_IMAGES=(
    "bgheader.jpg"
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
)

for img in "${MAIN_IMAGES[@]}"; do
    img_path="$IMG_DIR/$img"
    avif_path="$IMG_DIR/${img%.*}.avif"
    
    if [ -f "$img_path" ]; then
        if [ ! -f "$avif_path" ] || [ "$img_path" -nt "$avif_path" ]; then
            echo "Converting: $img -> ${img%.*}.avif"
            avifenc -q "$QUALITY" "$img_path" "$avif_path" 2>/dev/null || echo "Failed: $img"
        else
            echo "Skipping (already exists and up-to-date): ${img%.*}.avif"
        fi
    else
        echo "Warning: Image not found: $img_path"
    fi
done

echo ""
echo "AVIF conversion complete!"
echo ""
echo "Next steps:"
echo "1. Verify AVIF files were created in $IMG_DIR"
echo "2. Test images in browser (Chrome/Edge support AVIF)"
echo "3. The picture_webp() function will automatically use AVIF if available"

