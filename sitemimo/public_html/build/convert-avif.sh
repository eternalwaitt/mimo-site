#!/bin/bash
# Convert images to AVIF format (better compression than WebP)
# Usage: ./convert-avif.sh [quality] [directory]
# Quality: 0-100 (default: 80)
# Directory: path to images (default: ../img)

QUALITY=${1:-80}
IMG_DIR=${2:-../img}

# Check if avifenc is available (from libavif)
if ! command -v avifenc &> /dev/null; then
    echo "Error: avifenc not found."
    echo "Install libavif:"
    echo "  macOS: brew install libavif"
    echo "  Linux: sudo apt-get install libavif-bin"
    echo ""
    echo "Falling back to ImageMagick (slower, but works)..."
    
    if ! command -v convert &> /dev/null && ! command -v magick &> /dev/null; then
        echo "Error: ImageMagick also not found. Install with: brew install imagemagick"
        exit 1
    fi
    
    USE_IMAGEMAGICK=true
    CONVERT_CMD="convert"
    if command -v magick &> /dev/null; then
        CONVERT_CMD="magick"
    fi
else
    USE_IMAGEMAGICK=false
fi

echo "Converting images to AVIF (quality: $QUALITY) in $IMG_DIR..."

# Find and convert JPG/JPEG files
find "$IMG_DIR" -type f \( -iname "*.jpg" -o -iname "*.jpeg" \) ! -name "*-2x.*" ! -name "*-3x.*" | while read -r img; do
    avif="${img%.*}.avif"
    if [ ! -f "$avif" ] || [ "$img" -nt "$avif" ]; then
        echo "Converting: $img -> $avif"
        if [ "$USE_IMAGEMAGICK" = true ]; then
            $CONVERT_CMD "$img" -quality "$QUALITY" "$avif" 2>/dev/null || echo "Failed: $img"
        else
            avifenc -q "$QUALITY" "$img" "$avif" 2>/dev/null || echo "Failed: $img"
        fi
    else
        echo "Skipping (already exists and up-to-date): $avif"
    fi
done

# Find and convert PNG files
find "$IMG_DIR" -type f -iname "*.png" ! -name "*-2x.*" ! -name "*-3x.*" | while read -r img; do
    avif="${img%.*}.avif"
    if [ ! -f "$avif" ] || [ "$img" -nt "$avif" ]; then
        echo "Converting: $img -> $avif"
        if [ "$USE_IMAGEMAGICK" = true ]; then
            $CONVERT_CMD "$img" -quality "$QUALITY" "$avif" 2>/dev/null || echo "Failed: $img"
        else
            avifenc -q "$QUALITY" "$img" "$avif" 2>/dev/null || echo "Failed: $img"
        fi
    else
        echo "Skipping (already exists and up-to-date): $avif"
    fi
done

# Also convert 2x and 3x versions if they exist
find "$IMG_DIR" -type f \( -iname "*-2x.jpg" -o -iname "*-2x.jpeg" -o -iname "*-2x.png" \) | while read -r img; do
    avif="${img%.*}.avif"
    if [ ! -f "$avif" ] || [ "$img" -nt "$avif" ]; then
        echo "Converting: $img -> $avif"
        if [ "$USE_IMAGEMAGICK" = true ]; then
            $CONVERT_CMD "$img" -quality "$QUALITY" "$avif" 2>/dev/null || echo "Failed: $img"
        else
            avifenc -q "$QUALITY" "$img" "$avif" 2>/dev/null || echo "Failed: $img"
        fi
    fi
done

find "$IMG_DIR" -type f \( -iname "*-3x.jpg" -o -iname "*-3x.jpeg" -o -iname "*-3x.png" \) | while read -r img; do
    avif="${img%.*}.avif"
    if [ ! -f "$avif" ] || [ "$img" -nt "$avif" ]; then
        echo "Converting: $img -> $avif"
        if [ "$USE_IMAGEMAGICK" = true ]; then
            $CONVERT_CMD "$img" -quality "$QUALITY" "$avif" 2>/dev/null || echo "Failed: $img"
        else
            avifenc -q "$QUALITY" "$img" "$avif" 2>/dev/null || echo "Failed: $img"
        fi
    fi
done

echo "AVIF conversion complete!"

