# Performance Guide - Mimo Site

**Last Updated**: 2025-01-29  
**Target**: Mobile Lighthouse Performance ≥ 90

## Quick Start

### Running Lighthouse Locally

1. **Build production bundle**:
   ```bash
   npm run build
   npm run start
   ```

2. **Test with PageSpeed Insights** (requires API key):
   ```bash
   npm run lighthouse:home
   ```

3. **Or use Chrome DevTools**:
   - Open `http://localhost:3000`
   - Open DevTools → Lighthouse tab
   - Select "Mobile" strategy
   - Run audit

### Running Lighthouse on Production

```bash
npm run lighthouse:home
```

This tests `https://mimo-site.vercel.app` using PageSpeed Insights API.

## Performance Rules

### 1. LCP Element Optimization

**Critical Rule**: Never use Framer Motion or any client-side JS on the LCP element.

- ✅ **DO**: Use server components for hero/LCP sections
- ✅ **DO**: Use CSS animations for LCP element animations
- ❌ **DON'T**: Wrap LCP images in `motion.div` or client components
- ❌ **DON'T**: Require JS hydration for LCP element

**Example - Hero Image (LCP element)**:
```tsx
// ✅ GOOD: Server component, CSS animation
<div className="animate-hero-image-scale">
  <Image
    src="/images/hero-bg.webp"
    fill
    priority
    fetchPriority="high"
    sizes="(max-width: 768px) 100vw, 1920px"
  />
</div>

// ❌ BAD: Client component, Framer Motion
'use client'
<motion.div>
  <Image ... />
</motion.div>
```

### 2. Image Optimization

**Always use Next.js Image component** with proper attributes:

```tsx
<Image
  src="/path/to/image.webp"
  width={1920}
  height={1080}
  priority={isAboveFold} // true for LCP element
  fetchPriority={isLCP ? "high" : "auto"}
  sizes="(max-width: 768px) 100vw, 1920px" // Mobile-friendly
  quality={85} // Balance quality/size
  alt="Descriptive alt text"
/>
```

**Rules**:
- Use `priority` only for above-the-fold images (especially LCP)
- Use `fetchPriority="high"` only for the LCP element
- Always provide `sizes` attribute with mobile breakpoints
- Use WebP/AVIF formats (Next.js handles conversion)
- Quality: 75-85 for photos, 90+ for graphics/logos

### 3. Animations

**Use CSS animations for above-the-fold content**:

```tsx
// ✅ GOOD: CSS animation
<div className="animate-fade-in-up">
  <h1>Content</h1>
</div>

// ❌ BAD: Framer Motion for above-fold
<motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }}>
  <h1>Content</h1>
</motion.div>
```

**When to use Framer Motion**:
- ✅ Below-the-fold animations
- ✅ Complex interactions (not just fade/scale)
- ✅ User-triggered animations

**When NOT to use Framer Motion**:
- ❌ LCP element
- ❌ Above-the-fold content
- ❌ Simple fade/scale animations

### 4. Font Loading

**Current setup** (already optimized):
```tsx
const font = localFont({
  src: '../public/fonts/font.woff2',
  display: 'swap', // ✅ Prevents FOIT
  fallback: ['system-fonts'],
})
```

**Rules**:
- Always use `display: 'swap'`
- Only load required weights
- Use `next/font/local` for custom fonts
- System font fallbacks prevent layout shift

### 5. Component Architecture

**Server Components by Default**:
- ✅ Use server components for static content
- ✅ Only use `'use client'` when necessary (interactivity, hooks)
- ✅ Keep LCP elements in server components

**Code Splitting**:
- ✅ Use dynamic imports for below-fold components
- ✅ Lazy load heavy libraries (Framer Motion, charts, etc.)
- ❌ Don't bundle everything in initial load

### 6. Bundle Size

**Monitor bundle size**:
```bash
npm run analyze
```

**Rules**:
- Keep First Load JS < 200 KB
- Remove unused dependencies
- Use dynamic imports for heavy components
- Avoid importing entire libraries (use tree-shaking)

## Current LCP Element

**Element**: `hero-bg.webp` image in hero section  
**Location**: `components/sections/hero-manifesto.tsx`  
**Implementation**: Server component, CSS animation, optimized `sizes` attribute  
**Target LCP**: < 2.5s

### LCP Quality Gate Rules

**Critical Rules for Home Page**:
1. **LCP must be the hero image/section**, not galleries or remote embeds
2. **Any new large images near the top of the page must**:
   - Use `next/image` with proper `sizes` attribute
   - Be reasonably small in file size (<200 KiB for mobile)
   - Not be larger than the hero element in the initial viewport
3. **Remote embeds (Instagram, YouTube, etc.) must**:
   - Be lazy-loaded with `IntersectionObserver`
   - Only load when visible (below fold)
   - Not interfere with LCP measurement
