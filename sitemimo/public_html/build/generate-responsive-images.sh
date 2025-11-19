#!/bin/bash
# Generate responsive images with multiple sizes (1x, 2x, 3x)
# Usage: ./generate-responsive-images.sh [source_dir] [output_dir]
# Creates: filename.ext (1x), filename-2x.ext (2x), filename-3x.ext (3x)

SOURCE_DIR=${1:-../img}
OUTPUT_DIR=${2:-$SOURCE_DIR}

# Check if ImageMagick is installed
if ! command -v convert &> /dev/null && ! command -v magick &> /dev/null; then
    echo "Error: ImageMagick not found. Install with: brew install imagemagick"
    exit 1
fi

CONVERT_CMD="convert"
if command -v magick &> /dev/null; then
    CONVERT_CMD="magick"
fi

echo "Generating responsive images in $SOURCE_DIR..."

# Process JPG/JPEG files
find "$SOURCE_DIR" -type f \( -iname "*.jpg" -o -iname "*.jpeg" \) ! -name "*-2x.*" ! -name "*-3x.*" | while read -r img; do
    dir=$(dirname "$img")
    filename=$(basename "$img")
    name="${filename%.*}"
    ext="${filename##*.}"
    
    # Get original dimensions
    width=$($CONVERT_CMD identify -format "%w" "$img" 2>/dev/null)
    height=$($CONVERT_CMD identify -format "%h" "$img" 2>/dev/null)
    
    if [ -z "$width" ] || [ -z "$height" ]; then
        echo "Skipping (cannot read dimensions): $img"
        continue
    fi
    
    # Generate 2x version (if original is large enough)
    if [ "$width" -ge 400 ] || [ "$height" -ge 400 ]; then
        output_2x="$dir/${name}-2x.${ext}"
        if [ ! -f "$output_2x" ] || [ "$img" -nt "$output_2x" ]; then
            echo "Generating 2x: $output_2x ($((width * 2))x$((height * 2)))"
            $CONVERT_CMD "$img" -resize "$((width * 2))x$((height * 2))>" -quality 85 "$output_2x" 2>/dev/null || echo "Failed: $img -> 2x"
        fi
    fi
    
    # Generate 3x version (if original is large enough)
    if [ "$width" -ge 600 ] || [ "$height" -ge 600 ]; then
        output_3x="$dir/${name}-3x.${ext}"
        if [ ! -f "$output_3x" ] || [ "$img" -nt "$output_3x" ]; then
            echo "Generating 3x: $output_3x ($((width * 3))x$((height * 3)))"
            $CONVERT_CMD "$img" -resize "$((width * 3))x$((height * 3))>" -quality 85 "$output_3x" 2>/dev/null || echo "Failed: $img -> 3x"
        fi
    fi
done

# Process PNG files
find "$SOURCE_DIR" -type f -iname "*.png" ! -name "*-2x.*" ! -name "*-3x.*" | while read -r img; do
    dir=$(dirname "$img")
    filename=$(basename "$img")
    name="${filename%.*}"
    ext="${filename##*.}"
    
    # Get original dimensions
    width=$($CONVERT_CMD identify -format "%w" "$img" 2>/dev/null)
    height=$($CONVERT_CMD identify -format "%h" "$img" 2>/dev/null)
    
    if [ -z "$width" ] || [ -z "$height" ]; then
        echo "Skipping (cannot read dimensions): $img"
        continue
    fi
    
    # Generate 2x version (if original is large enough)
    if [ "$width" -ge 400 ] || [ "$height" -ge 400 ]; then
        output_2x="$dir/${name}-2x.${ext}"
        if [ ! -f "$output_2x" ] || [ "$img" -nt "$output_2x" ]; then
            echo "Generating 2x: $output_2x ($((width * 2))x$((height * 2)))"
            $CONVERT_CMD "$img" -resize "$((width * 2))x$((height * 2))>" -quality 85 "$output_2x" 2>/dev/null || echo "Failed: $img -> 2x"
        fi
    fi
    
    # Generate 3x version (if original is large enough)
    if [ "$width" -ge 600 ] || [ "$height" -ge 600 ]; then
        output_3x="$dir/${name}-3x.${ext}"
        if [ ! -f "$output_3x" ] || [ "$img" -nt "$output_3x" ]; then
            echo "Generating 3x: $output_3x ($((width * 3))x$((height * 3)))"
            $CONVERT_CMD "$img" -resize "$((width * 3))x$((height * 3))>" -quality 85 "$output_3x" 2>/dev/null || echo "Failed: $img -> 3x"
        fi
    fi
done

echo "Responsive images generation complete!"

