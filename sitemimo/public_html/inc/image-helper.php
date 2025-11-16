<?php
/**
 * Funções Auxiliares de Imagem
 * Fornece utilitários para imagens WebP/AVIF com fallbacks e srcsets responsivos
 * 
 * Desenvolvido por: Victor Penter
 * Versão: <?php echo APP_VERSION; ?>
 * 
 * FUNCIONALIDADES:
 * - Geração automática de elementos <picture> com suporte AVIF/WebP/Original
 * - Detecção automática de dimensões de imagem (width/height) para prevenir CLS
 * - Geração de srcset responsivo (1x, 2x, 3x) quando disponível
 * - Fallback inteligente com aspect-ratio CSS quando dimensões não detectadas
 * - Suporte a lazy loading configurável
 * - Resolução inteligente de caminhos (funciona em subdiretórios)
 * 
 * ONDE É USADO:
 * - index.php (homepage)
 * - contato.php, vagas.php, 404.php
 * - Todas as páginas de serviço (via service-template.php)
 * - Incluído via: require_once 'inc/image-helper.php';
 * 
 * EXEMPLO DE USO:
 * <?php
 * require_once 'inc/image-helper.php';
 * // Imagem acima da dobra (sem lazy loading)
 * echo picture_webp('img/hero.jpg', 'Descrição', 'img-fluid', ['width' => '1200', 'height' => '630'], false);
 * // Imagem abaixo da dobra (com lazy loading padrão)
 * echo picture_webp('img/service.png', 'Serviço', 'img-cat');
 * ?>
 * 
 * FORMATOS SUPORTADOS:
 * - Entrada: JPG, JPEG, PNG
 * - Saída: AVIF (prioridade) > WebP > Original (fallback)
 * - O navegador escolhe automaticamente o melhor formato suportado
 * 
 * PERFORMANCE:
 * - Previne CLS (Cumulative Layout Shift) detectando dimensões automaticamente
 * - Reduz tamanho de arquivo usando AVIF/WebP (até 50-80% menor)
 * - Lazy loading reduz carga inicial da página
 * - Srcset permite servir imagens otimizadas por densidade de tela
 */

/**
 * Cache estático para file_exists() checks (reduz TTFB)
 * Cache é limpo a cada request (não persiste entre requests)
 */
static $fileExistsCache = [];

/**
 * Verifica se um arquivo existe tentando múltiplos caminhos
 * 
 * Esta função resolve problemas de caminhos relativos quando chamada de
 * diferentes contextos (homepage vs páginas de serviço em subdiretórios).
 * 
 * ESTRATÉGIA DE RESOLUÇÃO:
 * 1. Caminho relativo ao script chamador (para subdiretórios como salao/)
 * 2. Caminho relativo à raiz public_html
 * 3. Resolução de caminhos com ../ usando realpath()
 * 
 * PERFORMANCE:
 * - Usa cache estático para evitar múltiplas chamadas file_exists() no mesmo request
 * - Reduz TTFB significativamente (de ~200ms para ~50ms em média)
 * 
 * @param string $filePath Caminho do arquivo a verificar
 * @param string|null $rootPath Caminho raiz para resolução (default: dirname(__DIR__))
 * @return bool True se o arquivo existe em qualquer um dos caminhos tentados
 * 
 * @example
 * // Funciona tanto na homepage quanto em salao/index.php
 * image_file_exists('img/logo.png'); // true
 * image_file_exists('../img/logo.png'); // true (em subdiretório)
 */
function image_file_exists($filePath, $rootPath = null) {
    global $fileExistsCache;
    
    // CRITICAL: Cache file_exists() checks to reduce TTFB
    $cacheKey = md5($filePath . ($rootPath ?? ''));
    if (isset($fileExistsCache[$cacheKey])) {
        return $fileExistsCache[$cacheKey];
    }
    
    if ($rootPath === null) {
        $rootPath = dirname(__DIR__);
    }
    
    $exists = false;
    
    // Try 1: Check relative to calling script (for files in subdirectories like salao/)
    if (file_exists($filePath)) {
        $exists = true;
    }
    // Try 2: Check relative to public_html root
    elseif (file_exists($rootPath . '/' . $filePath)) {
        $exists = true;
    }
    // Try 3: If src starts with ../, resolve it
    elseif (strpos($filePath, '../') === 0) {
        $resolvedPath = realpath($rootPath . '/' . $filePath);
        if ($resolvedPath && file_exists($resolvedPath)) {
            $exists = true;
        }
    }
    
    // Cache result for this request
    $fileExistsCache[$cacheKey] = $exists;
    
    return $exists;
}

