# Build Scripts

## Quick Start - Gerar AVIF das Imagens Principais

Para gerar versões AVIF das imagens principais (hero, categorias, about):

```bash
cd build
./generate-avif-main-images.sh
```

Este script converte as imagens mais importantes para AVIF, que é ~30% menor que WebP.
A função `picture_webp()` automaticamente usará AVIF se disponível.

**Requisitos:**
- `libavif` instalado: `brew install libavif` (macOS) ou `sudo apt-get install libavif-bin` (Linux)

---

# Build Scripts for Performance Optimization

This directory contains build scripts for optimizing CSS, JavaScript, and images for production.

## Prerequisites

### For CSS/JS Minification
- Node.js and npm (scripts use npx, no installation needed)

### For Image Optimization
**Required:**
- ImageMagick (for responsive sizes and AVIF conversion)
  ```bash
  brew install imagemagick
  ```

**Optional (for better AVIF conversion):**
- libavif (faster AVIF encoding)
  ```bash
  brew install libavif
  ```

**For Image Compression:**
Choose one option:
- **Option 1 (Recommended)**: Native tools (faster, better compression)
  ```bash
  brew install jpegoptim optipng
  ```
- **Option 2**: Node.js tools (slower, but works without additional installs)
  - Uses imagemin via npx (no installation needed)

**For WebP Conversion:**
```bash
brew install webp
```

## Usage

### Minify CSS

```bash
cd "Site Mimo/public_html"
./build/minify-css.sh
```

This will create minified versions in the `minified/` directory:
- `minified/product.min.css`
- `minified/servicos.min.css`
- `minified/form-main.min.css` (if exists)

### Minify JavaScript

```bash
cd "Site Mimo/public_html"
./build/minify-js.sh
```

This will create minified versions in the `minified/` directory:
- `minified/main.min.js`
- `minified/form-main.min.js` (if exists)

### Optimize Images (Complete Pipeline)

**All-in-one optimization (recommended):**
```bash
cd "Site Mimo/public_html"
./build/optimize-all-images.sh img/
```

This will:
1. Generate responsive sizes (1x, 2x, 3x)
2. Convert to WebP
3. Convert to AVIF
4. Compress original images

**Individual steps:**

**Generate Responsive Sizes:**
```bash
./build/generate-responsive-images.sh img/
```
Creates: `filename.ext` (1x), `filename-2x.ext` (2x), `filename-3x.ext` (3x)

**Convert to WebP:**
```bash
./build/convert-webp.sh 85 img/
```

**Convert to AVIF:**
```bash
./build/convert-avif.sh 80 img/
```

### Compress Images

**Option 1: Using Native Tools (Recommended)**
```bash
cd "Site Mimo/public_html"
# Install tools first (one time)
brew install jpegoptim optipng

# Compress images (quality: 50-100, default: 85)
./build/compress-images-native.sh 85 img/
```

**Option 2: Using Node.js Tools**
```bash
cd "Site Mimo/public_html"
./build/compress-images-simple.sh 85 img/
```

**Parameters:**
- First argument: Quality (50-100, default: 85)
- Second argument: Directory to compress (default: img/)

**Features:**
- Creates automatic backup before compression
- Shows compression statistics
- Preserves original quality while reducing file size
- Skips WebP files (already optimized)

## Production Deployment

After minifying CSS/JS:

1. Update `config.php` to use minified files in production:
   ```php
   define('USE_MINIFIED', true); // Set to true in production
   ```

2. The asset helper (`inc/asset-helper.php`) automatically detects and uses minified files

3. Update `ASSET_VERSION` in `config.php` to force cache refresh

After compressing images:

1. Review the compression results and statistics
2. Test the site to ensure images still look good
3. If needed, restore from backup: `cp -r img_backup_YYYYMMDD_HHMMSS/* img/`
4. Commit the compressed images to version control

## Notes

- Minified files should NOT be committed to version control
- Always test minified files before deploying to production
- Keep original files for development
- The `minified/` directory should be in `.gitignore`
- Image backups are created automatically before compression
- WebP files are skipped (already optimized format)

