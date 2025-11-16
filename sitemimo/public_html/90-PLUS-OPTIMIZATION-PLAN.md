# 90+ Optimization Plan - Mobile & Desktop

**Date**: 2025-11-16  
**Current Status**: Mobile 72, Desktop (TBD)  
**Target**: 90+ on both platforms

## Current Scores

### Mobile
- **Performance**: 72 (Target: 90+)
- **CLS**: 0.368 (Target: <0.1) 🔴 Critical
- **LCP**: 3.0s (Target: <2.5s) 🟡 High
- **FCP**: 2.6s (Target: <1.8s) 🟡 High
- **TBT**: 0ms ✅ Perfect
- **SI**: 3.8s (Target: <3.4s) 🟡 Medium

### Desktop
- **Performance**: TBD (need to run analysis)
- **Target**: 90+

## Priority-Based Action Plan

### 🔴 Phase 1: Critical CLS Fixes (Mobile & Desktop)
**Impact**: +15-20 points (Mobile), +10-15 points (Desktop)  
**Estimated Time**: 2-3 hours  
**Target CLS**: <0.1 (currently 0.368 mobile)

#### 1.1 Investigate Layout Shift Culprits
- [ ] Use Chrome DevTools Performance panel to identify specific elements causing shifts
- [ ] Check "Layout shift culprits" insight in PageSpeed report
- [ ] Identify dynamic content causing shifts (Google Reviews, lazy-loaded images)
- [ ] Document all shift sources with timestamps and element IDs

#### 1.2 Fix Image Layout Shifts
- [ ] Verify ALL images have explicit `width` and `height` attributes
- [ ] Ensure `picture_webp()` function outputs dimensions for all images
- [ ] Add `aspect-ratio` CSS to images without dimensions
- [ ] Reserve space for images before they load (skeleton/placeholder)
- [ ] Fix any images loaded via JavaScript that don't have dimensions

#### 1.3 Fix Dynamic Content Shifts
- [ ] Reserve space for Google Reviews carousel (min-height, contain)
- [ ] Add explicit dimensions to review images
- [ ] Prevent font reflow with `font-display: swap` and `size-adjust`
- [ ] Use `contain: layout style paint` on dynamic content containers

#### 1.4 Fix Font Loading Shifts
- [ ] Verify all fonts use `font-display: swap`
- [ ] Add `size-adjust` to prevent layout shift during font swap
- [ ] Reserve space for text elements before fonts load
- [ ] Use `font-display: optional` for decorative fonts

#### 1.5 Fix Ad/Third-Party Content Shifts
- [ ] Reserve space for third-party embeds (Google Maps, Reviews)
- [ ] Use `aspect-ratio` for iframes and embeds
- [ ] Add `contain: layout` to third-party containers

**Files to Modify**:
- `inc/image-helper.php` - Ensure all images have dimensions
- `inc/critical-css.php` - Add containment for dynamic content
- `product.css` - Add min-heights and containment
- `inc/google-reviews.php` - Reserve space for reviews

**Success Criteria**: CLS <0.1 on both mobile and desktop

---

### 🟡 Phase 2: Image Optimization (Mobile & Desktop)
**Impact**: +5-10 points (Mobile), +3-5 points (Desktop)  
**Estimated Time**: 1-2 hours  
**Target**: 791 KiB savings (mobile)

#### 2.1 Verify AVIF/WebP Deployment
- [ ] Check if `picture_webp()` is generating correct `<picture>` elements
- [ ] Verify AVIF/WebP images exist in production
- [ ] Check Network tab to confirm AVIF/WebP are being served
- [ ] Verify Content-Type headers are correct

#### 2.2 Optimize Image Sizes
- [ ] Check if responsive `srcset` is working correctly
- [ ] Verify `sizes` attribute matches actual display sizes
- [ ] Ensure category images (150x150) use correct sizes
- [ ] Ensure service images use responsive sizes
- [ ] Optimize hero images (LCP candidates)

