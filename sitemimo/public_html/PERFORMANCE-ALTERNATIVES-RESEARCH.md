# Performance Optimization Alternatives Research

**Date**: 2025-11-16  
**Purpose**: Research alternative tools, frameworks, and approaches to achieve 90+ PageSpeed score

## Current Situation

- **Minification**: Using `csso-cli` via npx (working but producing small files)
- **PurgeCSS**: Working correctly (10KB purged from 83KB)
- **Issue**: Minified purged CSS is 861B (too small, asset helper skips it)
- **Current Build**: Shell scripts (bash) - simple but effective

## Alternative Minification Tools

### 1. **cssnano (PostCSS Plugin)** ⭐ RECOMMENDED
**Why**: Modern, actively maintained, better compression than csso  
**GitHub**: https://github.com/cssnano/cssnano  
**Pros**:
- Better compression ratios than csso
- Part of PostCSS ecosystem (extensible)
- Handles modern CSS features better
- Can be used standalone or via PostCSS
- More reliable output (less likely to produce broken files)

**Cons**:
- Requires PostCSS setup (but can use standalone)
- Slightly slower than csso

**Implementation**:
```bash
# Standalone usage
npx --yes cssnano-cli css/purged/product.css --output css/purged/product.min.css

# Or via PostCSS (more powerful)
npx --yes postcss css/purged/product.css --use cssnano --output css/purged/product.min.css
```

### 2. **Clean-CSS** ⭐ GOOD ALTERNATIVE
**Why**: Fast, reliable, widely used  
**GitHub**: https://github.com/clean-css/clean-css  
**Pros**:
- Very fast
- Reliable output
- Good compression
- Can handle multiple files
- Well-documented

**Cons**:
- Less aggressive optimization than cssnano
- Doesn't do structural optimization like csso

**Implementation**:
```bash
npx --yes clean-css-cli -o css/purged/product.min.css css/purged/product.css
```

### 3. **PostCSS with Plugins** ⭐ BEST FOR FUTURE
**Why**: Most flexible, extensible, industry standard  
**GitHub**: https://github.com/postcss/postcss  
**Pros**:
- Plugin ecosystem (autoprefixer, cssnano, etc.)
- Can combine multiple optimizations
- Future-proof
- Can be integrated into build pipeline
- Already mentioned in IMPROVEMENTS.md

**Cons**:
- Requires more setup
- Learning curve

**Implementation**:
```bash
# Create postcss.config.js
npx --yes postcss css/purged/product.css --use cssnano autoprefixer --output css/purged/product.min.css
```

## Alternative Build Systems

### 1. **Gulp.js** ⭐ RECOMMENDED FOR AUTOMATION
**Why**: Task runner, can automate entire build process  
**GitHub**: https://github.com/gulpjs/gulp  
**Pros**:
- Automates minification, purging, image optimization
- Watch mode for development
- Can combine multiple tasks
- Large plugin ecosystem
- Better than shell scripts for complex workflows

**Cons**:
- Requires Node.js setup
- More complex than shell scripts
- Learning curve

**Example gulpfile.js**:
```javascript
const gulp = require('gulp');
const cleanCSS = require('gulp-clean-css');
const purgecss = require('gulp-purgecss');

gulp.task('minify-css', () => {
  return gulp.src('css/purged/*.css')
    .pipe(cleanCSS())
    .pipe(gulp.dest('css/purged/'));
});
```

### 2. **Webpack** ⚠️ OVERKILL FOR THIS PROJECT
**Why**: Module bundler, more than needed  
**Cons**:
- Too complex for PHP project
- Designed for JS apps, not PHP
- Overkill for current needs

### 3. **PHP On-the-Fly Minification** ⭐ INTERESTING
**Why**: No build step needed, minifies at runtime  
**GitHub Examples**:
- https://gist.github.com/4235836 (PHP CSS minifier)
- https://gist.github.com/jtallant/3735275 (PHP minification class)

**Pros**:
- No build step
- Always up-to-date
- Can cache results
- Works well with PHP

**Cons**:
- Runtime overhead (but can be cached)
- Less control over optimization
- May be slower than pre-minified

**Implementation**:
```php
// Could add to asset-helper.php
function minify_css_on_fly($css) {
    // Remove comments
    $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
    // Remove whitespace
    $css = preg_replace('/\s+/', ' ', $css);
    return trim($css);
}
```

## Alternative PurgeCSS Tools

### 1. **UnCSS** ⭐ MORE ACCURATE
**Why**: Executes JavaScript, more accurate than PurgeCSS  
**GitHub**: https://github.com/uncss/uncss  
**Pros**:
- More accurate (executes JS, sees dynamic classes)
- Better for SPAs and dynamic sites
- Can detect classes added by JavaScript

**Cons**:
- Slower (executes JS)
- Requires Node.js
- More complex setup

### 2. **PurifyCSS** ⚠️ LESS ACCURATE
**Why**: Simpler but less accurate  
**GitHub**: https://github.com/purifycss/purifycss  
**Cons**:
- Less accurate than PurgeCSS
- Can have false positives
- Not recommended

