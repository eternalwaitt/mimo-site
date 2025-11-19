# PageSpeed Insights Results Analysis - v2.6.12

**Date**: 2025-11-16  
**Report URL**: https://pagespeed.web.dev/analysis/https-minhamimo-com-br/oewendwnh2?form_factor=mobile  
**Status**: ⚠️ Performance regression detected - optimizations may not be deployed

## Mobile Results

### Scores
| Category | Score | Target | Status |
|----------|-------|--------|--------|
| Performance | **64** | 90+ | 🔴 **Worse than baseline (72)** |
| Accessibility | 100 | 90+ | ✅ Perfect |
| Best Practices | 96 | 90+ | ✅ Excellent |
| SEO | 100 | 90+ | ✅ Perfect |

### Core Web Vitals
| Metric | Value | Target | Baseline | Change | Status |
|--------|-------|--------|----------|--------|--------|
| FCP | 2.6s | <1.8s | 2.6s | 0s | 🟡 No change |
| LCP | **4.6s** | <2.5s | 3.0s | **+1.6s worse** | 🔴 Regression |
| TBT | 0ms | <200ms | 0ms | 0ms | ✅ Perfect |
| CLS | **0.286** | <0.1 | 0.368 | **-0.082 improved** | 🟡 Improved but still high |
| SI | 3.8s | <3.4s | 3.8s | 0s | 🟡 No change |

### Key Insights

#### 🔴 Critical Issues
1. **Improve image delivery** - 795 KiB savings possible
   - Status: Not optimized in production
   - Impact: High on LCP and Network Payload

2. **Layout shift culprits** - Still present
   - CLS: 0.286 (improved from 0.368 but still >0.1)
   - Status: Partial fix (testimonials containment may not be deployed)

3. **LCP breakdown** - 4.6s (worse than baseline 3.0s)
   - Status: Needs investigation - may indicate deployment issue

#### 🟡 Medium Priority
4. **Reduce unused CSS** - 36 KiB savings
   - Status: PurgeCSS may not be deployed

5. **Minify CSS** - 10 KiB savings
   - Status: `USE_MINIFIED` may not be enabled

6. **Minify JavaScript** - 3 KiB savings
   - Status: Minification may not be deployed

7. **Non-composited animations** - 36 elements found
   - Status: GPU acceleration may not be applied

## Analysis

### Performance Regression
**Current**: 64 (down from baseline 72)  
**Expected**: 95-100 after optimizations  
**Gap**: -8 points from baseline, -31-36 points from target

### Possible Causes

1. **Optimizations Not Deployed** ⚠️ **Most Likely**
   - Changes committed but not deployed to production
   - `USE_MINIFIED` not enabled in production
   - Purged CSS files not deployed
   - Critical CSS changes not in production

2. **Cache Issues**
   - Browser/CDN cache serving old files
   - Asset version not updated in production

3. **Deployment Regression**
   - Something broke during deployment
   - Missing files or incorrect configuration

### CLS Improvement ✅
- **Baseline**: 0.368
- **Current**: 0.286
- **Improvement**: -0.082 (22% reduction)
- **Status**: Partial success - testimonials containment may be working

### LCP Regression 🔴
- **Baseline**: 3.0s
- **Current**: 4.6s
- **Regression**: +1.6s (53% worse)
- **Possible Causes**:
  - LCP image not loading properly
  - Server response time increased
  - Network issues during test
  - AVIF/WebP not being served

## Comparison with Expected Results

### Expected (After All Phases)
- Performance: 95-100
- CLS: <0.2 (target: <0.1)
- LCP: <2.5s
- FCP: <1.8s

### Actual
- Performance: 64 (31-36 points below expected)
- CLS: 0.286 (0.086 above target)
- LCP: 4.6s (2.1s above target)
- FCP: 2.6s (0.8s above target)

## Recommendations

### Immediate Actions

1. **Verify Deployment** 🔴 **CRITICAL**
   - Check if latest code is deployed to production
   - Verify `USE_MINIFIED = true` in production `config.php`
   - Verify purged CSS files exist in production
   - Check asset version matches latest commit

2. **Verify Optimizations**
   - Check Network tab for CSS file sizes (should be ~10KB purged, not 80KB)
   - Verify AVIF/WebP images are served (check Content-Type headers)
   - Check if critical CSS changes are in production HTML

3. **Clear Cache**
   - Clear CDN cache if using one
   - Clear browser cache
   - Test in incognito mode

4. **Investigate LCP Regression**
   - Check server response time (TTFB)
   - Verify LCP image preloads are in HTML
   - Check if LCP image is loading correctly

### Next Steps

1. **If Optimizations Not Deployed**:
   - Deploy latest code to production
   - Enable `USE_MINIFIED = true`
   - Verify all files are deployed
   - Clear cache
   - Re-test

2. **If Optimizations Deployed but Not Working**:
   - Check Network tab for file sizes
   - Verify asset-helper.php is serving correct files
   - Check for JavaScript errors
   - Verify .htaccess compression is working

3. **If Still Issues After Deployment**:
   - Investigate LCP regression (4.6s vs 3.0s)
   - Further CLS optimization needed (0.286 → <0.1)
   - Image optimization (795 KiB savings)
   - CSS/JS minification (49 KiB savings)

## Success Metrics

### Current Status
- **Performance**: 64 (baseline: 72, target: 90+)
- **CLS**: 0.286 (baseline: 0.368, target: <0.1) ✅ Improved but needs more work
- **LCP**: 4.6s (baseline: 3.0s, target: <2.5s) 🔴 Regression
- **FCP**: 2.6s (baseline: 2.6s, target: <1.8s) 🟡 No change

### Target After Deployment
- **Performance**: 90+ (need +26 points)
- **CLS**: <0.1 (need -0.186 improvement)
- **LCP**: <2.5s (need -2.1s improvement)
- **FCP**: <1.8s (need -0.8s improvement)

## Notes

- Results suggest optimizations may not be deployed to production
- CLS improved (0.368 → 0.286) suggesting some changes are working
- LCP regression (3.0s → 4.6s) needs immediate investigation
- Need to verify deployment status and re-test after deployment

