<?php
/**
 * Cache Headers
 * Implementa cache headers apropriados para diferentes tipos de assets
 * 
 * Desenvolvido por: Victor Penter
 * Versão: 1.0.0
 */

/**
 * Define cache headers baseado no tipo de arquivo
 * 
 * @param string $filePath Caminho do arquivo sendo servido (opcional)
 * @param string $contentType Tipo MIME do conteúdo (opcional)
 */
function set_cache_headers($filePath = null, $contentType = null) {
    // Se headers já foram enviados, não fazer nada
    if (headers_sent()) {
        return;
    }

    // Detectar tipo de arquivo se não fornecido
    if (!$contentType && $filePath) {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $contentType = get_mime_type($extension);
    }

    // Se ainda não temos contentType, tentar detectar do header atual
    if (!$contentType) {
        $contentType = get_content_type_from_request();
    }

    // Configurações de cache por tipo
    $cacheConfig = get_cache_config($contentType, $filePath);
    
    // Aplicar headers
    if ($cacheConfig['max_age'] > 0) {
        // Cache público com max-age
        header('Cache-Control: public, max-age=' . $cacheConfig['max_age'] . ', immutable');
        
        // Expires header (compatibilidade com navegadores antigos)
        $expires = gmdate('D, d M Y H:i:s', time() + $cacheConfig['max_age']) . ' GMT';
        header('Expires: ' . $expires);
        
        // Pragma para compatibilidade HTTP/1.0
        header('Pragma: public');
    } else {
        // No cache para HTML e conteúdo dinâmico
        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() - 3600) . ' GMT');
        header('Pragma: no-cache');
    }

    // ETag para validação de cache (se arquivo existe)
    if ($filePath && file_exists($filePath)) {
        $etag = generate_etag($filePath);
        header('ETag: ' . $etag);
        
        // Verificar If-None-Match para retornar 304
        if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] === $etag) {
            http_response_code(304);
            exit;
        }
    }

    // Last-Modified (se arquivo existe)
    if ($filePath && file_exists($filePath)) {
        $lastModified = gmdate('D, d M Y H:i:s', filemtime($filePath)) . ' GMT';
        header('Last-Modified: ' . $lastModified);
        
        // Verificar If-Modified-Since para retornar 304
        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
            $ifModifiedSince = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
            $fileModified = filemtime($filePath);
            
            if ($ifModifiedSince >= $fileModified) {
                http_response_code(304);
                exit;
            }
        }
    }

    // Vary header para compressão
    if (in_array($contentType, ['text/css', 'application/javascript', 'text/javascript'])) {
        header('Vary: Accept-Encoding');
    }
}

/**
 * Obtém configuração de cache baseado no tipo de conteúdo
 * 
 * @param string $contentType Tipo MIME
 * @param string|null $filePath Caminho do arquivo (para verificar versionamento)
 * @return array Configuração de cache
 */
function get_cache_config($contentType, $filePath = null) {
    // HTML - cache curto (5 minutos) para permitir atualizações rápidas
    if (strpos($contentType, 'text/html') !== false) {
        return ['max_age' => 300]; // 5 minutos
    }

    // CSS e JS versionados - cache longo (1 ano)
    if (in_array($contentType, ['text/css', 'application/javascript', 'text/javascript'])) {
        // Se tem versionamento na URL (query string com versão), cache longo
        $hasVersion = false;
        if ($filePath) {
            $hasVersion = strpos($filePath, '?') !== false;
            if (defined('ASSET_VERSION')) {
                $hasVersion = $hasVersion || strpos($filePath, ASSET_VERSION) !== false;
            }
        }
        
        if ($hasVersion) {
            return ['max_age' => 31536000]; // 1 ano
        }
        // Sem versionamento, cache médio
        return ['max_age' => 86400]; // 1 dia
    }

    // Imagens - cache longo (1 ano)
    if (strpos($contentType, 'image/') !== false) {
        return ['max_age' => 31536000]; // 1 ano
    }

    // Fontes - cache longo (1 ano)
    if (in_array($contentType, [
        'font/woff',
        'font/woff2',
        'application/font-woff',
        'application/font-woff2',
        'font/ttf',
        'font/otf'
    ])) {
        return ['max_age' => 31536000]; // 1 ano
    }

    // JSON, XML - cache médio
    if (in_array($contentType, ['application/json', 'application/xml', 'text/xml'])) {
        return ['max_age' => 3600]; // 1 hora
    }

    // Padrão: no cache
    return ['max_age' => 0];
}

/**
 * Gera ETag baseado no conteúdo do arquivo
 * 
 * @param string $filePath Caminho do arquivo
 * @return string ETag
 */
function generate_etag($filePath) {
    $stat = stat($filePath);
    $etag = md5($filePath . $stat['mtime'] . $stat['size']);
    return '"' . $etag . '"';
}

/**
 * Obtém tipo MIME baseado na extensão
 * 
 * @param string $extension Extensão do arquivo
 * @return string Tipo MIME
 */
function get_mime_type($extension) {
    $mimeTypes = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'svg' => 'image/svg+xml',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'otf' => 'font/otf',
        'html' => 'text/html',
        'htm' => 'text/html',
        'php' => 'text/html',
        'xml' => 'application/xml',
    ];

    return $mimeTypes[$extension] ?? 'application/octet-stream';
}

/**
 * Tenta detectar content type da requisição atual
 * 
 * @return string|null Tipo MIME ou null
 */
function get_content_type_from_request() {
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    $path = parse_url($requestUri, PHP_URL_PATH);
    
    if ($path) {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return get_mime_type($extension);
    }
    
    return null;
}

/**
 * Define cache headers para páginas HTML (PHP)
 * Deve ser chamado no início de arquivos PHP
 */
function set_html_cache_headers() {
    // Tentar enviar headers mesmo se headers_sent() retornar true
    // Alguns servidores (como Apache com mod_headers) podem enviar headers automaticamente
    // mas ainda permitem adicionar headers adicionais
    
    $headersSent = headers_sent($file, $line);
    
    // HTML - NO CACHE (força sempre buscar versão nova)
    // Headers agressivos para bypassar cache Varnish da Locaweb
    // 'private' força bypass de cache compartilhado (Varnish/CDN)
    @header('Cache-Control: private, no-cache, no-store, must-revalidate, proxy-revalidate, max-age=0');
    @header('Pragma: no-cache');
    @header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');
    @header('Vary: Accept-Encoding, User-Agent');
    
    // Headers específicos para Varnish/Nginx (Locaweb usa Varnish)
    @header('X-Accel-Expires: 0'); // Nginx/Varnish: 0 = bypass cache
    @header('X-Cache-Status: BYPASS'); // Para debugging
    
    // Remover ETag completamente (não gerar ETag para HTML)
    // ETags podem causar 304 Not Modified mesmo com no-cache
    @header_remove('ETag');
    @header_remove('Last-Modified');
    
    // Header de debug com versão
    $assetVersion = defined('ASSET_VERSION') ? ASSET_VERSION : '0';
    @header('X-Cache-Version: ' . $assetVersion);
    
    // Se headers já foram enviados, adicionar informação de debug
    if ($headersSent) {
        @header('X-Headers-Sent-At: ' . $file . ':' . $line);
    }
}

