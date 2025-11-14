#!/bin/bash
# Convert images to WebP format
# Usage: ./convert-webp.sh [quality] [directory]
# Quality: 0-100 (default: 85)
# Directory: path to images (default: ../img)

QUALITY=${1:-85}
IMG_DIR=${2:-../img}

if ! command -v cwebp &> /dev/null; then
    echo "Error: cwebp not found. Install with: brew install webp"
    exit 1
fi

echo "Converting images to WebP (quality: $QUALITY) in $IMG_DIR..."

# Find and convert JPG/JPEG files
find "$IMG_DIR" -type f \( -iname "*.jpg" -o -iname "*.jpeg" \) | while read -r img; do
    webp="${img%.*}.webp"
    if [ ! -f "$webp" ] || [ "$img" -nt "$webp" ]; then
        echo "Converting: $img -> $webp"
        cwebp -q "$QUALITY" "$img" -o "$webp" 2>/dev/null || echo "Failed: $img"
    else
        echo "Skipping (already exists and up-to-date): $webp"
    fi
done

# Find and convert PNG files
find "$IMG_DIR" -type f -iname "*.png" | while read -r img; do
    webp="${img%.*}.webp"
    if [ ! -f "$webp" ] || [ "$img" -nt "$webp" ]; then
        echo "Converting: $img -> $webp"
        cwebp -q "$QUALITY" "$img" -o "$webp" 2>/dev/null || echo "Failed: $img"
    else
        echo "Skipping (already exists and up-to-date): $webp"
    fi
done

echo "WebP conversion complete!"

