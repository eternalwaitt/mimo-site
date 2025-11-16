# PageSpeed Insights Results - v2.6.12

**Date**: 2025-11-16  
**Version**: 2.6.12  
**URL**: https://minhamimo.com.br/

## Summary

### Mobile Performance
- **Performance Score**: 50 (Target: 90+)
- **FCP**: 3.8s (Target: <1.8s) ❌
- **LCP**: 4.8s (Target: <2.5s) ❌
- **TBT**: 0ms ✅
- **CLS**: 0.532 (Target: <0.1) ❌
- **SI**: 4.9s (Target: <3.4s) ❌

### Desktop Performance
- **Performance Score**: 78 (Target: 90+)
- **FCP**: 0.7s (Target: <1.8s) ✅
- **LCP**: 1.1s (Target: <2.5s) ✅
- **TBT**: 50ms (Target: <200ms) ✅
- **CLS**: 0.324 (Target: <0.1) ❌
- **SI**: 1.9s (Target: <3.4s) ✅

## Comparison with Previous Results

### Desktop (Previous: 66 → Current: 78)
- **Improvement**: +12 points ✅
- **CLS**: 0.625 → 0.324 (48% improvement) ✅
- **LCP**: Improved significantly (was ~7.5s, now 1.1s) ✅
- **FCP**: Improved significantly (was ~2.9s, now 0.7s) ✅

### Mobile (Previous: 45 → Current: 50)
- **Improvement**: +5 points ✅
- **CLS**: 0.747 → 0.532 (29% improvement) ✅
- **LCP**: Improved significantly (was ~7.5s, now 4.8s) ✅
- **FCP**: Improved significantly (was ~2.9s, now 3.8s) ⚠️ (still high)

## Key Issues Identified

### Mobile (Priority: High)
1. **CLS: 0.532** (Target: <0.1)
   - Still 5x above target
   - Layout shift culprits identified
   - Need more aggressive containment

2. **LCP: 4.8s** (Target: <2.5s)
   - Still 2x above target
   - Image delivery optimization needed (868 KiB savings possible)
   - Network dependency tree shows bottlenecks

3. **FCP: 3.8s** (Target: <1.8s)
   - Still 2x above target
   - Critical CSS may need expansion
   - Render-blocking resources still present

4. **Image Delivery**: 868 KiB savings possible
   - Improve image formats (AVIF/WebP)
   - Optimize image sizes
   - Use responsive images

### Desktop (Priority: Medium)
1. **CLS: 0.324** (Target: <0.1)
   - Still 3x above target
   - Layout shift culprits identified
   - Need more aggressive containment

2. **Image Delivery**: 361 KiB savings possible
   - Improve image formats
   - Optimize image sizes

3. **Unused CSS**: 41 KiB savings possible
   - Regenerate PurgeCSS
   - Remove unused styles

4. **Minify CSS**: 3 KiB savings possible
   - Verify all CSS is minified

5. **Long Main-Thread Tasks**: 2 long tasks found
   - Optimize JavaScript execution
   - Break up heavy operations

6. **Non-Composited Animations**: 129 animated elements found
   - Ensure all animations use GPU acceleration
   - Use transform/opacity instead of layout properties

## Opportunities for Improvement

### High Impact (Mobile)
1. **Fix CLS** (0.532 → <0.1)
   - Add more aggressive containment to mobile-specific elements
   - Reserve space for dynamic content
   - Fix font reflow issues

2. **Optimize Image Delivery** (868 KiB savings)
   - Convert all images to AVIF/WebP
   - Implement responsive srcset properly
   - Optimize image sizes for mobile

3. **Reduce FCP** (3.8s → <1.8s)
   - Expand critical CSS for mobile
   - Defer more non-critical CSS
   - Optimize font loading

### Medium Impact (Desktop)
1. **Fix CLS** (0.324 → <0.1)
   - Continue containment improvements
   - Fix remaining layout shift culprits

2. **Optimize Image Delivery** (361 KiB savings)
   - Convert remaining images to AVIF/WebP
   - Optimize image sizes

3. **Reduce Unused CSS** (41 KiB savings)
   - Regenerate PurgeCSS
   - Remove unused styles

4. **Optimize Long Tasks**
   - Break up heavy JavaScript operations
   - Use requestIdleCallback for non-critical work

## Next Steps

### Phase 1: Mobile CLS Fixes (Priority: Critical)
- [ ] Identify and fix mobile-specific layout shift culprits
- [ ] Add aggressive containment to mobile containers
- [ ] Reserve space for dynamic content on mobile
- [ ] Fix font reflow issues on mobile

### Phase 2: Image Optimization (Priority: High)
- [ ] Convert all images to AVIF/WebP
- [ ] Implement proper responsive srcset
- [ ] Optimize image sizes for mobile (868 KiB savings)
- [ ] Optimize image sizes for desktop (361 KiB savings)

### Phase 3: Mobile FCP Optimization (Priority: High)
- [ ] Expand critical CSS for mobile
- [ ] Defer more non-critical CSS
- [ ] Optimize font loading strategy
- [ ] Reduce render-blocking resources

### Phase 4: CSS Optimization (Priority: Medium)
- [ ] Regenerate PurgeCSS (41 KiB savings)
- [ ] Verify all CSS is minified (3 KiB savings)
- [ ] Remove unused styles

### Phase 5: JavaScript Optimization (Priority: Medium)
- [ ] Break up long main-thread tasks
- [ ] Optimize animation performance
- [ ] Ensure all animations use GPU acceleration

## Target Scores

### Mobile
- **Performance**: 50 → 90+ (40 point improvement needed)
- **CLS**: 0.532 → <0.1 (5x improvement needed)
- **LCP**: 4.8s → <2.5s (2x improvement needed)
- **FCP**: 3.8s → <1.8s (2x improvement needed)

### Desktop
- **Performance**: 78 → 90+ (12 point improvement needed)
- **CLS**: 0.324 → <0.1 (3x improvement needed)
- **LCP**: 1.1s → <2.5s ✅ (already good)
- **FCP**: 0.7s → <1.8s ✅ (already good)

## Notes

- Desktop performance improved significantly (+12 points)
- Mobile performance improved slightly (+5 points)
- CLS remains the biggest issue on both mobile and desktop
- Image optimization offers the largest savings (868 KiB mobile, 361 KiB desktop)
- TBT is excellent on both platforms (0ms mobile, 50ms desktop)

