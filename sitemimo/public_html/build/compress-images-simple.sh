#!/bin/bash
# Simple Image Compression Script using imagemin
# Comprime imagens PNG e JPG sem perda significativa de qualidade
# Usage: ./compress-images-simple.sh [quality] [directory]

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
echo "Image Compression Script (Simple)"
echo "=========================================="
echo "Target directory: $TARGET_DIR"
echo "Quality: $QUALITY"
echo "Backup directory: $BACKUP_DIR"
echo ""

# Create backup directory
echo "Creating backup..."
mkdir -p "$BACKUP_DIR"

# Find all images
echo "Finding images..."
IMAGE_FILES=$(find "$TARGET_DIR" -type f \( -name "*.jpg" -o -name "*.jpeg" -o -name "*.png" \) ! -name "*.min.*" ! -name "*.webp")

if [ -z "$IMAGE_FILES" ]; then
    echo "No images found to compress."
    exit 0
fi

# Count files
FILE_COUNT=$(echo "$IMAGE_FILES" | wc -l | tr -d ' ')
echo "Found $FILE_COUNT images to compress"
echo ""

# Calculate total size before
TOTAL_SIZE_BEFORE=0
while IFS= read -r file; do
    if [ -f "$file" ]; then
        size=$(stat -f%z "$file" 2>/dev/null || stat -c%s "$file" 2>/dev/null || echo 0)
        TOTAL_SIZE_BEFORE=$((TOTAL_SIZE_BEFORE + size))
        # Create backup
        cp "$file" "$BACKUP_DIR/$(basename "$file")" 2>/dev/null || true
    fi
done <<< "$IMAGE_FILES"

echo "Backup created. Starting compression..."
echo ""

# Compress JPEG files
JPEG_FILES=$(find "$TARGET_DIR" -type f \( -name "*.jpg" -o -name "*.jpeg" \) ! -name "*.min.*" ! -name "*.webp")
if [ -n "$JPEG_FILES" ]; then
    echo "Compressing JPEG files..."
    while IFS= read -r file; do
        if [ -f "$file" ]; then
            echo "  Processing: $file"
            npx --yes imagemin "$file" --out-dir="$(dirname "$file")" --plugin=mozjpeg --plugin.mozjpeg.quality="$QUALITY" 2>/dev/null || {
                echo "    ⚠️  Failed to compress"
            }
        fi
    done <<< "$JPEG_FILES"
fi

# Compress PNG files
PNG_FILES=$(find "$TARGET_DIR" -type f -name "*.png" ! -name "*.min.*" ! -name "*.webp")
if [ -n "$PNG_FILES" ]; then
    echo "Compressing PNG files..."
    while IFS= read -r file; do
        if [ -f "$file" ]; then
            echo "  Processing: $file"
            npx --yes imagemin "$file" --out-dir="$(dirname "$file")" --plugin=pngquant --plugin.pngquant.quality=[0.7,0.9] 2>/dev/null || {
                echo "    ⚠️  Failed to compress"
            }
        fi
    done <<< "$PNG_FILES"
fi

# Calculate total size after
TOTAL_SIZE_AFTER=0
while IFS= read -r file; do
    if [ -f "$file" ]; then
        size=$(stat -f%z "$file" 2>/dev/null || stat -c%s "$file" 2>/dev/null || echo 0)
        TOTAL_SIZE_AFTER=$((TOTAL_SIZE_AFTER + size))
    fi
done <<< "$IMAGE_FILES"

# Calculate reduction
TOTAL_REDUCTION=$((TOTAL_SIZE_BEFORE - TOTAL_SIZE_AFTER))
if [ "$TOTAL_SIZE_BEFORE" -gt 0 ]; then
    TOTAL_REDUCTION_PERCENT=$((TOTAL_REDUCTION * 100 / TOTAL_SIZE_BEFORE))
else
    TOTAL_REDUCTION_PERCENT=0
fi

echo ""
echo "=========================================="
echo "Compression Complete!"
echo "=========================================="
echo "Images processed: $FILE_COUNT"
echo "Total size before: $(numfmt --to=iec-i --suffix=B $TOTAL_SIZE_BEFORE 2>/dev/null || echo "${TOTAL_SIZE_BEFORE} bytes")"
echo "Total size after: $(numfmt --to=iec-i --suffix=B $TOTAL_SIZE_AFTER 2>/dev/null || echo "${TOTAL_SIZE_AFTER} bytes")"
echo "Space saved: $(numfmt --to=iec-i --suffix=B $TOTAL_REDUCTION 2>/dev/null || echo "${TOTAL_REDUCTION} bytes") ($TOTAL_REDUCTION_PERCENT%)"
echo ""
echo "Backup saved to: $BACKUP_DIR"
echo ""
echo "To restore originals, copy files from $BACKUP_DIR back to $TARGET_DIR"

