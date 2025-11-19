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

## Optimization Changes Applied

### 1. Removed Framer Motion from LCP Element ✅
- Removed `motion.div` wrapper around hero image
- Converted scale animation to CSS (`animate-hero-image-scale`)
- Removed Framer Motion import from hero component

### 2. Converted Animations to CSS ✅
- Hero image scale: CSS animation (1.1 → 1 over 1.2s)
- Hero content fade: CSS animation (opacity 0→1, translateY 30px→0)
- Added to Tailwind config: `hero-image-scale`, `hero-content-fade`

### 3. Made Hero Server Component ✅
- Removed `'use client'` directive
- Hero now server-rendered, no JS hydration required for LCP element

### 4. Optimized Image Sizes ✅
- Changed from `sizes="100vw"` to `sizes="(max-width: 768px) 100vw, 1920px"`
- Mobile downloads ~768px width instead of full viewport

### 5. Removed Redundant Preload ✅
- Removed manual `<link rel="preload">` from layout.tsx
- Next.js Image with `priority` handles this automatically

## Expected Results After Deployment

- **LCP**: < 2.5s (from 13.65s)
- **FCP**: < 1.8s (from 6.26s)
- **Mobile Performance**: ≥ 90 (from 59)
- **LCP Element**: Same (hero-bg.webp) but rendered without JS blocking

## Note on Production Testing

Production URL still shows old code (LCP 13.45s) because changes need to be deployed to Vercel. After deployment, re-run Lighthouse tests to validate improvements.

---

## Latest Optimization Round (2025-01-29)

### Current State (From Latest PageSpeed Insights)
- **Performance**: 74/100
- **LCP**: 13.4s ❌ (target: <2.5s)
- **FCP**: 1.8s ✅
- **TBT**: 0ms ✅
- **CLS**: 0 ✅
- **Diagnostics**: 
  - "Improve image delivery" (~49 KiB savings)
  - "Reduce unused JavaScript" (~42 KiB)
  - Preconnect candidates: `scontent-lax7-1.cdninstagram.com`, `scontent-lax3-2.cdninstagram.com`

### Identified LCP Issue
Based on diagnostics and code analysis, the LCP delay is likely caused by:
1. **Instagram Reel iframes** in `#MomentoMIMO` section loading from `cdninstagram.com`
2. Iframes loading synchronously even when below fold
3. Large images from Instagram CDN blocking LCP measurement

### Changes Applied

#### 1. Lazy-Load Instagram Iframes ✅
- Added `IntersectionObserver` to `CelebrityCard` component
- Iframes only load when card is visible (50px before viewport)
- Show static image placeholder until iframe loads
- Added `loading="lazy"` attribute to iframes

#### 2. Optimized Gallery Images ✅
- Added proper `sizes="(max-width: 768px) 50vw, 25vw"` to all `CelebrityCard` images
- Ensures mobile devices download appropriate image sizes

#### 3. Removed Framer Motion from MomentoMimo ✅
- Replaced Framer Motion animations with CSS animations
- Reduces JavaScript bundle size (~40 KiB savings)
- Section uses `content-visibility: auto` for better performance

#### 4. Optimized Hero Image Sizes ✅
- Changed hero `sizes` from `"(max-width: 768px) 100vw, 1920px"` to `"100vw"`
- Simplifies sizes calculation for better mobile performance

#### 5. Ensured Below-Fold Loading ✅
- `MomentoMimo` section is below initial viewport
- All Instagram iframes are lazy-loaded
- No preconnect needed for Instagram CDN (not used above fold)

### Expected Results After Deployment

- **LCP**: < 2.5s (from 13.4s) - hero image should be LCP element
- **Performance**: ≥ 90 (from 74)
- **Unused JS**: Reduced by ~40 KiB
- **LCP Element**: Hero image (`hero-bg.webp`), not Instagram iframes

### Next Steps

1. Deploy changes to Vercel
2. Run Lighthouse mobile 3+ times to verify improvements
3. Document actual LCP element from Lighthouse report
4. Update this doc with before/after metrics

