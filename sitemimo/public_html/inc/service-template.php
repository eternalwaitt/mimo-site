<?php
/**
 * Template de Páginas de Serviços
 * Estrutura comum para páginas de serviços para reduzir duplicação de código
 * 
 * Desenvolvido por: Victor Penter
 * Versão: <?php echo APP_VERSION; ?>
 * 
 * Variáveis obrigatórias:
 * $serviceName - Nome do serviço para o título
 * $headerClass - Classe CSS para o cabeçalho (ex: 'esmal-header', 'cilios-header')
 * $headerTitle - Texto do título para o banner do cabeçalho
 * $tabs - Array de definições de abas
 * $tabContent - Array de conteúdo HTML das abas (ou use ob_start/ob_get_clean)
 * 
 * Variáveis opcionais:
 * $includeGTM - Defina como true para incluir scripts GTM (padrão: true)
 * 
 * Exemplo de uso:
 * $serviceName = 'Esmalteria';
 * $headerClass = 'esmal-header';
 * $headerTitle = 'ESMALTERIA';
 * $tabs = [
 *     ['id' => 'alongamentos', 'label' => 'alongamentos', 'active' => true],
 *     ['id' => 'blindagem', 'label' => 'blindagem', 'active' => false]
 * ];
 * // Defina o conteúdo antes de incluir o template, ou use output buffering
 * ob_start();
 * // ... HTML do conteúdo da aba ...
 * $tabContent['alongamentos'] = ob_get_clean();
 * include '../inc/service-template.php';
 */

// Suprimir avisos de depreciação (compatibilidade PHP 8.4)
error_reporting(E_ALL & ~E_DEPRECATED);

// IMPORTANTE: Cache headers DEVEM ser os PRIMEIROS headers enviados
// Carregar configuração primeiro (necessário para ASSET_VERSION)
require_once '../config.php';

// Cache headers para páginas HTML (ANTES de qualquer outro header)
require_once '../inc/cache-headers.php';
set_html_cache_headers();

// Cabeçalhos de segurança (depois dos cache headers)
require_once '../inc/security-headers.php';

// Funções auxiliares de imagem para suporte WebP
require_once '../inc/image-helper.php';

// Helper de SEO
require_once '../inc/seo-helper.php';

// Helper de assets (CSS/JS com suporte a minificação)
require_once '../inc/asset-helper.php';

// Helper de breadcrumbs
require_once '../inc/breadcrumbs.php';