### 3. **DropCSS** ⭐ FAST ALTERNATIVE
**Why**: Fast, minimal unused CSS remover  
**GitHub**: https://github.com/leeoniya/dropcss  
**Pros**:
- Very fast
- Minimal tool
- Good for simple sites

**Cons**:
- Less features than PurgeCSS
- May miss some cases

## PHP-Specific Solutions

### 1. **MatthiasMullie/Minify** ⭐ RECOMMENDED
**Why**: PHP library for minification, no Node.js needed  
**GitHub**: https://github.com/matthiasmullie/minify  
**Pros**:
- Pure PHP (no Node.js dependency)
- Can minify CSS and JS
- Can be used on-the-fly or in build
- Well-maintained
- Composer package

**Cons**:
- Less aggressive than Node.js tools
- Slightly larger output

**Implementation**:
```php
// composer require matthiasmullie/minify
use MatthiasMullie\Minify;

$minifier = new Minify\CSS('css/purged/product.css');
$minifier->minify('css/purged/product.min.css');
```

### 2. **PHP Asset Pipeline Libraries**
- **Assetic** (Symfony): https://github.com/kriswallsmith/assetic (deprecated)
- **Laravel Mix**: https://laravel-mix.com/ (requires Laravel)
- **Encore** (Symfony): https://symfony.com/doc/current/frontend.html

## GitHub Repos for PageSpeed Optimization

### 1. **addyosmani/critical** ⭐ CRITICAL CSS
**GitHub**: https://github.com/addyosmani/critical  
**Purpose**: Extract and inline critical CSS  
**Why Useful**: Can automate critical CSS extraction

### 2. **filamentgroup/loadCSS** ⭐ ALREADY USING
**GitHub**: https://github.com/filamentgroup/loadCSS  
**Status**: Already implemented ✅

### 3. **addyosmani/psi** ⭐ PAGESPEED API
**GitHub**: https://github.com/addyosmani/psi  
**Purpose**: Node.js wrapper for PageSpeed Insights API  
**Why Useful**: Can automate PageSpeed testing

### 4. **tdewolff/minify** ⭐ GO-BASED (FAST)
**GitHub**: https://github.com/tdewolff/minify  
**Purpose**: Go-based minifier (very fast)  
**Why Useful**: Extremely fast, can be compiled to binary

## Recommended Solutions

### Immediate Fix (Quick Win)
**Option 1: Switch to cssnano**
```bash
# Update minify-css.sh
npx --yes cssnano-cli css/purged/product.css --output css/purged/product.min.css
```
**Why**: Better compression, more reliable output

**Option 2: Lower asset-helper threshold**
```php
// In asset-helper.php, change:
if ($purgedMinFile && filesize($purgedMinFile) > 5000) {
// To:
if ($purgedMinFile && filesize($purgedMinFile) > 500) {
```
**Why**: Current 861B file is valid, just small

### Medium-Term (Better Build Process)
**Option 3: PostCSS with cssnano**
- Create `postcss.config.js`
- Use cssnano + autoprefixer
- More reliable than current setup
- Future-proof

**Option 4: Gulp.js Build Pipeline**
- Automate all optimizations
- Watch mode for development
- Better than shell scripts for complex workflows

### Long-Term (Production-Ready)
**Option 5: PHP Minification Library**
- Use `matthiasmullie/minify` via Composer
- No Node.js dependency
- Can minify on-the-fly with caching
- Better for PHP projects

## Comparison Table

| Tool | Speed | Compression | Reliability | Setup Complexity | Recommendation |
|------|-------|-------------|------------|------------------|---------------|
| **csso-cli** (current) | Fast | Good | ⚠️ Issues | Low | ❌ Replace |
| **cssnano** | Medium | Excellent | ✅ Reliable | Low | ⭐ **Best** |
| **clean-css** | Very Fast | Good | ✅ Reliable | Low | ✅ Good |
| **PostCSS + cssnano** | Medium | Excellent | ✅ Reliable | Medium | ⭐ **Future** |
| **matthiasmullie/minify** | Fast | Good | ✅ Reliable | Low | ✅ **PHP-native** |
| **Gulp.js** | Fast | Excellent | ✅ Reliable | Medium | ⭐ **Automation** |

## Action Plan

### Phase 1: Quick Fix (Today)
1. **Lower asset-helper threshold** to 500 bytes
   - Allows 861B minified file to be served
   - Quick win, no code changes needed

2. **OR switch to cssnano**
   - Update `minify-css.sh` to use cssnano
   - Better compression, more reliable

### Phase 2: Better Build (This Week)
3. **Implement PostCSS with cssnano**
   - Create `postcss.config.js`
   - Replace csso with cssnano
   - Add autoprefixer for browser compatibility

### Phase 3: Production-Ready (Next Sprint)
4. **Consider PHP minification library**
   - Evaluate `matthiasmullie/minify`
   - Remove Node.js dependency
   - Better for PHP projects

5. **OR implement Gulp.js pipeline**
   - Automate all optimizations
   - Better developer experience
   - More maintainable

## Conclusion

**Best Immediate Solution**: Switch to **cssnano** or lower the threshold  
**Best Long-Term Solution**: **PostCSS with cssnano** or **PHP minification library**

The current setup works but has issues. cssnano is the most reliable modern alternative, and PostCSS provides the most flexibility for future needs.