#### 2.3 Lazy Loading
- [ ] Verify `loading="lazy"` is on below-fold images
- [ ] Ensure LCP images have `loading="eager"` or no lazy
- [ ] Check if `fetchpriority="high"` is on LCP images

**Files to Modify**:
- `inc/image-helper.php` - Verify AVIF/WebP generation
- `index.php` - Verify LCP image preloads
- Check production server for AVIF/WebP files

**Success Criteria**: Images served in AVIF/WebP, 791 KiB savings achieved

---

### 🟡 Phase 3: CSS/JS Minification Verification (Mobile & Desktop)
**Impact**: +2-3 points (Mobile), +1-2 points (Desktop)  
**Estimated Time**: 30 minutes  
**Target**: 46 KiB savings (36 KiB CSS + 10 KiB CSS minify)

#### 3.1 Verify Production Configuration
- [ ] Check `USE_MINIFIED` flag in production `config.php`
- [ ] Verify purged CSS files exist in `css/purged/`
- [ ] Verify minified CSS files exist in `css/purged/*.min.css`
- [ ] Check `asset-helper.php` is serving purged/minified files

#### 3.2 Verify File Sizes
- [ ] Check Network tab for CSS file sizes
- [ ] Verify `product.css` is ~4KB (minified) not 80KB
- [ ] Verify other CSS modules are minified
- [ ] Check JavaScript files are minified

#### 3.3 Fix if Not Working
- [ ] Enable `USE_MINIFIED` in production if disabled
- [ ] Regenerate purged CSS if needed
- [ ] Regenerate minified CSS/JS if needed
- [ ] Clear CDN/cache if using one

**Files to Check**:
- `config.php` - `USE_MINIFIED` flag
- `css/purged/product.min.css` - Should be ~4KB
- Network tab in Chrome DevTools

**Success Criteria**: CSS/JS files are minified and purged in production

---

### 🟡 Phase 4: LCP Optimization (Mobile & Desktop)
**Impact**: +3-5 points (Mobile), +1-2 points (Desktop)  
**Estimated Time**: 1 hour  
**Target**: LCP <2.5s (currently 3.0s mobile)

#### 4.1 Optimize LCP Image
- [ ] Identify LCP element (likely hero image)
- [ ] Ensure LCP image has `preload` link
- [ ] Ensure LCP image has `fetchpriority="high"`
- [ ] Optimize LCP image format (AVIF/WebP)
- [ ] Reduce LCP image size if possible

#### 4.2 Optimize LCP Timing
- [ ] Reduce server response time (TTFB)
- [ ] Ensure critical CSS is inline
- [ ] Defer non-critical CSS
- [ ] Preconnect to image CDN if used

#### 4.3 LCP Element Optimization
- [ ] Ensure LCP element is above the fold
- [ ] Remove any blocking resources before LCP
- [ ] Optimize font loading for LCP text

**Files to Modify**:
- `index.php` - LCP image preloads
- `inc/critical-css.php` - Ensure LCP styles are inline
- Server configuration - TTFB optimization

**Success Criteria**: LCP <2.5s on mobile, <2.5s on desktop

---

### 🟢 Phase 5: FCP Optimization (Mobile & Desktop)
**Impact**: +2-3 points (Mobile), +1-2 points (Desktop)  
**Estimated Time**: 1 hour  
**Target**: FCP <1.8s (currently 2.6s mobile)

#### 5.1 Expand Critical CSS
- [ ] Add more above-fold styles to critical CSS
- [ ] Include hero section styles
- [ ] Include navbar styles
- [ ] Include first paragraph styles

#### 5.2 Optimize Font Loading
- [ ] Preload critical fonts (Nunito)
- [ ] Use `font-display: swap` for all fonts
- [ ] Reduce font file sizes if possible
- [ ] Consider subsetting fonts

