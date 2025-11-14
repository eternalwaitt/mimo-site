<?php
/**
 * Helper de SEO
 * Funções auxiliares para otimização de SEO
 * 
 * Desenvolvido por: Victor Penter
 * Versão: <?php echo APP_VERSION ?? '2.0.0'; ?>
 */

/**
 * Gera meta tags Open Graph para redes sociais
 * 
 * @param string $title Título da página
 * @param string $description Descrição da página
 * @param string $image URL da imagem (opcional)
 * @param string $url URL canônica (opcional)
 * @param string $type Tipo Open Graph (default: website)
 * @return string HTML com meta tags Open Graph
 */
function generate_open_graph_tags($title, $description, $image = '', $url = '', $type = 'website') {
    $siteUrl = defined('SITE_URL') ? SITE_URL : 'https://minhamimo.com.br';
    $defaultImage = $siteUrl . '/img/logobranco1.png';
    
    $ogImage = $image ?: $defaultImage;
    $ogUrl = $url ?: $siteUrl . $_SERVER['REQUEST_URI'];
    
    // Garantir URL absoluta para imagem
    if (strpos($ogImage, 'http') !== 0) {
        $ogImage = $siteUrl . '/' . ltrim($ogImage, '/');
    }
    
    $html = "\n    <!-- Open Graph / Facebook -->\n";
    $html .= '    <meta property="og:type" content="' . htmlspecialchars($type) . '">' . "\n";
    $html .= '    <meta property="og:url" content="' . htmlspecialchars($ogUrl) . '">' . "\n";
    $html .= '    <meta property="og:title" content="' . htmlspecialchars($title) . '">' . "\n";
    $html .= '    <meta property="og:description" content="' . htmlspecialchars($description) . '">' . "\n";
    $html .= '    <meta property="og:image" content="' . htmlspecialchars($ogImage) . '">' . "\n";
    $html .= '    <meta property="og:image:width" content="1200">' . "\n";
    $html .= '    <meta property="og:image:height" content="630">' . "\n";
    $html .= '    <meta property="og:locale" content="pt_BR">' . "\n";
    $html .= '    <meta property="og:site_name" content="MIMO Estética">' . "\n";
    
    return $html;
}

/**
 * Gera meta tags Twitter Cards
 * 
 * @param string $title Título da página
 * @param string $description Descrição da página
 * @param string $image URL da imagem (opcional)
 * @param string $cardType Tipo de card (summary, summary_large_image)
 * @return string HTML com meta tags Twitter Cards
 */
function generate_twitter_cards($title, $description, $image = '', $cardType = 'summary_large_image') {
    $siteUrl = defined('SITE_URL') ? SITE_URL : 'https://minhamimo.com.br';
    $defaultImage = $siteUrl . '/img/logobranco1.png';
    
    $twitterImage = $image ?: $defaultImage;
    
    // Garantir URL absoluta para imagem
    if (strpos($twitterImage, 'http') !== 0) {
        $twitterImage = $siteUrl . '/' . ltrim($twitterImage, '/');
    }
    
    $html = "\n    <!-- Twitter Card -->\n";
    $html .= '    <meta name="twitter:card" content="' . htmlspecialchars($cardType) . '">' . "\n";
    $html .= '    <meta name="twitter:title" content="' . htmlspecialchars($title) . '">' . "\n";
    $html .= '    <meta name="twitter:description" content="' . htmlspecialchars($description) . '">' . "\n";
    $html .= '    <meta name="twitter:image" content="' . htmlspecialchars($twitterImage) . '">' . "\n";
    
    return $html;
}

/**
 * Gera Schema.org JSON-LD para LocalBusiness (centro de beleza)
 * 
 * @param array $options Opções personalizadas
 * @return string JSON-LD script tag
 */
function generate_local_business_schema($options = []) {
    $siteUrl = defined('SITE_URL') ? SITE_URL : 'https://minhamimo.com.br';
    
    $defaults = [
        'name' => 'MIMO Estética',
        'description' => 'Centro de beleza e estética oferecendo serviços de qualidade com preços acessíveis. Você merece esse mimo!',
        'address' => [
            'streetAddress' => 'Rua Heitor Penteado, 626',
            'addressLocality' => 'São Paulo',
            'addressRegion' => 'SP',
            'postalCode' => '',
            'addressCountry' => 'BR'
        ],
        'telephone' => '+55-11-3062-8295',
        'priceRange' => '$$',
        'openingHours' => [
            'Tu-Sa 08:30-22:00'
        ],
        'image' => $siteUrl . '/img/logobranco1.png',
        'url' => $siteUrl,
        'sameAs' => [
            'https://www.instagram.com/minhamimo/',
            'https://www.facebook.com/mimocuidadoebeleza/'
        ]
    ];
    
    $data = array_merge($defaults, $options);
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'BeautySalon',
        'name' => $data['name'],
        'description' => $data['description'],
        'image' => $data['image'],
        'url' => $data['url'],
        'telephone' => $data['telephone'],
        'priceRange' => $data['priceRange'],
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $data['address']['streetAddress'],
            'addressLocality' => $data['address']['addressLocality'],
            'addressRegion' => $data['address']['addressRegion'],
            'addressCountry' => $data['address']['addressCountry']
        ],
        'openingHoursSpecification' => []
    ];
    
    // Adicionar horários de funcionamento
    foreach ($data['openingHours'] as $hours) {
        // Formato: "Tu-Sa 08:30-22:00"
        if (preg_match('/([A-Za-z]+)-([A-Za-z]+)\s+(\d{2}:\d{2})-(\d{2}:\d{2})/', $hours, $matches)) {
            $schema['openingHoursSpecification'][] = [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => [
                    'https://schema.org/' . ucfirst($matches[1]) . 'day',
                    'https://schema.org/' . ucfirst($matches[2]) . 'day'
                ],
                'opens' => $matches[3],
                'closes' => $matches[4]
            ];
        }
    }
    
    // Adicionar redes sociais
    if (!empty($data['sameAs'])) {
        $schema['sameAs'] = $data['sameAs'];
    }
    
    // Adicionar geo se fornecido
    if (isset($data['geo'])) {
        $schema['geo'] = [
            '@type' => 'GeoCoordinates',
            'latitude' => $data['geo']['latitude'],
            'longitude' => $data['geo']['longitude']
        ];
    }
    
    $html = "\n    <!-- Schema.org JSON-LD -->\n";
    $html .= '    <script type="application/ld+json">' . "\n";
    $html .= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    $html .= "\n    </script>\n";
    
    return $html;
}

