<?php
/**
 * Asset Helper
 * Funções auxiliares para carregar assets (CSS/JS) com suporte a minificação
 * 
 * Desenvolvido por: Victor Penter
 * Versão: 1.1.0
 * 
 * FUNCIONALIDADES:
 * - Carregamento automático de versões minificadas em produção
 * - Detecção automática de subdiretórios (páginas de serviço)
 * - Cache busting via ASSET_VERSION
 * - Suporte a atributos customizados nas tags HTML
 * 
 * ONDE É USADO:
 * - Todas as páginas PHP (index.php, contato.php, vagas.php, 404.php)
 * - Páginas de serviço via service-template.php
 * - Incluído via: require_once 'inc/asset-helper.php';
 * 
 * EXEMPLO DE USO:
 * <?php
 * require_once 'inc/asset-helper.php';
 * echo css_tag('product.css'); // Gera: <link rel="stylesheet" href="product.css?v=20250114">
 * echo js_tag('main.js', ['defer' => true]); // Gera: <script src="main.js?v=20250114" defer></script>
 * ?>
 * 
 * CONFIGURAÇÃO:
 * - USE_MINIFIED: define('USE_MINIFIED', true) em config.php
 * - ASSET_VERSION: define('ASSET_VERSION', '20250114') em config.php
 * - Arquivos minificados devem estar em: minified/
 */

/**
 * Retorna o caminho do asset CSS, considerando minificação
 * 
 * @param string $filePath Caminho relativo do arquivo CSS (ex: 'product.css')
 * @param bool $addVersion Se deve adicionar versão para cache busting
 * @return string Caminho completo do asset
 */
function get_css_asset($filePath, $addVersion = true) {
    $basePath = $filePath;
    $version = '';
    
    if ($addVersion && defined('ASSET_VERSION')) {
        $version = '?v=' . ASSET_VERSION;
    }
    
    // Detectar se estamos em subdiretório (páginas de serviço)
    // Verificar se o arquivo atual está em um subdiretório
    $scriptPath = $_SERVER['SCRIPT_NAME'] ?? $_SERVER['PHP_SELF'] ?? '';
    $scriptDir = dirname($scriptPath);
    // Se o diretório não é /, . ou vazio, estamos em subdiretório
    $isSubdir = ($scriptDir !== '/' && $scriptDir !== '\\' && $scriptDir !== '.' && $scriptDir !== '' && $scriptDir !== '/index.php');
    $prefix = $isSubdir ? '../' : '';
    
    // Prioridade: 1) Purged + Minified, 2) Minified, 3) Purged, 4) Original
    if (defined('USE_MINIFIED') && USE_MINIFIED) {
        $minPath = str_replace('.css', '.min.css', $filePath);
        $minFileName = basename($minPath);
        $purgedPath = __DIR__ . '/../css/purged/' . basename($filePath);
        $purgedMinPath = __DIR__ . '/../css/purged/' . $minFileName;
        $minFullPath = __DIR__ . '/../minified/' . $minFileName;
        
        // Para arquivos em subdiretórios
        if (strpos($filePath, '/') !== false) {
            $dir = dirname($filePath);
            $name = basename($filePath, '.css');
            $minName = str_replace('/', '-', $dir) . '-' . $name . '.min.css';
            $minFullPath = __DIR__ . '/../minified/' . $minName;
        }
        
        // 1. Tentar purged + minified (melhor)
        if (file_exists($purgedMinPath)) {
            $basePath = $prefix . 'css/purged/' . $minFileName;
        }
        // 2. Tentar apenas minified
        elseif (file_exists($minFullPath)) {
            if (strpos($filePath, '/') !== false) {
                $dir = dirname($filePath);
                $name = basename($filePath, '.css');
                $minName = str_replace('/', '-', $dir) . '-' . $name . '.min.css';
                if (file_exists(__DIR__ . '/../minified/' . $minName)) {
                    $basePath = $prefix . 'minified/' . $minName;
                } else {
                    $basePath = $prefix . $filePath;
                }
            } else {
                $basePath = $prefix . 'minified/' . $minFileName;
            }
        }
        // 3. Tentar apenas purged
        elseif (file_exists($purgedPath)) {
            $basePath = $prefix . 'css/purged/' . basename($filePath);
        }
        // 4. Fallback para original
        else {
            $basePath = $prefix . $filePath;
        }
    } else {
        // Sem minificação, tentar purged se existir
        $purgedPath = __DIR__ . '/../css/purged/' . basename($filePath);
        if (file_exists($purgedPath)) {
            $basePath = $prefix . 'css/purged/' . basename($filePath);
        } else {
            $basePath = $prefix . $filePath;
        }
    }
    
    return $basePath . $version;
}

