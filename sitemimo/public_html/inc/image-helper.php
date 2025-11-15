<?php
/**
 * Funções Auxiliares de Imagem
 * Fornece utilitários para imagens WebP com fallbacks e srcsets responsivos
 * 
 * Desenvolvido por: Victor Penter
 * Versão: <?php echo APP_VERSION; ?>
 */

/**
 * Check if a file exists (tries multiple path resolutions)
 * 
 * @param string $filePath File path to check
 * @param string $rootPath Root path for resolution
 * @return bool True if file exists
 */
function image_file_exists($filePath, $rootPath = null) {
    if ($rootPath === null) {
        $rootPath = dirname(__DIR__);
    }
    
    // Try 1: Check relative to calling script (for files in subdirectories like salao/)
    if (file_exists($filePath)) {
        return true;
    }
    // Try 2: Check relative to public_html root
    if (file_exists($rootPath . '/' . $filePath)) {
        return true;
    }
    // Try 3: If src starts with ../, resolve it
    if (strpos($filePath, '../') === 0) {
        $resolvedPath = realpath($rootPath . '/' . $filePath);
        if ($resolvedPath && file_exists($resolvedPath)) {
            return true;
        }
    }
    
    return false;
}

/**
 * Generate picture element with AVIF, WebP and fallback, with responsive srcset
 * 
 * @param string $src Original image path (jpg/png)
 * @param string $alt Alt text
 * @param string $class CSS classes
 * @param array $attributes Additional HTML attributes (can include 'width' and 'height' for explicit dimensions)
 * @param bool $lazy Use lazy loading (default: true, false for above-the-fold images)
 * @param bool $generateSrcset Generate srcset with 1x, 2x, 3x sizes (default: true)
 * @param string $sizes Sizes attribute for responsive images (default: '100vw')
 * @return string HTML picture element
 */
function picture_webp($src, $alt = '', $class = '', $attributes = [], $lazy = true, $generateSrcset = true, $sizes = '100vw') {
    $rootPath = dirname(__DIR__);
    
    // Generate alternative formats
    $webpSrc = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $src);
    $avifSrc = preg_replace('/\.(jpg|jpeg|png)$/i', '.avif', $src);
    
    // Check which formats exist
    $webpExists = image_file_exists($webpSrc, $rootPath);
    $avifExists = image_file_exists($avifSrc, $rootPath);
    
    // Auto-detect image dimensions if not provided (prevents layout shift)
    if (!isset($attributes['width']) || !isset($attributes['height'])) {
        $imagePath = $rootPath . '/' . ltrim($src, '/');
        if (file_exists($imagePath) && function_exists('getimagesize')) {
            $imageInfo = @getimagesize($imagePath);
            if ($imageInfo !== false) {
                if (!isset($attributes['width'])) {
                    $attributes['width'] = $imageInfo[0];
                }
                if (!isset($attributes['height'])) {
                    $attributes['height'] = $imageInfo[1];
                }
            }
        }
    }
    
    // Generate srcset if requested
    $srcsetAvif = [];
    $srcsetWebp = [];
    $srcsetOriginal = [];
    
    if ($generateSrcset) {
        // Try to find multiple sizes (1x, 2x, 3x)
        // Pattern: filename-1x.ext, filename-2x.ext, filename-3x.ext
        $basePath = preg_replace('/\.(jpg|jpeg|png)$/i', '', $src);
        $ext = preg_replace('/^.*\.(jpg|jpeg|png)$/i', '$1', $src);
            
        for ($multiplier = 1; $multiplier <= 3; $multiplier++) {
            $sizePath = $basePath . ($multiplier > 1 ? '-' . $multiplier . 'x' : '') . '.' . $ext;
            $sizeWebp = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $sizePath);
            $sizeAvif = preg_replace('/\.(jpg|jpeg|png)$/i', '.avif', $sizePath);
            
            // Check if this size exists
            if (image_file_exists($sizePath, $rootPath)) {
                $descriptor = $multiplier . 'x';
                $srcsetOriginal[] = $sizePath . ' ' . $descriptor;
                
                if (image_file_exists($sizeWebp, $rootPath)) {
                    $srcsetWebp[] = $sizeWebp . ' ' . $descriptor;
                }
                
                if (image_file_exists($sizeAvif, $rootPath)) {
                    $srcsetAvif[] = $sizeAvif . ' ' . $descriptor;
                }
            }
        }
        
        // If no multiple sizes found, use original with 1x descriptor
        if (empty($srcsetOriginal)) {
            $srcsetOriginal[] = $src . ' 1x';
            if ($webpExists) {
                $srcsetWebp[] = $webpSrc . ' 1x';
            }
            if ($avifExists) {
                $srcsetAvif[] = $avifSrc . ' 1x';
            }
        }
    }
    
    $imgClass = $class ? ' class="' . htmlspecialchars($class) . '"' : '';
    $imgAlt = $alt ? ' alt="' . htmlspecialchars($alt) . '"' : '';
    $imgLoading = $lazy ? ' loading="lazy"' : '';
    
    // Extract width and height for explicit dimensions (prevents layout shift)
    $width = isset($attributes['width']) ? $attributes['width'] : '';
    $height = isset($attributes['height']) ? $attributes['height'] : '';
    $widthAttr = $width ? ' width="' . htmlspecialchars($width) . '"' : '';
    $heightAttr = $height ? ' height="' . htmlspecialchars($height) . '"' : '';
    
    // Build additional attributes (excluding width/height as they're handled separately)
    $additionalAttrs = '';
    foreach ($attributes as $key => $value) {
        if ($key !== 'width' && $key !== 'height') {
            $additionalAttrs .= ' ' . htmlspecialchars($key) . '="' . htmlspecialchars($value) . '"';
        }
    }
    
    $html = '<picture>';
    
    // AVIF sources (best compression, highest priority)
    if (!empty($srcsetAvif)) {
        $html .= '<source srcset="' . htmlspecialchars(implode(', ', $srcsetAvif)) . '" type="image/avif"' . ($sizes ? ' sizes="' . htmlspecialchars($sizes) . '"' : '') . '>';
    } elseif ($avifExists) {
        $html .= '<source srcset="' . htmlspecialchars($avifSrc) . '" type="image/avif">';
    }
    
    // WebP sources (fallback for AVIF)
    if (!empty($srcsetWebp)) {
        $html .= '<source srcset="' . htmlspecialchars(implode(', ', $srcsetWebp)) . '" type="image/webp"' . ($sizes ? ' sizes="' . htmlspecialchars($sizes) . '"' : '') . '>';
    } elseif ($webpExists) {
        $html .= '<source srcset="' . htmlspecialchars($webpSrc) . '" type="image/webp">';
    }
    
    // Original format fallback
    $imgSrc = $src;
    $imgSrcset = '';
    if (!empty($srcsetOriginal)) {
        $imgSrcset = ' srcset="' . htmlspecialchars(implode(', ', $srcsetOriginal)) . '"';
        if ($sizes) {
            $imgSrcset .= ' sizes="' . htmlspecialchars($sizes) . '"';
        }
    }
    
    $html .= '<img src="' . htmlspecialchars($imgSrc) . '"' . $imgSrcset . $imgClass . $imgAlt . $imgLoading . $widthAttr . $heightAttr . $additionalAttrs . '>';
    $html .= '</picture>';
    
    return $html;
}