4. **Gallery sections must**:
   - Be below the initial viewport on mobile
   - Use `content-visibility: auto` for performance
   - Lazy-load all images and iframes

## Performance Checklist

Before adding new features:

- [ ] Is the new component above-the-fold? → Use server component
- [ ] Does it need animation? → Use CSS, not Framer Motion
- [ ] Does it include images? → Use Next.js Image with proper `sizes`
- [ ] Is it heavy? → Use dynamic import
- [ ] Does it require JS? → Only if absolutely necessary

## Performance Budget

**Targets for Home Page (Mobile)**:
- **Initial JS Bundle**: ≤ 150 KiB (first load, gzipped)
- **Hero Image**: ≤ 200 KiB (mobile, WebP/AVIF)
- **LCP**: < 2.5s (Lighthouse Slow 4G)
- **FCP**: < 1.5s
- **TBT**: < 200ms
- **CLS**: < 0.1

**Guidelines**:
- Any new hero-level section must respect these numbers
- Any new third-party script must be justified in documentation
- New images must use `next/image` with proper `sizes`
- Large libraries must be dynamically imported if not critical

## Adding New Pages and Content

### Rules for New Pages

**Before merging any new page**:
1. Run `npm run lighthouse:home` (or test the specific page)
2. Verify Performance ≥ 95 on mobile
3. Verify LCP < 2.5s
4. Check bundle size impact (`npm run analyze`)

**New page with heavy imagery**:
- Must use `<Image>` with proper `sizes` attribute
- Should not introduce a new massive LCP candidate without testing
- Images should be optimized (WebP/AVIF via Next.js)

**New sections on home**:
- Must be tested with `npm run lighthouse:home` before merging
- Must not change the LCP element or push Performance below threshold (≥95)
- Below-fold sections should use `content-visibility: auto`

**Reuse existing patterns**:
- Lazy loading for off-screen content
- Placeholders for embeds (social media, etc.)
- CSS animations instead of JS for simple effects

## Monitoring

### CI/CD
- Lighthouse CI runs on PRs and pushes to main (`.github/workflows/ci.yml`)
- Steps: lint → type-check → build → lighthouse
- **Fails if**:
  - Performance < 95 on mobile (configurable via `LIGHTHOUSE_MIN_PERFORMANCE`)
  - LCP > 2.5s (configurable via `LIGHTHOUSE_MAX_LCP`)
  - Other categories < 90
- Results stored as artifacts for 30 days

**Local Testing**:
- Use `npm run lighthouse:local` to test against `http://localhost:3000`
- Requires: `npm run build && npm run start` running in background
- Validates Performance ≥ 95 and LCP < 2.5s

**Environment Variables** (optional):
- `LIGHTHOUSE_MIN_PERFORMANCE`: Minimum performance score (default: 95)
- `LIGHTHOUSE_MAX_LCP`: Maximum LCP in ms (default: 2500)
- `LIGHTHOUSE_BASE_URL`: URL to test (default: https://mimo-site.vercel.app)

**Running locally**:
```bash
# Test against localhost (requires build + start)
npm run build
npm run start &
npm run lighthouse:local

# Test against production
npm run lighthouse:home

# Test with custom thresholds (production)
LIGHTHOUSE_MIN_PERFORMANCE=98 LIGHTHOUSE_MAX_LCP=2000 npm run lighthouse:home
```

### Manual Testing
- Run `npm run lighthouse:home` before deploying
- Check bundle size: `npm run analyze`
- Test on real mobile device
- Review Lighthouse reports in `docs/lighthouse/`

### Monthly Performance Checks

**Schedule**: Once per month, or before major campaigns

**Pages to test**:
- `/` (home) - **Required**
- `/servicos` - If high traffic
- `/sobre` - If high traffic
- Any new high-traffic pages

**Process**:
1. Run PageSpeed Insights on each page
2. Append results to `docs/lighthouse-mobile-baseline.md` as historical log
3. Note any regressions and investigate
4. Update performance budget if needed

## Troubleshooting

### High LCP (> 2.5s)
1. Check if LCP element is wrapped in client component
2. Verify image `sizes` attribute is mobile-friendly
3. Check if fonts are blocking render
4. Verify `priority` and `fetchPriority` are set correctly

### Low Performance Score
1. Check Lighthouse opportunities (unused JS, large images)
2. Verify bundle size (`npm run analyze`)
3. Check for render-blocking resources
4. Verify server-side rendering is working

### High TTFB
1. Check server response time
2. Verify static generation is working
3. Check CDN cache headers
4. Investigate server-side computation

## Resources

- [Next.js Image Optimization](https://nextjs.org/docs/app/api-reference/components/image)
- [Web Vitals](https://web.dev/vitals/)
- [Lighthouse Scoring](https://web.dev/performance-scoring/)
- [CSS Animations](https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Animations)

