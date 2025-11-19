<?php
/**
 * Breadcrumbs Helper
 * Gera breadcrumbs estruturados para SEO e navegação
 * 
 * Desenvolvido por: Victor Penter
 * Versão: 1.0.0
 */

/**
 * Gera breadcrumbs HTML com Schema.org structured data
 * 
 * @param array $items Array de items do breadcrumb: [['label' => 'Home', 'url' => '/'], ...]
 * @param bool $includeSchema Se deve incluir Schema.org structured data
 * @return string HTML dos breadcrumbs
 */
function generate_breadcrumbs($items, $includeSchema = true) {
    if (empty($items)) {
        return '';
    }
    
    $html = '<nav aria-label="breadcrumb" class="breadcrumb-nav">';
    $html .= '<ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">';
    
    $position = 1;
    foreach ($items as $index => $item) {
        $isLast = ($index === count($items) - 1);
        $label = htmlspecialchars($item['label']);
        
        $html .= '<li class="breadcrumb-item' . ($isLast ? ' active' : '') . '"';
        
        if ($includeSchema) {
            $html .= ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"';
        }
        
        $html .= '>';
        
        if (!$isLast && isset($item['url'])) {
            $url = htmlspecialchars($item['url']);
            $html .= '<a href="' . $url . '"';
            
            if ($includeSchema) {
                $html .= ' itemprop="item"';
            }
            
            $html .= '>';
            
            if ($includeSchema) {
                $html .= '<span itemprop="name">' . $label . '</span>';
            } else {
                $html .= $label;
            }
            
            $html .= '</a>';
        } else {
            if ($includeSchema) {
                $html .= '<span itemprop="name">' . $label . '</span>';
            } else {
                $html .= $label;
            }
        }
        
        if ($includeSchema) {
            $html .= '<meta itemprop="position" content="' . $position . '" />';
        }
        
        $html .= '</li>';
        $position++;
    }
    
    $html .= '</ol>';
    $html .= '</nav>';
    
    return $html;
}

/**
 * Gera breadcrumbs padrão para página de serviço
 * 
 * @param string $serviceName Nome do serviço
 * @param string $serviceUrl URL do serviço (opcional)
 * @return string HTML dos breadcrumbs
 */
function generate_service_breadcrumbs($serviceName, $serviceUrl = null) {
    $items = [
        ['label' => 'Home', 'url' => '/'],
        ['label' => $serviceName, 'url' => $serviceUrl]
    ];
    
    return generate_breadcrumbs($items);
}

