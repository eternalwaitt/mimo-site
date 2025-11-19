# PageSpeed Insights Results - Latest Analysis v2.6.12

**Date**: 2025-11-16  
**Report URL**: https://pagespeed.web.dev/analysis/https-minhamimo-com-br/yi9jv7u1il?form_factor=mobile  
**Report Time**: Nov 16, 2025, 2:28 AM GMT-3  
**Status**: ⚠️ Performance still below baseline - optimizations may not be deployed yet

## Mobile Results Comparison

### Scores
| Metric | Latest | Previous | Baseline | Change | Status |
|--------|--------|----------|----------|-------|--------|
| **Performance** | **61** | 64 | 72 | -3 from prev, -11 from baseline | 🔴 Worse |
| Accessibility | 100 | 100 | 100 | 0 | ✅ Perfect |
| Best Practices | 96 | 96 | 96 | 0 | ✅ Excellent |
| SEO | 100 | 100 | 100 | 0 | ✅ Perfect |

### Core Web Vitals
| Metric | Latest | Previous | Baseline | Target | Change | Status |
|--------|--------|----------|----------|--------|--------|--------|
| **FCP** | 2.6s | 2.6s | 2.6s | <1.8s | 0s | 🟡 No change |
| **LCP** | **4.8s** | 4.6s | 3.0s | <2.5s | +0.2s worse | 🔴 Regression |
| **TBT** | 0ms | 0ms | 0ms | <200ms | 0ms | ✅ Perfect |
| **CLS** | **0.332** | 0.286 | 0.368 | <0.1 | +0.046 worse | 🔴 Regression |
| **SI** | 3.7s | 3.8s | 3.8s | <3.4s | -0.1s better | 🟡 Slight improvement |

## Key Insights

### 🔴 Critical Issues (Still Present)
1. **Improve image delivery** - 795 KiB savings possible
   - Status: Not optimized in production
   - Impact: High on LCP and Network Payload

2. **Layout shift culprits** - CLS 0.332 (worse than previous 0.286)
   - Status: Regression detected
   - Impact: High on Performance score

3. **LCP breakdown** - 4.8s (worse than baseline 3.0s)
   - Status: Regression - needs investigation
   - Impact: High on Performance score

### 🟡 Medium Priority (Some Improvement)
4. **Reduce unused CSS** - 23 KiB savings (down from 36 KiB)
   - Status: ✅ Some improvement (13 KiB reduction)
   - Note: Still showing unused CSS, suggesting PurgeCSS may be partially working

5. **Minify CSS** - 4 KiB savings (down from 10 KiB)
   - Status: ✅ Some improvement (6 KiB reduction)
   - Note: Still showing minification needed, suggesting minification may be partially working

6. **Use efficient cache lifetimes** - 14 KiB savings (NEW)
   - Status: New issue detected
   - Impact: Low

7. **Optimize DOM size** - (NEW)
   - Status: New issue detected
   - Impact: Low

8. **Non-composited animations** - 36 elements found
   - Status: No change
   - Impact: Low

## Analysis

### Performance Regression
**Latest**: 61 (down from 64, down from baseline 72)  
**Gap**: -11 points from baseline, -29 points from target (90+)

### Possible Causes

1. **Optimizations Not Fully Deployed** ⚠️ **Most Likely**
   - Config.php change may not be deployed yet
   - Cache may not be cleared
   - Purged CSS files may not be deployed
   - Some optimizations working (unused CSS reduced), but not all

2. **CLS Regression** 🔴 **Critical**
   - CLS worsened: 0.286 → 0.332 (+0.046)
   - This is worse than baseline (0.368) but trending in wrong direction
   - Possible causes:
     - Testimonials containment not working
     - Font loading causing shifts
     - Dynamic content loading causing shifts

3. **LCP Regression** 🔴 **Critical**
   - LCP worsened: 4.6s → 4.8s (+0.2s)
   - Much worse than baseline (3.0s)
   - Possible causes:
     - LCP image not loading properly
     - Server response time increased
     - Network issues during test
     - AVIF/WebP not being served

### Positive Signs ✅

1. **Unused CSS Reduced**: 36 KiB → 23 KiB (13 KiB improvement)
   - Suggests PurgeCSS may be partially working
   - Or some CSS was removed

2. **Minify CSS Reduced**: 10 KiB → 4 KiB (6 KiB improvement)
   - Suggests minification may be partially working
   - Or some CSS was minified

3. **Speed Index Improved**: 3.8s → 3.7s (-0.1s)
   - Small improvement, but positive

## Comparison Timeline

| Test | Performance | CLS | LCP | FCP | Unused CSS | Minify CSS |
|------|-------------|-----|-----|-----|------------|------------|
| Baseline | 72 | 0.368 | 3.0s | 2.6s | ? | ? |
| Previous (2:20 AM) | 64 | 0.286 | 4.6s | 2.6s | 36 KiB | 10 KiB |
| Latest (2:28 AM) | 61 | 0.332 | 4.8s | 2.6s | 23 KiB | 4 KiB |

## Recommendations

### Immediate Actions

1. **Verify Deployment** 🔴 **CRITICAL**
   - Check if latest code (including config.php fix) is deployed
   - Verify `APP_ENV` is `'production'` in production
   - Verify `USE_MINIFIED` is `true` in production
   - Check if purged CSS files are deployed

2. **Clear All Caches** 🔴 **CRITICAL**
   - Clear CDN cache if using one
   - Clear server cache
   - Clear browser cache
   - Test in incognito mode

3. **Investigate CLS Regression** 🔴 **CRITICAL**
   - CLS worsened from 0.286 to 0.332
   - Use Chrome DevTools Performance panel
   - Identify what's causing the shift
   - Check if testimonials containment is working

4. **Investigate LCP Regression** 🔴 **CRITICAL**
   - LCP worsened from 4.6s to 4.8s
   - Check server response time (TTFB)
   - Verify LCP image preloads are in HTML
   - Check if AVIF/WebP images are being served

### Expected After Full Deployment

- **Performance**: 61 → 90+ (need +29 points)
- **CLS**: 0.332 → <0.1 (need -0.232 improvement)
- **LCP**: 4.8s → <2.5s (need -2.3s improvement)
- **FCP**: 2.6s → <1.8s (need -0.8s improvement)
- **Unused CSS**: 23 KiB → 0 KiB
- **Minify CSS**: 4 KiB → 0 KiB

## Next Steps

1. **Deploy Latest Code** (if not already)
   - Ensure config.php with environment-aware USE_MINIFIED is deployed
   - Ensure all optimization changes are deployed
   - Ensure purged CSS files are deployed

2. **Clear All Caches**
   - CDN, server, browser

3. **Re-test After Deployment**
   - Wait 2-5 minutes for caches to clear
   - Run PageSpeed Insights again
   - Compare with baseline and previous results

4. **If Still Issues After Deployment**
   - Investigate CLS regression (0.332)
   - Investigate LCP regression (4.8s)
   - Verify all optimizations are actually applied

## Notes

- Some improvements detected (unused CSS, minify CSS reduced)
- But overall performance is worse than baseline
- CLS and LCP regressions are concerning
- Need to verify deployment status and clear caches