// Default GTM inclusion
if (!isset($includeGTM)) {
    $includeGTM = true;
}
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta name="generator" content="Mimo Site v<?php echo APP_VERSION; ?>">
    <?php if ($includeGTM): ?>
        <?php include "../inc/gtm-head.php"; ?>
    <?php endif; ?>
    
    <?php if (isset($customHeadContent)): ?>
        <?php echo $customHeadContent; ?>
    <?php endif; ?>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Victor Penter">
    
    <?php
    // SEO Meta Tags dinâmicas por serviço
    $serviceNameClean = htmlspecialchars($serviceName ?? 'Serviços');
    $serviceSlug = strtolower(str_replace([' ', 'é', 'ê'], ['-', 'e', 'e'], $serviceNameClean));
    
    // Descrições específicas por serviço
    $serviceDescriptions = [
        'Esmalteria' => 'Esmalteria em São Paulo: alongamento de unhas, blindagem e manicure & pedicure. Serviços de qualidade com preços acessíveis. Agende seu horário!',
        'Cílios e Design' => 'Design de sobrancelha e extensão de cílios em São Paulo. Mimo Lash Lift, design personalizado e combos. Profissionais especializadas. Agende!',
        'Estética Corporal' => 'Estética corporal em São Paulo: radiofrequência, ultrassom, endermoterapia, drenagem linfática e massagens. Tratamentos para celulite e gordura localizada.',
        'Estética Facial' => 'Estética facial em São Paulo: limpeza de pele, microagulhamento e tratamentos faciais. Cuidados especializados para sua pele. Agende sua avaliação!',
        'Micropigmentação' => 'Micropigmentação em São Paulo: sobrancelhas, lábios e despigmentação. Resultados naturais e duradouros. Profissionais especializadas.',
        'Salão' => 'Salão de beleza em São Paulo: mechas, coloração, corte, alisamento e mega hair. Serviços completos de cabelo com profissionais especializadas.'
    ];
    
    $pageDescription = $serviceDescriptions[$serviceNameClean] ?? "Serviços de {$serviceNameClean} em São Paulo. Centro de beleza Mimo com profissionais especializadas e preços acessíveis.";
    $pageTitle = "{$serviceNameClean} em São Paulo - Mimo | Preços e Informações";
    $pageKeywords = strtolower($serviceNameClean) . ' são paulo, ' . strtolower($serviceNameClean) . ' vila madalena, mimo estética, centro de beleza são paulo';
    
    echo generate_seo_meta_tags($pageTitle, $pageDescription, $pageKeywords);
    echo generate_canonical_url();
    echo generate_open_graph_tags($pageTitle, $pageDescription, '../img/servicos/' . $serviceSlug . '/header.jpg');
    echo generate_twitter_cards($pageTitle, $pageDescription, '../img/servicos/' . $serviceSlug . '/header.jpg');
    ?>
    
    <!-- Resource Hints for Performance -->
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="dns-prefetch" href="https://stackpath.bootstrapcdn.com">
    <link rel="dns-prefetch" href="https://use.fontawesome.com">
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Script loader for deferred CSS - Must come before deferred resources -->
    <script src="<?php echo get_js_asset('js/loadcss-polyfill.js'); ?>"></script>
    
    <!-- Fonts with font-display: swap for better performance - Defer loading -->
    <script>loadCSS("https://fonts.googleapis.com/css?family=Nunito:200,300,400&display=swap");</script>
    <noscript><link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400&display=swap" rel="stylesheet"></noscript>
    <script>loadCSS("https://fonts.googleapis.com/css?family=EB+Garamond:400,400i,700i&display=swap");</script>
    <noscript><link href="https://fonts.googleapis.com/css?family=EB+Garamond:400,400i,700i&display=swap" rel="stylesheet"></noscript>
    <!-- Akrobat font loaded via CSS @font-face in product.css -->
    
    <!-- Font Awesome 6 - Defer loading (same as index.php) -->
    <script>loadCSS("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css");</script>
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer"></noscript>
    
    <!-- Bootstrap core CSS - Defer non-critical -->
    <script>loadCSS("https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css");</script>
    <noscript><link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"></noscript>
    
    <!-- CSS Variables (deve vir antes de product.css) -->
    <link rel="stylesheet" href="<?php echo get_css_asset('css/modules/_variables.css'); ?>">
    
    <!-- Custom styles -->
    <?php echo css_tag('product.css'); ?>
    
    <!-- Dark Mode Styles -->
    <link rel="stylesheet" href="<?php echo get_css_asset('css/modules/dark-mode.css'); ?>">
    
    <!-- Fix para ícones Font Awesome no footer -->
    <style>
    .site-footer .footer-social-link {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 42px !important;
        height: 42px !important;
    }
    
    .site-footer .footer-social-link i.fab {
        font-family: "Font Awesome 6 Brands" !important;
        font-weight: 400 !important;
        font-size: 18px !important;
        display: inline-block !important;
        line-height: 1 !important;
        width: auto !important;
        height: auto !important;
        margin: 0 !important;
        padding: 0 !important;
        -webkit-font-smoothing: antialiased !important;
        -moz-osx-font-smoothing: grayscale !important;
    }
    
    .site-footer .footer-social-link i.fa-instagram::before {
        content: "\f16d" !important;
        font-family: "Font Awesome 6 Brands" !important;
        font-weight: 400 !important;
        display: inline-block !important;
    }
    
    .site-footer .footer-social-link i.fa-facebook-f::before {
        content: "\f39e" !important;
        font-family: "Font Awesome 6 Brands" !important;
        font-weight: 400 !important;
        display: inline-block !important;
    }
    
    .site-footer .footer-social-link i.fa-whatsapp::before {
        content: "\f232" !important;
        font-family: "Font Awesome 6 Brands" !important;
        font-weight: 400 !important;
        display: inline-block !important;
    }
    </style>
    <?php echo css_tag('servicos.css'); ?>
    
    <!-- Form -->
    <link rel="stylesheet" type="text/css" href="../form/css/font-awesome.min.css">
    <?php echo css_tag('form/main.css'); ?>
    
    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon/apple-touch-icon.png?<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : '20211226'; ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon/favicon-32x32.png?<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : '20211226'; ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png?<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : '20211226'; ?>">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#ffffff">
