# Mobile Lighthouse Baseline

**Date**: 2025-01-29  
**URL**: https://mimo-site.vercel.app/  
**Test Method**: PageSpeed Insights API (mobile strategy)

## Baseline Metrics (Before Optimization)

### Performance Scores
- **Performance**: 59/100 ❌
- **Accessibility**: 100/100 ✅
- **Best Practices**: 96/100 ✅
- **SEO**: 100/100 ✅

### Core Web Vitals
- **LCP (Largest Contentful Paint)**: 13,651ms (13.65s) ❌
- **FCP (First Contentful Paint)**: 6,255ms (6.26s) ❌
- **CLS (Cumulative Layout Shift)**: 0.000 ✅
- **TBT (Total Blocking Time)**: 32ms ✅
- **TTI (Time to Interactive)**: 13,689ms (13.69s) ❌

### LCP Element
- **Element**: `hero-bg.webp` image
- **Location**: Hero section background image
- **Current Implementation**: 
  - Wrapped in Framer Motion `motion.div` with scale animation
  - Component is `'use client'` requiring JS hydration
  - Image uses `fill`, `priority`, `fetchPriority="high"`, `sizes="100vw"`
  - File size: 135KB webp

### Top Opportunities
1. **Reduce unused JavaScript**: 21 KiB savings potential
2. **LCP too slow**: 13.65s (target: < 2.5s)

### Root Cause Analysis

#### 1. Framer Motion Blocking Render
- Hero image is wrapped in `motion.div` with animation
- Requires Framer Motion JS bundle to be loaded and executed
- Client component (`'use client'`) prevents server-side rendering of LCP element
- JS must hydrate before image can render

#### 2. Image Optimization Issues
- `sizes="100vw"` downloads full viewport width on mobile
- No responsive image variants for mobile vs desktop
- Manual preload in layout.tsx may conflict with Next.js Image optimization

#### 3. Font Loading
- Custom fonts (Bueno, Satoshi) loaded with `display: 'swap'` ✅
- Fonts are not blocking (CLS is 0), but may contribute to FCP delay

#### 4. JavaScript Bundle
- 21 KiB unused JavaScript
- Framer Motion included in initial bundle for hero section

## Expected Improvements

After removing Framer Motion from LCP element and optimizing image loading:

- **LCP**: < 2.5s (from 13.65s)
- **FCP**: < 1.8s (from 6.26s)
- **Performance Score**: ≥ 90 (from 59)
- **LCP Element**: Same (hero-bg.webp) but rendered without JS blocking

## Next Steps

1. Remove Framer Motion from hero image container
2. Convert hero animations to CSS
3. Make hero component server-rendered
4. Optimize image sizes attribute for mobile
5. Remove redundant preload
6. Validate with 3+ Lighthouse runs

