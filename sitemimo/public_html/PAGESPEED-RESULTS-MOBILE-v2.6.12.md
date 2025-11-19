# PageSpeed Insights Results - Mobile v2.6.12

**Date**: 2025-11-16, 2:05 AM GMT-3  
**URL**: https://minhamimo.com.br/  
**Report**: https://pagespeed.web.dev/analysis/https-minhamimo-com-br/98gbfqjf8g?form_factor=mobile

## Score Summary

| Category | Score | Status | Change from Baseline |
|----------|-------|--------|---------------------|
| **Performance** | **72** | 🟡 Good | +22 (from 50) |
| Accessibility | 100 | ✅ Perfect | - |
| Best Practices | 96 | ✅ Excellent | - |
| SEO | 100 | ✅ Perfect | - |

## Core Web Vitals

| Metric | Value | Target | Status | Points | Change |
|--------|-------|--------|--------|--------|--------|
| **FCP** | 2.6s | <1.8s | 🟡 Needs work | +7 | 4.1s → 2.6s |
| **LCP** | 3.0s | <2.5s | 🟡 Needs work | +20 | 5.8s → 3.0s |
| **TBT** | 0ms | <200ms | ✅ Excellent | +30 | - |
| **CLS** | 0.368 | <0.1 | 🔴 Critical | +7 | 0.532 → 0.368 |
| **SI** | 3.8s | <3.4s | 🟡 Needs work | +8 | 5.9s → 3.8s |

## Improvements Achieved

### ✅ Significant Improvements
- **Performance Score**: 50 → 72 (+22 points, 44% improvement)
- **LCP**: 5.8s → 3.0s (-2.8s, 48% improvement)
- **FCP**: 4.1s → 2.6s (-1.5s, 37% improvement)
- **CLS**: 0.532 → 0.368 (-0.164, 31% improvement)
- **SI**: 5.9s → 3.8s (-2.1s, 36% improvement)
- **TBT**: 0ms (already excellent)

## Remaining Issues (Priority Order)

### 🔴 Critical (Blocks 90+ Score)

1. **CLS: 0.368** (Target: <0.1)
   - **Impact**: -18 points (estimated)
   - **Issue**: "Layout shift culprits" insight
   - **Action**: Further CLS fixes needed, likely from:
     - Dynamic content loading (Google Reviews)
     - Images without explicit dimensions
     - Font loading causing reflow
   - **Potential**: Reduce to <0.1 for +15-20 points

### 🟡 High Priority (Significant Impact)

2. **Image Delivery: 791 KiB savings**
   - **Impact**: -5-10 points (estimated)
   - **Issue**: Images not optimized (AVIF/WebP not being served?)
   - **Action**: Verify AVIF/WebP images are being served in production
   - **Potential**: +5-10 points

3. **LCP: 3.0s** (Target: <2.5s)
   - **Impact**: -3-5 points (estimated)
   - **Issue**: LCP element loading slowly
   - **Action**: Optimize LCP image, ensure preload/fetchpriority
   - **Potential**: +3-5 points

4. **FCP: 2.6s** (Target: <1.8s)
   - **Impact**: -2-3 points (estimated)
   - **Issue**: First contentful paint still slow
   - **Action**: Expand critical CSS further, optimize font loading
   - **Potential**: +2-3 points

### 🟢 Medium Priority (Minor Impact)

5. **Unused CSS: 36 KiB**
   - **Impact**: -1-2 points (estimated)
   - **Issue**: PurgeCSS may not be deployed or working correctly
   - **Action**: Verify `USE_MINIFIED` is enabled, check purged CSS is served
   - **Potential**: +1-2 points

6. **Minify CSS: 10 KiB**
   - **Impact**: -1 point (estimated)
   - **Issue**: CSS not minified in production
   - **Action**: Verify minified CSS is being served
   - **Potential**: +1 point

7. **Minify JavaScript: 3 KiB**
   - **Impact**: -0.5 points (estimated)
   - **Issue**: JS not minified in production
   - **Action**: Verify minified JS is being served
   - **Potential**: +0.5 points

8. **Non-composited animations: 34 elements**
   - **Impact**: -1-2 points (estimated)
   - **Issue**: Some animations not GPU-accelerated
   - **Action**: Verify all animations use `translateZ(0)` and `will-change`
   - **Potential**: +1-2 points

## Path to 90+ Score

### Current: 72
### Target: 90+
### Gap: 18 points

### Estimated Point Gains:
1. **CLS fix** (0.368 → <0.1): +15-20 points → **87-92** ✅
2. **Image optimization** (791 KiB): +5-10 points → **92-97** ✅
3. **LCP optimization** (3.0s → <2.5s): +3-5 points → **95-100** ✅
4. **FCP optimization** (2.6s → <1.8s): +2-3 points → **97-100** ✅
5. **CSS/JS minification**: +2-3 points → **99-100** ✅

**Total Potential**: 99-100 (exceeds target)

## Recommendations

### Immediate Actions (Critical)
1. **Fix CLS** (0.368 → <0.1)
   - Investigate "Layout shift culprits" insight
   - Add explicit dimensions to all images
   - Prevent font reflow with `font-display: swap` and size-adjust
   - Reserve space for dynamic content (Google Reviews)

2. **Verify Image Optimization**
   - Check if AVIF/WebP images are being served
   - Verify `picture_webp()` function is working in production
   - Check Network tab for image formats

3. **Verify CSS/JS Minification**
   - Check `USE_MINIFIED` flag in production
   - Verify purged/minified files are being served
   - Check Network tab for file sizes

### Next Steps
1. Run desktop analysis to compare
2. Investigate CLS culprits in detail
3. Optimize remaining images
4. Verify all optimizations are deployed

## Notes

- **Excellent progress**: 22 point improvement from baseline
- **TBT is perfect**: 0ms (no blocking time)
- **Accessibility and SEO perfect**: 100/100
- **Main blocker**: CLS at 0.368 (needs <0.1)
- **Secondary blocker**: Image optimization (791 KiB savings)

The optimizations are working! We just need to:
1. Fix CLS (biggest impact)
2. Verify image optimization is deployed
3. Verify CSS/JS minification is deployed

