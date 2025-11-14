#!/bin/bash
# Restore images from backup
# Usage: ./restore-images.sh [backup_directory]

BACKUP_DIR=${1:-img_backup_20251114_103040}

if [ ! -d "$BACKUP_DIR" ]; then
    echo "Error: Backup directory $BACKUP_DIR not found"
    echo ""
    echo "Available backups:"
    ls -d img_backup_* 2>/dev/null || echo "  No backups found"
    exit 1
fi

echo "=========================================="
echo "Restore Images from Backup"
echo "=========================================="
echo "Backup directory: $BACKUP_DIR"
echo ""

# Count files to restore
FILE_COUNT=$(find "$BACKUP_DIR/img" -type f \( -name "*.jpg" -o -name "*.jpeg" -o -name "*.png" \) 2>/dev/null | wc -l | tr -d ' ')

if [ "$FILE_COUNT" -eq 0 ]; then
    echo "No images found in backup directory"
    exit 0
fi

echo "Found $FILE_COUNT images to restore"
echo ""
read -p "This will overwrite current images. Continue? (y/N): " -n 1 -r
echo ""

if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Restore cancelled"
    exit 0
fi

# Restore images
RESTORED=0
FAILED=0

find "$BACKUP_DIR/img" -type f \( -name "*.jpg" -o -name "*.jpeg" -o -name "*.png" \) | while read -r backup_file; do
    # Get relative path
    rel_path="${backup_file#$BACKUP_DIR/img/}"
    target_file="img/$rel_path"
    
    # Create target directory if needed
    mkdir -p "$(dirname "$target_file")"
    
    # Copy file
    if cp "$backup_file" "$target_file" 2>/dev/null; then
        echo "  ✓ Restored: $rel_path"
        RESTORED=$((RESTORED + 1))
    else
        echo "  ✗ Failed: $rel_path"
        FAILED=$((FAILED + 1))
    fi
done

echo ""
echo "=========================================="
echo "Restore Complete!"
echo "=========================================="
echo "Restored: $RESTORED files"
echo "Failed: $FAILED files"