/**
 * Generate responsive image with srcset
 * 
 * @param string $basePath Base image path without extension
 * @param string $ext Original extension (jpg/png)
 * @param string $alt Alt text
 * @param string $class CSS classes
 * @param array $sizes Array of sizes in format ['1x' => 'path', '2x' => 'path'] or widths
 * @param bool $lazy Use lazy loading
 * @return string HTML img element with srcset
 */
function responsive_image($basePath, $ext, $alt = '', $class = '', $sizes = [], $lazy = true) {
    $webpBase = preg_replace('/\.(jpg|jpeg|png)$/i', '', $basePath);
    $originalBase = preg_replace('/\.(jpg|jpeg|png)$/i', '', $basePath);
    
    $imgClass = $class ? ' class="' . htmlspecialchars($class) . '"' : '';
    $imgAlt = $alt ? ' alt="' . htmlspecialchars($alt) . '"' : '';
    $imgLoading = $lazy ? ' loading="lazy"' : '';
    
    $srcsetWebp = [];
    $srcsetOriginal = [];
    
    // Build srcsets
    foreach ($sizes as $descriptor => $path) {
        $webpPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $path);
        if (file_exists(__DIR__ . '/../' . $webpPath)) {
            $srcsetWebp[] = $webpPath . ' ' . $descriptor;
        }
        $srcsetOriginal[] = $path . ' ' . $descriptor;
    }
    
    $html = '<picture>';
    
    if (!empty($srcsetWebp)) {
        $html .= '<source srcset="' . htmlspecialchars(implode(', ', $srcsetWebp)) . '" type="image/webp">';
    }
    
    $html .= '<img src="' . htmlspecialchars($originalBase . '.' . $ext) . '"';
    if (!empty($srcsetOriginal)) {
        $html .= ' srcset="' . htmlspecialchars(implode(', ', $srcsetOriginal)) . '"';
    }
    $html .= $imgClass . $imgAlt . $imgLoading . '>';
    $html .= '</picture>';
    
    return $html;
}