#### 5.3 Reduce Render-Blocking Resources
- [ ] Ensure all CSS uses `loadCSS` (deferred)
- [ ] Ensure all JS has `defer` or `async`
- [ ] Minimize inline scripts

**Files to Modify**:
- `inc/critical-css.php` - Expand critical CSS
- `index.php` - Font preloads
- Verify `loadCSS` is working

**Success Criteria**: FCP <1.8s on mobile, <1.0s on desktop

---

### 🟢 Phase 6: Animation Optimization (Mobile & Desktop)
**Impact**: +1-2 points  
**Estimated Time**: 30 minutes  
**Target**: All animations GPU-accelerated

#### 6.1 Verify GPU Acceleration
- [ ] Check all animations use `transform` and `opacity`
- [ ] Verify `translateZ(0)` on animated elements
- [ ] Verify `will-change` is set appropriately
- [ ] Check 34 animated elements are optimized

#### 6.2 Fix Non-Composited Animations
- [ ] Identify which 34 elements are not composited
- [ ] Add `translateZ(0)` to force GPU acceleration
- [ ] Add `will-change` where needed
- [ ] Remove animations that cause layout shifts

**Files to Modify**:
- `css/modules/animations.css` - GPU acceleration
- `product.css` - Animation optimizations

**Success Criteria**: All animations GPU-accelerated, no layout shifts from animations

---

## Implementation Order

### Week 1: Critical Fixes
1. **Day 1-2**: Phase 1 (CLS Fixes) - Highest impact
2. **Day 3**: Phase 2 (Image Optimization) - High impact
3. **Day 4**: Phase 3 (CSS/JS Minification) - Quick win

### Week 2: Performance Tuning
4. **Day 5**: Phase 4 (LCP Optimization)
5. **Day 6**: Phase 5 (FCP Optimization)
6. **Day 7**: Phase 6 (Animation Optimization)

## Testing & Validation

### After Each Phase
- [ ] Run PageSpeed Insights on mobile
- [ ] Run PageSpeed Insights on desktop
- [ ] Compare scores with previous
- [ ] Document improvements

### Final Validation
- [ ] Mobile Performance: 90+
- [ ] Desktop Performance: 90+
- [ ] CLS <0.1 on both
- [ ] LCP <2.5s on both
- [ ] FCP <1.8s mobile, <1.0s desktop

## Expected Final Scores

### Mobile
- **Performance**: 72 → 90+ (+18 points)
- **CLS**: 0.368 → <0.1 (+15-20 points)
- **LCP**: 3.0s → <2.5s (+3-5 points)
- **FCP**: 2.6s → <1.8s (+2-3 points)
- **Image optimization**: +5-10 points
- **CSS/JS minification**: +2-3 points

### Desktop
- **Performance**: TBD → 90+
- **CLS**: TBD → <0.1
- **LCP**: TBD → <2.5s
- **FCP**: TBD → <1.0s

## Risk Mitigation

### Potential Issues
1. **CLS fixes might break layout**: Test thoroughly, use containment carefully
2. **Image optimization might not work**: Verify server supports AVIF/WebP
3. **Minification might break styles**: Test in staging first
4. **Font loading might cause FOUT**: Use `font-display: swap` correctly

### Rollback Plan
- Keep previous versions of files in git
- Test in staging before production
- Monitor after deployment
- Have rollback procedure ready

## Success Metrics

### Minimum Success
- Mobile: 85+ (13 point improvement)
- Desktop: 90+ (TBD improvement)
- CLS <0.15 on both

### Target Success
- Mobile: 90+ (18 point improvement)
- Desktop: 90+ (TBD improvement)
- CLS <0.1 on both

### Optimal Success
- Mobile: 95+ (23 point improvement)
- Desktop: 95+ (TBD improvement)
- CLS <0.05 on both

## Notes

- Focus on CLS first (biggest impact)
- Verify optimizations are deployed in production
- Test on real devices, not just emulation
- Monitor Core Web Vitals in Search Console
- Keep documentation updated

