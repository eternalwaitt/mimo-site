# Production Layout Fix

**Date**: 2025-11-16  
**Issue**: Layout completely broken on production (minhamimo.com.br) vs local  
**Root Cause**: Broken purged CSS file being served

## Problem Identified

The asset helper was serving `css/purged/product.min.css` which is only **812 bytes** (vs 39KB minified, 65KB original). This file is missing most CSS styles, causing the layout to break completely.

## Fix Applied

### 1. Asset Helper - Skip Broken Purged Files ✅
**File**: `inc/asset-helper.php`

**Change**: Added file size check to skip purged files that are suspiciously small (< 5KB)

```php
// FIX: Skip purged files if they're suspiciously small (likely broken)
$purgedMinFile = file_exists($purgedMinPath) ? $purgedMinPath : (file_exists($purgedMinPathAlt) ? $purgedMinPathAlt : null);
if ($purgedMinFile && filesize($purgedMinFile) > 5000) { // Only use if > 5KB
    $basePath = $prefix . 'css/purged/' . $minFileName;
}
```

**Result**: Now uses `minified/product.min.css` (39KB) instead of broken purged file

### 2. Critical CSS - Removed Conflicting Styles ✅
**File**: `inc/critical-css.php`

**Changes**:
- Removed full navbar styles that conflicted with product.css
- Kept only minimal styles to prevent FOUC
- Let product.css handle full styling

**Result**: No more CSS conflicts between critical and main CSS

## File Sizes Comparison

| File | Size | Status |
|------|------|--------|
| `product.css` (original) | 65KB | ✅ Complete |
| `minified/product.min.css` | 39KB | ✅ Complete (now being used) |
| `css/purged/product.min.css` | 812 bytes | ❌ Broken (now skipped) |
| `css/purged/product.css` | 7.8KB | ⚠️ May be incomplete |

## Expected Result

After deployment:
- ✅ Layout should work correctly
- ✅ Navbar should be transparent on homepage
- ✅ All styles should load properly
- ✅ No more broken layouts

## Next Steps

1. **Deploy changes** to production
2. **Verify** layout works correctly
3. **Check** if purged CSS needs to be regenerated (if we want to use it)
4. **Monitor** performance scores

## Note

The purged CSS file appears to be corrupted or generated incorrectly. If we want to use purged CSS in the future, we need to:
- Regenerate the purged CSS properly
- Ensure it includes all necessary styles
- Verify file size is reasonable (> 20KB for product.css)