</head>

<body>
    <?php if ($includeGTM): ?>
        <?php include "../inc/gtm-body.php"; ?>
    <?php endif; ?>
    
    <?php if (isset($customBodyStartContent)): ?>
        <?php echo $customBodyStartContent; ?>
    <?php endif; ?>
    
    <?php include "../inc/header-inner.php"; ?>
    
    <!-- Breadcrumbs -->
    <?php
    $currentUrl = $_SERVER['REQUEST_URI'] ?? '';
    $serviceUrl = str_replace('../', '', $currentUrl);
    echo generate_service_breadcrumbs($serviceNameClean ?? $serviceName ?? 'Serviços', $serviceUrl);
    ?>
    
    <?php if (isset($customContentBeforeBanner)): ?>
        <?php echo $customContentBeforeBanner; ?>
    <?php endif; ?>
    
    <!-- Banner -->
    <?php if (isset($headerClass) && isset($headerTitle)): ?>
    <div class="position-relative overflow-hidden <?php echo htmlspecialchars($headerClass); ?> text-center text-white">
        <h2 style="line-height: 9; font-size: 45px;"><?php echo htmlspecialchars($headerTitle); ?></h2>
    </div>
    <?php endif; ?>
    
    <!-- Navigation -->
    <?php if (isset($tabs) && is_array($tabs) && count($tabs) > 0): ?>
    <?php
    // Support custom tab ID prefix (default: "pills-")
    $tabIdPrefix = isset($tabIdPrefix) ? $tabIdPrefix : 'pills-';
    $tabListId = isset($tabListId) ? $tabListId : 'pills-tab';
    $tabContentId = isset($tabContentId) ? $tabContentId : 'pills-tabContent';
    $tabContentClass = isset($tabContentClass) ? $tabContentClass : 'mb-5';
    ?>
    <ul class="nav nav-pills mt-5 mb-5" id="<?php echo htmlspecialchars($tabListId); ?>" role="tablist" style="padding-right: 10%;">
        <li class="nav-item voltarBtn">
            <a class="nav-link" href="../#services"
                style="font-size: 14px; width: 101%; margin-left: 36px; text-transform: lowercase;">
                &lt; voltar
            </a>
        </li>
        <?php foreach ($tabs as $tab): ?>
        <li class="nav-item" style="margin: auto">
            <?php
            // Support custom nav link ID format
            $navLinkId = isset($tab['navLinkId']) ? $tab['navLinkId'] : $tabIdPrefix . $tab['id'] . 's';
            $tabPaneId = isset($tab['tabPaneId']) ? $tab['tabPaneId'] : $tabIdPrefix . $tab['id'];
            ?>
            <a class="nav-link <?php echo $tab['active'] ? 'active' : ''; ?>" 
               id="<?php echo htmlspecialchars($navLinkId); ?>" 
               data-toggle="pill" 
               href="#<?php echo htmlspecialchars($tabPaneId); ?>" 
               role="tab"
               aria-controls="<?php echo htmlspecialchars($tabPaneId); ?>" 
               aria-selected="<?php echo $tab['active'] ? 'true' : 'false'; ?>">
                <?php echo htmlspecialchars($tab['label']); ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
    
    <!-- Content -->
    <div class="tab-content <?php echo htmlspecialchars($tabContentClass); ?>" id="<?php echo htmlspecialchars($tabContentId); ?>">
        <?php
        // Content can be provided via $tabContent array or included directly
        if (isset($tabContent) && is_array($tabContent)):
            foreach ($tabs as $tab):
                $tabId = $tab['id'];
                if (isset($tabContent[$tabId])):
                    $tabPaneId = isset($tab['tabPaneId']) ? $tab['tabPaneId'] : $tabIdPrefix . $tabId;
                    $navLinkId = isset($tab['navLinkId']) ? $tab['navLinkId'] : $tabIdPrefix . $tabId . 's';
                    echo '<div class="tab-pane fade ' . ($tab['active'] ? 'show active' : '') . '" id="' . htmlspecialchars($tabPaneId) . '" role="tabpanel" aria-labelledby="' . htmlspecialchars($navLinkId) . '">';
                    echo $tabContent[$tabId];
                    echo '</div>';
                endif;
            endforeach;
        endif;
        
        // Support custom footer inside tab-content (for salao)
        if (isset($footerInsideTabContent) && $footerInsideTabContent):
            echo '<footer class="container"><div class="row"><div class="col-12 col-md my-2 py-2"><small class="d-block text-muted text-center" style="line-height: 3;">&copy; Mimo 2018 | Todos os direitos reservados</small></div></div></footer>';
        endif;
        ?>
    </div>
    
    <?php if (!isset($footerInsideTabContent) || !$footerInsideTabContent): ?>
    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <!-- Links de Navegação -->
                <div class="col-12 col-md-4 mb-4 mb-md-0">
                    <h5 class="footer-title">Navegação</h5>
                    <nav class="footer-nav-vertical">
                        <a href="../#about" class="footer-link">Sobre</a>
                        <a href="../#services" class="footer-link">Serviços</a>
                        <a href="../contato.php" class="footer-link">Contato</a>
                        <a href="../faq/" class="footer-link">FAQ</a>
                        <a href="../vagas.php" class="footer-link">Trabalhe Conosco</a>
                    </nav>
                </div>
                
                <!-- Informações de Contato -->
                <div class="col-12 col-md-4 mb-4 mb-md-0">
                    <h5 class="footer-title">Contato</h5>
                    <div class="footer-contact">
                        <p class="footer-contact-item">
                            <svg class="footer-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <span>Rua Heitor Penteado, 626<br>Vila Madalena, São Paulo - SP</span>
                        </p>
                        <p class="footer-contact-item">
                            <svg class="footer-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            <span><strong>Telefone:</strong> (11) 3062-8295</span>
                        </p>
                        <p class="footer-contact-item">
                            <svg class="footer-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.057-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.386 1.262.617 1.694.789.712.28 1.36.24 1.871.146.571-.104 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            <span><strong>WhatsApp:</strong> (11) 99478-1012</span>
                        </p>
                    </div>
                </div>
                
                <!-- Redes Sociais -->
                <div class="col-12 col-md-4 footer-social-col">
                    <h5 class="footer-title">Redes Sociais</h5>
                    <div class="footer-social">
                        <a href="https://www.instagram.com/minhamimo/" target="_blank" class="footer-social-link" aria-label="Instagram">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <a href="https://www.facebook.com/mimocuidadoebeleza/" target="_blank" class="footer-social-link" aria-label="Facebook">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="https://api.whatsapp.com/send?1=pt_BR&phone=5511994781012" target="_blank" class="footer-social-link" aria-label="WhatsApp">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="row">
                <div class="col-12">
                    <div class="footer-copyright">
                        <p>&copy; <?php echo date('Y'); ?> Mimo | 57.659.472/0001-78 | Todos os direitos reservados</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <?php endif; ?>
    
    <?php
    // Schema.org Structured Data - Service
    $serviceDescriptions = [
        'Esmalteria' => 'Serviços de esmalteria incluindo alongamento de unhas, blindagem e manicure & pedicure',
        'Cílios e Design' => 'Design de sobrancelha personalizado e extensão de cílios com técnicas modernas',
        'Estética Corporal' => 'Tratamentos estéticos corporais para celulite, gordura localizada e flacidez',
        'Estética Facial' => 'Tratamentos faciais especializados incluindo limpeza de pele e microagulhamento',
        'Micropigmentação' => 'Micropigmentação de sobrancelhas, lábios e procedimentos de despigmentação',
        'Salão' => 'Serviços completos de salão incluindo mechas, coloração, corte e alisamento'
    ];
    
    $serviceDesc = $serviceDescriptions[$serviceNameClean] ?? "Serviços de {$serviceNameClean}";
    echo generate_service_schema($serviceNameClean, $serviceDesc, '$$', '../img/servicos/' . $serviceSlug . '/header.jpg');
    
    // Breadcrumbs Schema
    $breadcrumbs = [
        ['name' => 'Início', 'url' => '/'],
        ['name' => 'Serviços', 'url' => '/#services'],
        ['name' => $serviceNameClean, 'url' => $_SERVER['REQUEST_URI']]
    ];
    echo generate_breadcrumb_schema($breadcrumbs);
    ?>
    
    <!-- Bootstrap core JavaScript -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../bootstrap/jquery/dist/jquery.slim.min.js"><\/script>')</script>
    <script src="../bootstrap/popper.js/dist/popper.min.js"></script>
    <script src="../bootstrap/bootstrap/dist/js/bootstrap.min.js"></script>
    <?php echo js_tag('form/main.js'); ?>
    <?php echo js_tag('js/bc-swipe.js'); ?>
    <?php echo js_tag('main.js', ['defer' => true]); ?>
    <?php echo js_tag('js/dark-mode.js', ['defer' => false]); ?>
    <!-- Tidio chat removido - script retorna 404 -->
    
    <!-- Botão Voltar ao Topo -->
    <?php include '../inc/back-to-top.php'; ?>

    <script>
        // Forçar navbar com fundo desde o início em páginas internas
        (function() {
            const pageHero = document.querySelector('.page-hero');
            if (!pageHero) return;
            
            function forceNavbarBackground() {
                const navbar = document.querySelector('.navbar');
                const navbarNav = document.querySelector('.navbar-nav');
                const navbarBrand = document.querySelector('.navbar-brand');
                
                if (navbar) {
                    navbar.classList.add('compressed');
                    if (navbarNav) navbarNav.classList.add('changecolormenu');
                    if (navbarBrand) navbarBrand.classList.add('changecolorlogo');
                }
            }
            
            // Executar imediatamente
            forceNavbarBackground();
            
            // Executar quando DOM estiver pronto
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', forceNavbarBackground);
            } else {
                forceNavbarBackground();
            }
            
            // Executar após um pequeno delay para garantir
            setTimeout(forceNavbarBackground, 100);
            
            // Interceptar o evento de scroll para manter a classe SEMPRE
            if (typeof jQuery !== 'undefined') {
                $(window).on('scroll', function() {
                    // Sempre forçar o fundo escuro em páginas internas, independente da posição do scroll
                    forceNavbarBackground();
                });
            }
            
            // Também garantir após qualquer mudança de scroll
            window.addEventListener('scroll', forceNavbarBackground, { passive: true });
        })();
    </script>

    <!-- Fix footer nav vertical -->
    <script>
        (function() {
            function fixFooterNav() {
                const nav = document.querySelector('.footer-nav-vertical');
                if (nav) {
                    nav.style.display = 'flex';
                    nav.style.flexDirection = 'column';
                    Array.from(nav.children).forEach(link => {
                        link.style.display = 'block';
                    });
                }
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', fixFooterNav);
            } else {
                fixFooterNav();
            }
            setTimeout(fixFooterNav, 100);
        })();
    </script>
    
    <!-- Fix footer social icons centering -->
    <style>
        .footer-social-col {
            text-align: center !important;
        }
        .footer-social-col .footer-title {
            text-align: center !important;
        }
        
        /* Forçar esconder breadcrumb e corrigir ícones do footer */
        nav.breadcrumb-nav,
        .breadcrumb-nav,
        nav[aria-label="breadcrumb"] {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            position: absolute !important;
            width: 1px !important;
            height: 1px !important;
            overflow: hidden !important;
            clip: rect(0, 0, 0, 0) !important;
            pointer-events: none !important;
        }
        
        /* SVG Icons - tamanho consistente e moderno */
        .footer-contact-item .footer-icon {
            width: 20px !important;
            height: 20px !important;
            min-width: 20px !important;
            max-width: 20px !important;
            color: #b895a0 !important;
            fill: #b895a0 !important;
            stroke: #b895a0 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-shrink: 0 !important;
            margin-top: 2px;
        }
        
        /* Garantir que ícones de redes sociais não sejam afetados - remover qualquer override */
        .footer-social-link {
            /* Manter estilos originais - não sobrescrever */
        }
    </style>
    
    <!-- Script para garantir que breadcrumb e ícones sejam corrigidos após carregamento -->
    <script>
        (function() {
            function fixBreadcrumbAndIcons() {
                // Esconder breadcrumb
                const breadcrumb = document.querySelector('.breadcrumb-nav');
                if (breadcrumb) {
                    breadcrumb.style.display = 'none';
                    breadcrumb.style.visibility = 'hidden';
                    breadcrumb.style.opacity = '0';
                }
                
                // Ionicons já tem tamanho consistente por padrão, não precisa de ajustes
            }
            
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', fixBreadcrumbAndIcons);
            } else {
                fixBreadcrumbAndIcons();
            }
            
            // Executar novamente após um delay para garantir
            setTimeout(fixBreadcrumbAndIcons, 100);
            setTimeout(fixBreadcrumbAndIcons, 500);
        })();
    </script>
</body>
</html>
