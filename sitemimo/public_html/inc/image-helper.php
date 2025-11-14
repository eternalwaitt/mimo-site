<?php
/**
 * Funções Auxiliares de Imagem
 * Fornece utilitários para imagens WebP com fallbacks e srcsets responsivos
 * 
 * Desenvolvido por: Victor Penter
 * Versão: <?php echo APP_VERSION; ?>
 */

/**
 * Generate picture element with WebP and fallback
 * 
 * @param string $src Original image path (jpg/png)
 * @param string $alt Alt text
 * @param string $class CSS classes
 * @param array $attributes Additional HTML attributes
 * @param bool $lazy Use lazy loading (default: true)
 * @return string HTML picture element
 */
function picture_webp($src, $alt = '', $class = '', $attributes = [], $lazy = true) {
    $webpSrc = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $src);
    // Check if WebP exists - try multiple path resolutions
    $rootPath = dirname(__DIR__);
    $webpExists = false;
    
    // Try 1: Check relative to calling script (for files in subdirectories like salao/)
    if (file_exists($webpSrc)) {
        $webpExists = true;
    }
    // Try 2: Check relative to public_html root
    elseif (file_exists($rootPath . '/' . $webpSrc)) {
        $webpExists = true;
    }
    // Try 3: If src starts with ../, resolve it
    elseif (strpos($src, '../') === 0) {
        $resolvedPath = realpath($rootPath . '/' . $webpSrc);
        if ($resolvedPath && file_exists($resolvedPath)) {
            $webpExists = true;
        }
    }
    
    $imgClass = $class ? ' class="' . htmlspecialchars($class) . '"' : '';
    $imgAlt = $alt ? ' alt="' . htmlspecialchars($alt) . '"' : '';
    $imgLoading = $lazy ? ' loading="lazy"' : '';
    
    $additionalAttrs = '';
    foreach ($attributes as $key => $value) {
        $additionalAttrs .= ' ' . htmlspecialchars($key) . '="' . htmlspecialchars($value) . '"';
    }
    
    $html = '<picture>';
    
    if ($webpExists) {
        $html .= '<source srcset="' . htmlspecialchars($webpSrc) . '" type="image/webp">';
    }
    
    $html .= '<img src="' . htmlspecialchars($src) . '"' . $imgClass . $imgAlt . $imgLoading . $additionalAttrs . '>';
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

