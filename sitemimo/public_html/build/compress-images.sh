#!/bin/bash
# Image Compression Script
# Comprime imagens PNG e JPG sem perda significativa de qualidade
# Usage: ./compress-images.sh [quality] [directory]
#   quality: 80-100 (default: 85)
#   directory: pasta para comprimir (default: img/)

QUALITY=${1:-85}
TARGET_DIR=${2:-img/}
BACKUP_DIR="img_backup_$(date +%Y%m%d_%H%M%S)"

# Check if required tools are available
if ! command -v npx &> /dev/null; then
    echo "Error: npx is required. Install Node.js first."
    exit 1
fi

# Validate quality
if [ "$QUALITY" -lt 50 ] || [ "$QUALITY" -gt 100 ]; then
    echo "Error: Quality must be between 50 and 100"
    exit 1
fi

# Check if target directory exists
if [ ! -d "$TARGET_DIR" ]; then
    echo "Error: Directory $TARGET_DIR does not exist"
    exit 1
fi

echo "=========================================="
echo "Image Compression Script"
echo "=========================================="
echo "Target directory: $TARGET_DIR"
echo "Quality: $QUALITY"
echo "Backup directory: $BACKUP_DIR"
echo ""

# Create backup directory
echo "Creating backup..."
mkdir -p "$BACKUP_DIR"

# Function to compress a single image
compress_image() {
    local file="$1"
    local ext="${file##*.}"
    local basename="${file%.*}"
    local dir=$(dirname "$file")
    local filename=$(basename "$file")
    
    # Skip if already compressed (has .min in name) or is WebP
    if [[ "$filename" == *".min."* ]] || [[ "$ext" == "webp" ]]; then
        return 0
    fi
    
    # Create backup
    cp "$file" "$BACKUP_DIR/$filename"
    
    # Compress based on extension using sharp via npx
    if [[ "$ext" == "jpg" ]] || [[ "$ext" == "jpeg" ]]; then
        # Compress JPEG using sharp
        npx --yes sharp-cli -i "$file" -o "$file" --jpeg-quality "$QUALITY" 2>/dev/null || {
            # Fallback: try with imagemin
            echo "    Trying imagemin fallback..."
            npx --yes imagemin "$file" --out-dir="$(dirname "$file")" --plugin=mozjpeg --plugin.mozjpeg.quality="$QUALITY" 2>/dev/null || {
                echo "  ⚠️  Could not compress: $file"
                return 1
            }
        }
    elif [[ "$ext" == "png" ]]; then
        # Compress PNG using sharp
        npx --yes sharp-cli -i "$file" -o "$file" --png-quality "$QUALITY" --png-compression-level 9 2>/dev/null || {
            # Fallback: try with imagemin
            echo "    Trying imagemin fallback..."
            npx --yes imagemin "$file" --out-dir="$(dirname "$file")" --plugin=pngquant --plugin.pngquant.quality=[0.7,0.9] 2>/dev/null || {
                echo "  ⚠️  Could not compress: $file"
                return 1
            }
        }
    fi
    
    return 0
}

# Find and compress all images
TOTAL_SIZE_BEFORE=0
TOTAL_SIZE_AFTER=0
COMPRESSED_COUNT=0
FAILED_COUNT=0

echo "Scanning for images..."
while IFS= read -r -d '' file; do
    ext="${file##*.}"
    if [[ "$ext" == "jpg" ]] || [[ "$ext" == "jpeg" ]] || [[ "$ext" == "png" ]]; then
        size_before=$(stat -f%z "$file" 2>/dev/null || stat -c%s "$file" 2>/dev/null || echo 0)
        TOTAL_SIZE_BEFORE=$((TOTAL_SIZE_BEFORE + size_before))
        
        echo "  Compressing: $file"
        if compress_image "$file"; then
            size_after=$(stat -f%z "$file" 2>/dev/null || stat -c%s "$file" 2>/dev/null || echo 0)
            TOTAL_SIZE_AFTER=$((TOTAL_SIZE_AFTER + size_after))
            COMPRESSED_COUNT=$((COMPRESSED_COUNT + 1))
            
            reduction=$((100 - (size_after * 100 / size_before)))
            if [ "$reduction" -gt 0 ]; then
                echo "    ✓ Reduced by ${reduction}%"
            fi
        else
            FAILED_COUNT=$((FAILED_COUNT + 1))
        fi
    fi
done < <(find "$TARGET_DIR" -type f \( -name "*.jpg" -o -name "*.jpeg" -o -name "*.png" \) -print0)

# Calculate totals
TOTAL_REDUCTION=$((TOTAL_SIZE_BEFORE - TOTAL_SIZE_AFTER))
TOTAL_REDUCTION_PERCENT=$((TOTAL_REDUCTION * 100 / TOTAL_SIZE_BEFORE))

echo ""
echo "=========================================="
echo "Compression Complete!"
echo "=========================================="
echo "Images compressed: $COMPRESSED_COUNT"
echo "Failed: $FAILED_COUNT"
echo "Total size before: $(numfmt --to=iec-i --suffix=B $TOTAL_SIZE_BEFORE 2>/dev/null || echo "${TOTAL_SIZE_BEFORE} bytes")"
echo "Total size after: $(numfmt --to=iec-i --suffix=B $TOTAL_SIZE_AFTER 2>/dev/null || echo "${TOTAL_SIZE_AFTER} bytes")"
echo "Space saved: $(numfmt --to=iec-i --suffix=B $TOTAL_REDUCTION 2>/dev/null || echo "${TOTAL_REDUCTION} bytes") ($TOTAL_REDUCTION_PERCENT%)"
echo ""
echo "Backup saved to: $BACKUP_DIR"
echo ""
echo "To restore originals, copy files from $BACKUP_DIR back to $TARGET_DIR"