/**
 * Gera Schema.org para Service (serviço específico)
 * 
 * @param string $serviceName Nome do serviço
 * @param string $description Descrição do serviço
 * @param string $priceRange Faixa de preço (ex: "R$ 100,00 - R$ 500,00")
 * @param string $image URL da imagem do serviço
 * @return string JSON-LD script tag
 */
function generate_service_schema($serviceName, $description, $priceRange = '', $image = '') {
    $siteUrl = defined('SITE_URL') ? SITE_URL : 'https://minhamimo.com.br';
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'serviceType' => $serviceName,
        'description' => $description,
        'provider' => [
            '@type' => 'BeautySalon',
            'name' => 'MIMO Estética',
            'url' => $siteUrl
        ]
    ];
    
    if ($priceRange) {
        $schema['offers'] = [
            '@type' => 'Offer',
            'priceRange' => $priceRange
        ];
    }
    
    if ($image) {
        if (strpos($image, 'http') !== 0) {
            $image = $siteUrl . '/' . ltrim($image, '/');
        }
        $schema['image'] = $image;
    }
    
    $html = "\n    <!-- Schema.org Service -->\n";
    $html .= '    <script type="application/ld+json">' . "\n";
    $html .= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    $html .= "\n    </script>\n";
    
    return $html;
}

/**
 * Gera breadcrumbs Schema.org
 * 
 * @param array $breadcrumbs Array de [['name' => 'Nome', 'url' => 'url']]
 * @return string JSON-LD script tag
 */
function generate_breadcrumb_schema($breadcrumbs) {
    $siteUrl = defined('SITE_URL') ? SITE_URL : 'https://minhamimo.com.br';
    
    $items = [];
    $position = 1;
    
    foreach ($breadcrumbs as $crumb) {
        $items[] = [
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => $crumb['name'],
            'item' => (strpos($crumb['url'], 'http') === 0) ? $crumb['url'] : $siteUrl . '/' . ltrim($crumb['url'], '/')
        ];
    }
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $items
    ];
    
    $html = "\n    <!-- Schema.org BreadcrumbList -->\n";
    $html .= '    <script type="application/ld+json">' . "\n";
    $html .= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    $html .= "\n    </script>\n";
    
    return $html;
}

/**
 * Gera canonical URL
 * 
 * @param string $url URL canônica (opcional, usa REQUEST_URI se não fornecido)
 * @return string HTML meta tag
 */
function generate_canonical_url($url = '') {
    $siteUrl = defined('SITE_URL') ? SITE_URL : 'https://minhamimo.com.br';
    
    if (empty($url)) {
        $url = $siteUrl . $_SERVER['REQUEST_URI'];
        // Remover query strings para canonical
        $url = strtok($url, '?');
    } elseif (strpos($url, 'http') !== 0) {
        $url = $siteUrl . '/' . ltrim($url, '/');
    }
    
    return '    <link rel="canonical" href="' . htmlspecialchars($url) . '">' . "\n";
}

/**
 * Gera meta tags básicas de SEO
 * 
 * @param string $title Título da página
 * @param string $description Meta description
 * @param string $keywords Palavras-chave (opcional)
 * @return string HTML com meta tags
 */
function generate_seo_meta_tags($title, $description, $keywords = '') {
    $html = '    <title>' . htmlspecialchars($title) . '</title>' . "\n";
    $html .= '    <meta name="description" content="' . htmlspecialchars($description) . '">' . "\n";
    
    if ($keywords) {
        $html .= '    <meta name="keywords" content="' . htmlspecialchars($keywords) . '">' . "\n";
    }
    
    // Meta tags adicionais
    $html .= '    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">' . "\n";
    $html .= '    <meta name="googlebot" content="index, follow">' . "\n";
    $html .= '    <meta name="language" content="Portuguese">' . "\n";
    $html .= '    <meta name="geo.region" content="BR-SP">' . "\n";
    $html .= '    <meta name="geo.placename" content="São Paulo">' . "\n";
    
    return $html;
}

