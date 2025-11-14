#!/bin/bash
# Image Compression Script using native tools (jpegoptim/optipng)
# Comprime imagens PNG e JPG sem perda significativa de qualidade
# Usage: ./compress-images-native.sh [quality] [directory]
# 
# Requires: jpegoptim and optipng (install via: brew install jpegoptim optipng)

QUALITY=${1:-85}
TARGET_DIR=${2:-img/}
BACKUP_DIR="img_backup_$(date +%Y%m%d_%H%M%S)"

# Check if required tools are available
MISSING_TOOLS=()

if ! command -v jpegoptim &> /dev/null; then
    MISSING_TOOLS+=("jpegoptim")
fi

if ! command -v optipng &> /dev/null; then
    MISSING_TOOLS+=("optipng")
fi

if [ ${#MISSING_TOOLS[@]} -gt 0 ]; then
    echo "Error: Missing required tools: ${MISSING_TOOLS[*]}"
    echo ""
    echo "Install with Homebrew:"
    echo "  brew install jpegoptim optipng"
    echo ""
    echo "Or use the Node.js version: ./compress-images-simple.sh"
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
echo "Image Compression Script (Native Tools)"
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
        rel_path="${file#$TARGET_DIR/}"
        backup_path="$BACKUP_DIR/$rel_path"
        mkdir -p "$(dirname "$backup_path")"
        cp "$file" "$backup_path" 2>/dev/null || true
    fi
done <<< "$IMAGE_FILES"

echo "Backup created. Starting compression..."
echo ""

COMPRESSED_COUNT=0
FAILED_COUNT=0

# Compress JPEG files
JPEG_FILES=$(find "$TARGET_DIR" -type f \( -name "*.jpg" -o -name "*.jpeg" \) ! -name "*.min.*" ! -name "*.webp")
if [ -n "$JPEG_FILES" ]; then
    echo "Compressing JPEG files..."
    while IFS= read -r file; do
        if [ -f "$file" ]; then
            size_before=$(stat -f%z "$file" 2>/dev/null || stat -c%s "$file" 2>/dev/null || echo 0)
            echo "  Processing: $file"
            
            if jpegoptim --max="$QUALITY" --strip-all --preserve --force "$file" 2>/dev/null; then
                size_after=$(stat -f%z "$file" 2>/dev/null || stat -c%s "$file" 2>/dev/null || echo 0)
                reduction=$((100 - (size_after * 100 / size_before)))
                if [ "$reduction" -gt 0 ]; then
                    echo "    ✓ Reduced by ${reduction}%"
                fi
                COMPRESSED_COUNT=$((COMPRESSED_COUNT + 1))
            else
                echo "    ⚠️  Failed to compress"
                FAILED_COUNT=$((FAILED_COUNT + 1))
            fi
        fi
    done <<< "$JPEG_FILES"
fi

# Compress PNG files
PNG_FILES=$(find "$TARGET_DIR" -type f -name "*.png" ! -name "*.min.*" ! -name "*.webp")
if [ -n "$PNG_FILES" ]; then
    echo "Compressing PNG files..."
    while IFS= read -r file; do
        if [ -f "$file" ]; then
            size_before=$(stat -f%z "$file" 2>/dev/null || stat -c%s "$file" 2>/dev/null || echo 0)
            echo "  Processing: $file"
            
            if optipng -o7 -strip all -quiet -force "$file" 2>/dev/null; then
                size_after=$(stat -f%z "$file" 2>/dev/null || stat -c%s "$file" 2>/dev/null || echo 0)
                reduction=$((100 - (size_after * 100 / size_before)))
                if [ "$reduction" -gt 0 ]; then
                    echo "    ✓ Reduced by ${reduction}%"
                fi
                COMPRESSED_COUNT=$((COMPRESSED_COUNT + 1))
            else
                echo "    ⚠️  Failed to compress"
                FAILED_COUNT=$((FAILED_COUNT + 1))
            fi
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
echo "Images processed: $COMPRESSED_COUNT"
echo "Failed: $FAILED_COUNT"
echo "Total size before: $(numfmt --to=iec-i --suffix=B $TOTAL_SIZE_BEFORE 2>/dev/null || echo "${TOTAL_SIZE_BEFORE} bytes")"
echo "Total size after: $(numfmt --to=iec-i --suffix=B $TOTAL_SIZE_AFTER 2>/dev/null || echo "${TOTAL_SIZE_AFTER} bytes")"
echo "Space saved: $(numfmt --to=iec-i --suffix=B $TOTAL_REDUCTION 2>/dev/null || echo "${TOTAL_REDUCTION} bytes") ($TOTAL_REDUCTION_PERCENT%)"
echo ""
echo "Backup saved to: $BACKUP_DIR"
echo ""
echo "To restore originals, copy files from $BACKUP_DIR back to $TARGET_DIR"

