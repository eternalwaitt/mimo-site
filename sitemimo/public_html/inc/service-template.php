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
    
    $pageDescription = $serviceDescriptions[$serviceNameClean] ?? "Serviços de {$serviceNameClean} em São Paulo. Centro de beleza MIMO Estética com profissionais especializadas e preços acessíveis.";
    $pageTitle = "{$serviceNameClean} em São Paulo - MIMO Estética | Preços e Agendamento";
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
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=EB+Garamond:400,400i" rel="stylesheet">
    <link href="../Akrobat-Regular.woff" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"
        integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    
    <!-- Bootstrap core CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom styles -->
    <?php echo css_tag('product.css'); ?>
    <?php echo css_tag('servicos.css'); ?>
    
    <!-- Form -->
    <link rel="stylesheet" type="text/css" href="../form/css/font-awesome.min.css">
    <?php echo css_tag('form/main.css'); ?>
    
    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon/apple-touch-icon.png?<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : '20211226'; ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon/favicon-32x32.png?<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : '20211226'; ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png?<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : '20211226'; ?>">
    <link rel="manifest" href="../favicon/site.webmanifest">
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
            echo '<footer class="container"><div class="row"><div class="col-12 col-md my-2 py-2"><small class="d-block text-muted text-center" style="line-height: 3;">&copy; MIMO Estética 2018 | Todos os direitos reservados</small></div></div></footer>';
        endif;
        ?>
    </div>
    
    <?php if (!isset($footerInsideTabContent) || !$footerInsideTabContent): ?>
    <footer class="container">
        <div class="row">
            <div class="col-12 col-md my-2 py-2">
                <small class="d-block text-muted text-center" style="line-height: 3;">&copy; MIMO Estética 2018 | Todos os direitos reservados</small>
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
    <?php echo js_tag('main.js'); ?>
    <script src="//code.tidio.co/ylbfxpiqcmi2on8duid7rpjgqydlrqne.js"></script>
    
    <!-- Botão Voltar ao Topo -->
    <?php include '../inc/back-to-top.php'; ?>
</body>
</html>