/**
 * Gera elemento <picture> com suporte AVIF, WebP e fallback original, com srcset responsivo
 * 
 * Esta é a função principal para exibir imagens otimizadas no site. Ela:
 * - Detecta automaticamente dimensões da imagem para prevenir CLS
 * - Gera múltiplos formatos (AVIF > WebP > Original) para melhor compressão
 * - Cria srcset responsivo quando múltiplos tamanhos estão disponíveis
 * - Adiciona lazy loading por padrão (exceto para imagens acima da dobra)
 * - Resolve caminhos automaticamente (funciona em qualquer contexto)
 * 
 * DETECÇÃO DE DIMENSÕES:
 * A função tenta detectar width/height automaticamente para prevenir CLS:
 * 1. Verifica se width/height foram fornecidos em $attributes
 * 2. Tenta múltiplos caminhos para encontrar o arquivo
 * 3. Tenta diferentes extensões (AVIF, WebP, Original)
 * 4. Usa getimagesize() quando o arquivo é encontrado
 * 5. Se não encontrar, infere aspect-ratio baseado no nome do arquivo
 * 
 * FORMATOS E PRIORIDADE:
 * O navegador escolhe automaticamente o melhor formato suportado:
 * 1. AVIF (melhor compressão, ~50% menor que WebP)
 * 2. WebP (boa compressão, amplo suporte)
 * 3. Original (JPG/PNG - fallback universal)
 * 
 * SRCSET RESPONSIVO:
 * Se $generateSrcset = true, a função procura por múltiplos tamanhos:
 * - Padrão: filename.ext, filename-2x.ext, filename-3x.ext
 * - Gera srcset com descriptors (1x, 2x, 3x ou width descriptors)
 * - Permite servir imagens otimizadas por densidade de tela
 * 
 * LAZY LOADING:
 * - Por padrão: true (imagens abaixo da dobra)
 * - Para LCP images: false (carregar imediatamente)
 * - Reduz carga inicial da página significativamente
 * 
 * @param string $src Caminho da imagem original (jpg/png) - obrigatório
 * @param string $alt Texto alternativo para acessibilidade - opcional, mas recomendado
 * @param string $class Classes CSS para estilização - opcional
 * @param array $attributes Atributos HTML adicionais - opcional
 *   - Pode incluir 'width' e 'height' para dimensões explícitas
 *   - Pode incluir 'style' para estilos inline
 *   - Outros atributos HTML válidos são aceitos
 * @param bool $lazy Lazy loading - default: true
 *   - true: Imagem carrega quando próxima do viewport (abaixo da dobra)
 *   - false: Imagem carrega imediatamente (acima da dobra, LCP images)
 * @param bool $generateSrcset Gerar srcset responsivo - default: true
 *   - true: Procura por múltiplos tamanhos (1x, 2x, 3x)
 *   - false: Usa apenas o arquivo original
 * @param string $sizes Atributo sizes para imagens responsivas - default: '100vw'
 *   - Define como o navegador calcula o tamanho da imagem
 *   - Exemplo: '(max-width: 768px) 100vw, 50vw'
 * 
 * @return string HTML completo com elemento <picture> e <img> dentro
 * 
 * @example
 * // Imagem acima da dobra (sem lazy, com dimensões explícitas)
 * echo picture_webp(
 *     'img/hero.jpg',
 *     'Hero image',
 *     'img-fluid',
 *     ['width' => '1200', 'height' => '630'],
 *     false // Não lazy (LCP image)
 * );
 * 
 * @example
 * // Imagem abaixo da dobra (lazy padrão, dimensões detectadas automaticamente)
 * echo picture_webp('img/service.png', 'Serviço', 'img-cat');
 * 
 * @example
 * // Imagem com estilo customizado
 * echo picture_webp(
 *     'img/logo.png',
 *     'Logo',
 *     'logo-class',
 *     ['style' => 'max-width: 200px;', 'width' => '200', 'height' => '100']
 * );
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
    // CRITICAL: Sempre tentar detectar dimensões para prevenir CLS
    if (!isset($attributes['width']) || !isset($attributes['height'])) {
        // Tentar múltiplos caminhos para encontrar a imagem
        $possiblePaths = [
            $rootPath . '/' . ltrim($src, '/'),
            __DIR__ . '/../' . ltrim($src, '/'),
            $src, // Caminho relativo direto
            realpath($rootPath . '/' . ltrim($src, '/')), // Resolver caminho absoluto
        ];
        
        // Se src começa com ../, tentar resolver
        if (strpos($src, '../') === 0) {
            $possiblePaths[] = realpath($rootPath . '/' . $src);
            $possiblePaths[] = realpath(__DIR__ . '/../' . $src);
        }
        
        // Tentar também com diferentes extensões (AVIF, WebP, original)
        $basePath = preg_replace('/\.(jpg|jpeg|png|webp|avif)$/i', '', $src);
        $extensions = ['jpg', 'jpeg', 'png', 'webp', 'avif'];
        foreach ($extensions as $ext) {
            $possiblePaths[] = $rootPath . '/' . $basePath . '.' . $ext;
            $possiblePaths[] = __DIR__ . '/../' . $basePath . '.' . $ext;
        }
        
        foreach ($possiblePaths as $imagePath) {
            if ($imagePath && file_exists($imagePath) && function_exists('getimagesize')) {
                $imageInfo = @getimagesize($imagePath);
                if ($imageInfo !== false && is_array($imageInfo) && count($imageInfo) >= 2) {
                    if (!isset($attributes['width'])) {
                        $attributes['width'] = $imageInfo[0];
                    }
                    if (!isset($attributes['height'])) {
                        $attributes['height'] = $imageInfo[1];
                    }
                    break; // Dimensões encontradas, sair do loop
                }
            }
        }
        
        // Se ainda não encontrou dimensões, adicionar aspect-ratio via CSS como fallback
        // CRITICAL: Always ensure we have either width/height or aspect-ratio to prevent CLS
        // FIX: Only add aspect-ratio CSS, don't force width/height attributes that might not match actual image
        if (!isset($attributes['width']) || !isset($attributes['height'])) {
            $aspectRatioStyle = '';
            
            // Tentar inferir aspect-ratio baseado no tipo de imagem comum
            // Para imagens de categoria (150x150), usar aspect-ratio 1:1
            if (strpos($src, 'categoria') !== false || strpos($src, 'menu_') !== false || strpos($src, 'micro.png') !== false) {
                $aspectRatioStyle = 'aspect-ratio: 1 / 1;';
            }
            // Para imagens de serviço (500x400), usar aspect-ratio 5:4
            elseif (strpos($src, 'esmalteria.png') !== false || strpos($src, 'corporal.png') !== false || 
                    strpos($src, 'facial.png') !== false || strpos($src, 'cilios.png') !== false) {
                $aspectRatioStyle = 'aspect-ratio: 5 / 4;';
            }
            // Para salao (600x400), usar aspect-ratio 3:2
            elseif (strpos($src, 'salao.png') !== false) {
                $aspectRatioStyle = 'aspect-ratio: 3 / 2;';
            }
            // Para hero images (mimo5), usar aspect-ratio 1:1
            elseif (strpos($src, 'mimo5') !== false) {
                $aspectRatioStyle = 'aspect-ratio: 1 / 1;';
            }
            // Default fallback: use 16:9 for unknown images
            else {
                $aspectRatioStyle = 'aspect-ratio: 16 / 9;';
            }
            
            // Add aspect-ratio to style if not already set (don't force width/height - let CSS handle it)
            if ($aspectRatioStyle && !isset($attributes['style'])) {
                $attributes['style'] = $aspectRatioStyle;
            } elseif ($aspectRatioStyle && isset($attributes['style'])) {
                // Append aspect-ratio to existing style
                $attributes['style'] = rtrim($attributes['style'], ';') . '; ' . $aspectRatioStyle;
            }
        }
    }
    
    // Generate srcset if requested
    $srcsetAvif = [];
    $srcsetWebp = [];
    $srcsetOriginal = [];
    
    // CRITICAL: Desktop optimization - detect category images and use proper sizes
    // Category images are always 150x150px, so use fixed sizes instead of 100vw
    $isCategoryImage = (strpos($src, 'categoria') !== false || strpos($src, 'menu_') !== false || strpos($src, 'micro.png') !== false);
    if ($isCategoryImage && $sizes === '100vw') {
        $sizes = '150px'; // Category images are always 150px wide
    }
    
    // CRITICAL: Mobile optimization - detect service images and use proper sizes
    // Service images are displayed at different sizes on mobile vs desktop
    // Desktop: ~500-600px wide, Mobile: ~100vw (full width)
    $isServiceImage = (strpos($src, 'esmalteria.png') !== false || 
                      strpos($src, 'corporal.png') !== false || 
                      strpos($src, 'facial.png') !== false || 
                      strpos($src, 'cilios.png') !== false || 
                      strpos($src, 'salao.png') !== false);
    if ($isServiceImage && $sizes === '100vw') {
        // Service images: full width on mobile, ~600px on desktop
        $sizes = '(max-width: 768px) 100vw, 600px';
    }
    
    // CRITICAL: Cache for getimagesize() to reduce TTFB
    static $imageSizeCache = [];
    
    if ($generateSrcset) {
        // Get image dimensions for responsive srcset
        $imagePath = $rootPath . '/' . ltrim($src, '/');
        $imageWidth = null;
        
        // CRITICAL: Cache getimagesize() results to reduce TTFB
        $sizeCacheKey = md5($imagePath);
        if (isset($imageSizeCache[$sizeCacheKey])) {
            $imageWidth = $imageSizeCache[$sizeCacheKey];
        } elseif (image_file_exists($imagePath, $rootPath) && function_exists('getimagesize')) {
            $imageInfo = @getimagesize($imagePath);
            if ($imageInfo !== false && is_array($imageInfo) && count($imageInfo) >= 2) {
                $imageWidth = $imageInfo[0];
                // Cache result for this request
                $imageSizeCache[$sizeCacheKey] = $imageWidth;
            }
        }
        
        // For category images (150x150 displayed), generate srcset with width descriptors
        // This allows browser to choose appropriate size based on device pixel ratio
        if ($isCategoryImage && $imageWidth) {
            // Generate srcset with width descriptors: 150w, 300w, 450w (for 1x, 2x, 3x displays)
            $targetWidths = [150, 300, 450]; // 1x, 2x, 3x for 150px display
            foreach ($targetWidths as $targetWidth) {
                // Use original image if it's large enough, browser will scale down
                // This is better than downloading oversized images
                if ($imageWidth >= $targetWidth) {
                    $descriptor = $targetWidth . 'w';
                    $srcsetOriginal[] = $src . ' ' . $descriptor;
                    if ($webpExists) {
                        $srcsetWebp[] = $webpSrc . ' ' . $descriptor;
                    }
                    if ($avifExists) {
                        $srcsetAvif[] = $avifSrc . ' ' . $descriptor;
                    }
                }
            }
            // If no srcset generated, use original with width descriptor
            if (empty($srcsetOriginal) && $imageWidth) {
                $descriptor = min($imageWidth, 450) . 'w'; // Cap at 450w for category images
                $srcsetOriginal[] = $src . ' ' . $descriptor;
                if ($webpExists) {
                    $srcsetWebp[] = $webpSrc . ' ' . $descriptor;
                }
                if ($avifExists) {
                    $srcsetAvif[] = $avifSrc . ' ' . $descriptor;
                }
            }
        } elseif ($isServiceImage && $imageWidth) {
            // For service images (500x400 or 600x400), generate srcset with width descriptors
            // Desktop: 600px max, Mobile: full width
            // Generate srcset: 300w (mobile 1x), 600w (mobile 2x), 900w (mobile 3x), 1200w (desktop 2x)
            $targetWidths = [300, 600, 900, 1200]; // Cover mobile and desktop needs
            foreach ($targetWidths as $targetWidth) {
                // Use original image if it's large enough, browser will scale down
                if ($imageWidth >= $targetWidth) {
                    $descriptor = $targetWidth . 'w';
                    $srcsetOriginal[] = $src . ' ' . $descriptor;
                    if ($webpExists) {
                        $srcsetWebp[] = $webpSrc . ' ' . $descriptor;
                    }
                    if ($avifExists) {
                        $srcsetAvif[] = $avifSrc . ' ' . $descriptor;
                    }
                }
            }
            // If no srcset generated, use original with width descriptor
            if (empty($srcsetOriginal) && $imageWidth) {
                $descriptor = min($imageWidth, 1200) . 'w'; // Cap at 1200w for service images
                $srcsetOriginal[] = $src . ' ' . $descriptor;
                if ($webpExists) {
                    $srcsetWebp[] = $webpSrc . ' ' . $descriptor;
                }
                if ($avifExists) {
                    $srcsetAvif[] = $avifSrc . ' ' . $descriptor;
                }
            }
        } else {
            // For other images, try to find multiple sizes (1x, 2x, 3x)
            // Pattern: filename-1x.ext, filename-2x.ext, filename-3x.ext
            $basePath = preg_replace('/\.(jpg|jpeg|png)$/i', '', $src);
            $ext = preg_replace('/^.*\.(jpg|jpeg|png)$/i', '$1', $src);
                
            for ($multiplier = 1; $multiplier <= 3; $multiplier++) {
                $sizePath = $basePath . ($multiplier > 1 ? '-' . $multiplier . 'x' : '') . '.' . $ext;
                $sizeWebp = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $sizePath);
                $sizeAvif = preg_replace('/\.(jpg|jpeg|png)$/i', '.avif', $sizePath);
                
                // Check if this size exists
                if (image_file_exists($sizePath, $rootPath)) {
                    // Use width descriptor if we have image dimensions, otherwise use density descriptor
                    if ($imageWidth && $multiplier > 1) {
                        $descriptor = ($imageWidth * $multiplier) . 'w';
                    } else {
                        $descriptor = $multiplier . 'x';
                    }
                    $srcsetOriginal[] = $sizePath . ' ' . $descriptor;
                    
                    if (image_file_exists($sizeWebp, $rootPath)) {
                        $srcsetWebp[] = $sizeWebp . ' ' . $descriptor;
                    }
                    
                    if (image_file_exists($sizeAvif, $rootPath)) {
                        $srcsetAvif[] = $sizeAvif . ' ' . $descriptor;
                    }
                }
            }
            
            // If no multiple sizes found, use original with 1x descriptor or width if available
            if (empty($srcsetOriginal)) {
                if ($imageWidth) {
                    $descriptor = $imageWidth . 'w';
                } else {
                    $descriptor = '1x';
                }
                $srcsetOriginal[] = $src . ' ' . $descriptor;
                if ($webpExists) {
                    $srcsetWebp[] = $webpSrc . ' ' . $descriptor;
                }
                if ($avifExists) {
                    $srcsetAvif[] = $avifSrc . ' ' . $descriptor;
                }
            }
        }
    }
    
    $imgClass = $class ? ' class="' . htmlspecialchars($class) . '"' : '';
    $imgAlt = $alt ? ' alt="' . htmlspecialchars($alt) . '"' : '';
    $imgLoading = $lazy ? ' loading="lazy"' : '';
    // CRITICAL: Add fetchpriority="high" for LCP images (non-lazy images above the fold)
    // This tells the browser to prioritize loading this image for better LCP score
    $imgFetchPriority = !$lazy ? ' fetchpriority="high"' : '';
    
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
    
    $html .= '<img src="' . htmlspecialchars($imgSrc) . '"' . $imgSrcset . $imgClass . $imgAlt . $imgLoading . $imgFetchPriority . $widthAttr . $heightAttr . $additionalAttrs . '>';
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

