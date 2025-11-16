# TTFB (Time to First Byte) Optimization Guide

**Status**: Analysis Complete  
**Current Issue**: Document request latency: 64 KiB flagged  
**Target**: TTFB <600ms (recommended by PageSpeed Insights guides)

## Current State

### Cache Headers ✅
- Static assets (CSS/JS/images): 1 year cache
- HTML/PHP: No-cache (forces fresh content)
- ETags: Disabled for HTML (prevents 304 issues)
- Varnish bypass: Configured via headers

### Server Configuration
- Hosting: Locaweb (uses Varnish)
- PHP: Version unknown (check server)
- OPcache: Should be enabled (server config)

## TTFB Optimization Recommendations

### 1. PHP Performance
**Current**: Unknown (needs measurement)

**Actions**:
- ✅ Cache headers configured
- ⚠️ Verify OPcache is enabled on server
- ⚠️ Minimize file I/O operations
- ⚠️ Reduce `file_exists()` calls (cache results)
- ⚠️ Optimize `picture_webp()` function (multiple file checks)

### 2. Database Queries
**Current**: No database queries in index.php ✅

**Status**: No database queries found - good!

### 3. External Resources
**Current**: Google Tag Manager, Google Fonts

**Impact**: External resources don't affect TTFB (they load after HTML)

### 4. File System Operations
**Potential Issues**:
- `picture_webp()` checks multiple file paths for each image
- Multiple `file_exists()` calls in asset-helper.php
- Image dimension detection uses `getimagesize()`

**Optimization**:
- Cache file existence checks
- Cache image dimensions
- Reduce redundant file system calls

### 5. CDN Consideration
**Current**: Not using CDN

**Recommendation**: Consider CDN for static assets (images, CSS, JS)
- Reduces server load
- Improves TTFB for static assets
- Better global performance

## Measurement

To measure TTFB:
1. Use Chrome DevTools Network tab
2. Look for "Waiting (TTFB)" in request details
3. Target: <600ms

Or use:
```bash
curl -o /dev/null -s -w "TTFB: %{time_starttransfer}\n" https://minhamimo.com.br/
```

## Priority Actions

1. **Measure current TTFB** (use curl or DevTools)
2. **Verify OPcache enabled** (check phpinfo or server config)
3. **Optimize file_exists() calls** (cache results in memory)
4. **Consider CDN** for static assets
5. **Profile PHP execution** (identify slow operations)

## Expected Impact

- TTFB <600ms: +2-5 performance points
- Reduced server load: Better scalability
- Faster FCP/LCP: Better user experience