/**
 * Retorna o caminho do asset JS, considerando minificação
 * 
 * @param string $filePath Caminho relativo do arquivo JS (ex: 'main.js')
 * @param bool $addVersion Se deve adicionar versão para cache busting
 * @return string Caminho completo do asset
 */
function get_js_asset($filePath, $addVersion = true) {
    $basePath = $filePath;
    $version = '';
    
    if ($addVersion && defined('ASSET_VERSION')) {
        $version = '?v=' . ASSET_VERSION;
    }
    
    // Detectar se estamos em subdiretório (páginas de serviço)
    // Verificar se o arquivo atual está em um subdiretório
    $scriptPath = $_SERVER['SCRIPT_NAME'] ?? $_SERVER['PHP_SELF'] ?? '';
    $scriptDir = dirname($scriptPath);
    // Se o diretório não é /, . ou vazio, estamos em subdiretório
    $isSubdir = ($scriptDir !== '/' && $scriptDir !== '\\' && $scriptDir !== '.' && $scriptDir !== '' && $scriptDir !== '/index.php');
    $prefix = $isSubdir ? '../' : '';
    
    // Se minificação está ativa, tentar usar versão minificada
    if (defined('USE_MINIFIED') && USE_MINIFIED) {
        $minPath = str_replace('.js', '.min.js', $filePath);
        $minFileName = basename($minPath);
        $minFullPath = __DIR__ . '/../minified/' . $minFileName;
        
        // Se arquivo minificado existe, usar ele
        if (file_exists($minFullPath)) {
            // Manter estrutura de diretório se houver (ex: form/main.js -> minified/form-main.min.js)
            if (strpos($filePath, '/') !== false) {
                // Para arquivos em subdiretórios, usar nome sem subdiretório
                $dir = dirname($filePath);
                $name = basename($filePath, '.js');
                $minName = str_replace('/', '-', $dir) . '-' . $name . '.min.js';
                $minFullPath = __DIR__ . '/../minified/' . $minName;
                if (file_exists($minFullPath)) {
                    $basePath = $prefix . 'minified/' . $minName;
                } else {
                    $basePath = $prefix . 'minified/' . str_replace('/', '-', $minFileName);
                }
            } else {
                $basePath = $prefix . 'minified/' . $minFileName;
            }
        } else {
            // Se não encontrou minificado, usar original com prefixo se necessário
            $basePath = $prefix . $filePath;
        }
    } else {
        // Sem minificação, usar original com prefixo se necessário
        $basePath = $prefix . $filePath;
    }
    
    return $basePath . $version;
}

/**
 * Gera tag <link> para CSS
 * 
 * @param string $filePath Caminho do arquivo CSS
 * @param array $attributes Atributos adicionais (ex: ['media' => 'print'])
 * @return string Tag HTML completa
 */
function css_tag($filePath, $attributes = []) {
    $href = get_css_asset($filePath);
    $attrs = '';
    
    foreach ($attributes as $key => $value) {
        $attrs .= ' ' . htmlspecialchars($key) . '="' . htmlspecialchars($value) . '"';
    }
    
    return '<link rel="stylesheet" href="' . htmlspecialchars($href) . '"' . $attrs . '>';
}

/**
 * Gera tag <script> para JS
 * 
 * @param string $filePath Caminho do arquivo JS
 * @param array $attributes Atributos adicionais (ex: ['defer' => true])
 * @return string Tag HTML completa
 */
function js_tag($filePath, $attributes = []) {
    $src = get_js_asset($filePath);
    $attrs = '';
    
    foreach ($attributes as $key => $value) {
        if ($value === true) {
            $attrs .= ' ' . htmlspecialchars($key);
        } else {
            $attrs .= ' ' . htmlspecialchars($key) . '="' . htmlspecialchars($value) . '"';
        }
    }
    
    return '<script src="' . htmlspecialchars($src) . '"' . $attrs . '></script>';
}

