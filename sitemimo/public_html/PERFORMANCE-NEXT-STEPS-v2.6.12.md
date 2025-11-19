# Performance Optimization Plan - Next Steps (v2.6.12)

**Date**: 2025-11-16  
**Current Scores**: Mobile 50, Desktop 78  
**Target Scores**: Mobile 90+, Desktop 90+

## Priority Matrix

### Critical (Mobile CLS)
- **Impact**: High (0.532 → <0.1 needed)
- **Effort**: Medium
- **ROI**: Very High

### High (Image Optimization)
- **Impact**: Very High (868 KiB mobile, 361 KiB desktop savings)
- **Effort**: Low-Medium
- **ROI**: Very High

### High (Mobile FCP)
- **Impact**: High (3.8s → <1.8s needed)
- **Effort**: Medium
- **ROI**: High

### Medium (Desktop CLS)
- **Impact**: Medium (0.324 → <0.1 needed)
- **Effort**: Low-Medium
- **ROI**: Medium

## Phase 1: Mobile CLS Fixes (Critical)

### Goal
Reduce mobile CLS from 0.532 to <0.1

### Tasks
1. **Identify Mobile-Specific Layout Shift Culprits**
   - Use Chrome DevTools Performance panel
   - Record mobile trace
   - Identify elements causing shifts
   - Focus on above-the-fold content

2. **Add Aggressive Containment**
   - Apply `contain: layout style paint` to mobile containers
   - Add `min-height` to prevent shifts
   - Reserve space for dynamic content

3. **Fix Font Reflow**
   - Add explicit font-size and line-height
   - Use `font-display: swap` consistently
   - Consider `size-adjust` for font loading

4. **Reserve Space for Dynamic Content**
   - Testimonials carousel
   - Service cards
   - Category images
   - Any content loaded via JavaScript

### Expected Impact
- CLS: 0.532 → <0.1 (5x improvement)
- Performance: +15-20 points

## Phase 2: Image Optimization (High)

### Goal
Reduce image payload by 868 KiB (mobile) and 361 KiB (desktop)

### Tasks
1. **Convert All Images to AVIF/WebP**
   - Audit all images on homepage
   - Convert to AVIF (best compression)
   - Fallback to WebP
   - Keep JPG/PNG as final fallback

2. **Implement Proper Responsive srcset**
   - Use width descriptors (150w, 300w, 450w)
   - Set proper `sizes` attribute
   - Ensure browser selects appropriate size

3. **Optimize Image Sizes**
   - Resize images to actual display size
   - Remove unnecessary pixels
   - Compress images properly

4. **Lazy Load Below-the-Fold Images**
   - Use `loading="lazy"` attribute
   - Defer non-critical images
   - Prioritize LCP image

### Expected Impact
- LCP: 4.8s → <3.0s (mobile), 1.1s → <1.0s (desktop)
- Network payload: -868 KiB (mobile), -361 KiB (desktop)
- Performance: +10-15 points

## Phase 3: Mobile FCP Optimization (High)

### Goal
Reduce mobile FCP from 3.8s to <1.8s

### Tasks
1. **Expand Critical CSS**
   - Identify mobile-specific above-the-fold styles
   - Inline critical CSS for mobile
   - Defer non-critical CSS

2. **Optimize Font Loading**
   - Use `font-display: swap`
   - Preload critical fonts
   - Consider reducing font count
   - Use `size-adjust` to prevent reflow

3. **Reduce Render-Blocking Resources**
   - Defer all non-critical CSS
   - Use `loadCSS` for async loading
   - Minimize inline styles

4. **Optimize Server Response**
   - Check TTFB (Time to First Byte)
   - Enable compression (gzip/brotli)
   - Optimize PHP execution

### Expected Impact
- FCP: 3.8s → <1.8s (2x improvement)
- Performance: +10-15 points

## Phase 4: CSS Optimization (Medium)

### Goal
Reduce unused CSS and minify all CSS

### Tasks
1. **Regenerate PurgeCSS**
   - Update PurgeCSS configuration
   - Scan all HTML/PHP files
   - Remove unused CSS (41 KiB savings)

2. **Verify Minification**
   - Ensure all CSS is minified
   - Check minified file sizes
   - Fix any minification issues (3 KiB savings)

3. **Remove Unused Styles**
   - Audit CSS files
   - Remove dead code
   - Consolidate duplicate styles

### Expected Impact
- Network payload: -44 KiB
- Performance: +2-5 points

## Phase 5: JavaScript Optimization (Medium)

### Goal
Optimize long main-thread tasks and animations

### Tasks
1. **Break Up Long Tasks**
   - Identify long tasks in Performance panel
   - Split into smaller chunks
   - Use `requestIdleCallback` for non-critical work

2. **Optimize Animations**
   - Ensure all 129 animations use GPU acceleration
   - Use `transform` and `opacity` only
   - Add `will-change` where appropriate

3. **Defer Non-Critical JavaScript**
   - Move non-critical JS to end of page
   - Use `defer` or `async` attributes
   - Lazy load heavy libraries

### Expected Impact
- TBT: Maintain low (0ms mobile, 50ms desktop)
- Performance: +3-5 points

## Implementation Order

1. **Week 1**: Phase 1 (Mobile CLS) - Critical
2. **Week 1**: Phase 2 (Image Optimization) - High
3. **Week 2**: Phase 3 (Mobile FCP) - High
4. **Week 2**: Phase 4 (CSS Optimization) - Medium
5. **Week 3**: Phase 5 (JavaScript Optimization) - Medium

## Success Metrics

### Mobile
- Performance: 50 → 90+ ✅
- CLS: 0.532 → <0.1 ✅
- LCP: 4.8s → <2.5s ✅
- FCP: 3.8s → <1.8s ✅

### Desktop
- Performance: 78 → 90+ ✅
- CLS: 0.324 → <0.1 ✅
- LCP: 1.1s → <2.5s ✅ (already good)
- FCP: 0.7s → <1.8s ✅ (already good)

## Notes

- Focus on mobile first (bigger gap to close)
- Desktop is already close (78 → 90+ needs 12 points)
- CLS is the biggest issue on both platforms
- Image optimization offers the largest savings
- TBT is already excellent (no changes needed)

