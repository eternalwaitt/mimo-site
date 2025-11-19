# Benchmark Testing Guide - v2.6.12

**Date**: 2025-11-16  
**Purpose**: Test all optimization phases and measure improvements

## Pre-Testing Checklist

- [x] All Phase 1-6 changes applied
- [x] Local server running (`php -S localhost:8000`)
- [ ] Production deployment complete
- [ ] `USE_MINIFIED` enabled in production
- [ ] Purged CSS files deployed

## Testing Methods

### Method 1: PageSpeed Insights Web Interface (Recommended)

1. **Navigate to PageSpeed Insights**
   - URL: https://pagespeed.web.dev/
   - Or use direct link: https://pagespeed.web.dev/analysis?url=https://minhamimo.com.br/

2. **Test Mobile**
   - Enter URL: `https://minhamimo.com.br/`
   - Select "Mobile" tab
   - Click "Analyze"
   - Wait 30-60 seconds for results
   - Capture:
     - Performance score
     - CLS, LCP, FCP, TBT, SI
     - All insights and diagnostics

3. **Test Desktop**
   - Switch to "Desktop" tab
   - Click "Analyze"
   - Wait 30-60 seconds for results
   - Capture same metrics as mobile

4. **Save Results**
   - Copy the report URL
   - Take screenshots of key metrics
   - Document scores in `PAGESPEED-RESULTS-v2.6.12-FINAL.md`

### Method 2: Chrome DevTools Lighthouse

1. **Open Chrome DevTools**
   - Press F12 or right-click → Inspect
   - Go to "Lighthouse" tab

2. **Configure Lighthouse**
   - Select "Performance"
   - Select "Mobile" or "Desktop"
   - Check "Clear storage"
   - Click "Analyze page load"

3. **Review Results**
   - Performance score
   - Core Web Vitals
   - Opportunities and Diagnostics
   - Save as JSON or export report

### Method 3: PageSpeed Insights API (If Available)

```bash
cd sitemimo/public_html
./build/pagespeed-api-test.sh
```

## Metrics to Capture

### Core Web Vitals
- **FCP** (First Contentful Paint): Target <1.8s (mobile), <1.0s (desktop)
- **LCP** (Largest Contentful Paint): Target <2.5s
- **CLS** (Cumulative Layout Shift): Target <0.1
- **TBT** (Total Blocking Time): Target <200ms
- **SI** (Speed Index): Target <3.4s (mobile), <3.0s (desktop)

### Performance Score
- **Mobile**: Current 72, Target 90+
- **Desktop**: Current TBD, Target 90+

### Key Insights
- Layout shift culprits
- Image delivery savings
- Unused CSS/JS
- Minification status
- Non-composited animations

## Baseline Comparison

### Mobile (Previous: v2.6.12 Initial)
- Performance: 72
- CLS: 0.368
- LCP: 3.0s
- FCP: 2.6s
- TBT: 0ms ✅
- SI: 3.8s

### Desktop (Previous: TBD)
- Need to establish baseline

## Expected Improvements

### Mobile
- **Performance**: 72 → 95-100 (+23-28 points)
- **CLS**: 0.368 → <0.2 (target: <0.1)
- **LCP**: 3.0s → <2.5s
- **FCP**: 2.6s → <1.8s
- **SI**: 3.8s → <3.4s

### Desktop
- **Performance**: TBD → 90+
- **CLS**: TBD → <0.1
- **LCP**: TBD → <2.5s
- **FCP**: TBD → <1.0s

## Testing Checklist

### Local Testing
- [ ] Homepage loads correctly
- [ ] No layout shifts visible
- [ ] Images load properly
- [ ] Testimonials carousel works
- [ ] No console errors
- [ ] CSS/JS files are correct sizes

### Production Testing
- [ ] Homepage loads correctly
- [ ] `USE_MINIFIED` is enabled
- [ ] Purged CSS is served (check Network tab)
- [ ] AVIF/WebP images are served (check Network tab)
- [ ] No layout shifts
- [ ] All functionality works

### Benchmark Testing
- [ ] Run PageSpeed Insights mobile
- [ ] Run PageSpeed Insights desktop
- [ ] Capture all metrics
- [ ] Compare with baseline
- [ ] Document findings

## Documentation Template

```markdown
# PageSpeed Insights Results - v2.6.12 Final

**Date**: [DATE]
**URL**: https://minhamimo.com.br/
**Mobile Report**: [URL]
**Desktop Report**: [URL]

## Mobile Results

### Scores
- Performance: [SCORE]
- Accessibility: [SCORE]
- Best Practices: [SCORE]
- SEO: [SCORE]

### Core Web Vitals
- FCP: [VALUE]s (Target: <1.8s)
- LCP: [VALUE]s (Target: <2.5s)
- CLS: [VALUE] (Target: <0.1)
- TBT: [VALUE]ms (Target: <200ms)
- SI: [VALUE]s (Target: <3.4s)

### Improvements
- Performance: 72 → [SCORE] (+[POINTS] points)
- CLS: 0.368 → [VALUE] ([CHANGE])
- LCP: 3.0s → [VALUE]s ([CHANGE])
- FCP: 2.6s → [VALUE]s ([CHANGE])

## Desktop Results

[Same format as mobile]

## Findings

### ✅ Improvements Achieved
- [List improvements]

### ⚠️ Remaining Issues
- [List remaining issues]

### 📊 Next Steps
- [List next steps]
```

## Notes

- Test on production after deployment
- Clear browser cache before testing
- Test multiple times for consistency
- Document all findings for future reference

