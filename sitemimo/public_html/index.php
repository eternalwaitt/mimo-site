<?php
/**
 * Site Mimo - Página Inicial
 * 
 * Desenvolvido por: Victor Penter
 * Versão: 2.5.0
 * 
 * Este arquivo contém a página principal do site com formulário de contato
 * e seções de serviços, depoimentos e informações de contato.
 */

// CRITICAL: Start HTML minification output buffer (Phase 9.1)
require_once __DIR__ . '/inc/html-minify.php';
start_html_minify();

// Suprimir avisos de depreciação de bibliotecas de terceiros (compatibilidade PHP 8.4)
error_reporting(E_ALL & ~E_DEPRECATED);

// IMPORTANTE: Cache headers DEVEM ser os PRIMEIROS headers enviados
// Carregar configuração primeiro (necessário para ASSET_VERSION)
require_once 'config.php';

// Cache headers são definidos abaixo para controlar cache do navegador e Varnish

// Cache headers para páginas HTML (ANTES de qualquer outro header)
require_once 'inc/cache-headers.php';
set_html_cache_headers();

// Cabeçalhos de segurança (depois dos cache headers)
require_once 'inc/security-headers.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Funções auxiliares de imagem
require_once 'inc/image-helper.php';

// Helper de SEO
require_once 'inc/seo-helper.php';

// Helper de assets (CSS/JS com suporte a minificação)
require_once 'inc/asset-helper.php';

// Helper de segurança do formulário
require_once 'inc/form-security.php';

// Helper de reviews do Google (opcional - requer API key)
require_once 'inc/google-reviews.php';

// Helper de reviews manuais (alternativa grátis)
require_once 'inc/manual-reviews.php';

// Autoloader do Composer (PHPMailer, etc.)
require 'vendor/autoload.php';

// Iniciar sessão para rate limiting
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_POST) {

    $is_mail_sent = false;
    $form_errors = [];

    // Verificar honeypot (campo escondido que bots preenchem)
    if (is_honeypot_filled()) {
        // Silenciosamente rejeitar (não mostrar erro para não alertar bots)
        $is_mail_sent = false;
    } else {
        // Verificar rate limiting
        $rateLimit = check_rate_limit(3, 3600); // 3 tentativas por hora
        if (!$rateLimit['allowed']) {
            $form_errors[] = 'Muitas tentativas. Por favor, tente novamente em ' . 
                           round(($rateLimit['reset_at'] - time()) / 60) . ' minutos.';
        } else {
            // Validar e sanitizar dados
            $nomeremetente = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $emailremetente = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $assunto = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $mensagem = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Validações
            if (!validate_name($nomeremetente)) {
                $form_errors[] = 'Nome inválido. Deve ter entre 2 e 100 caracteres.';
            }

            if (!validate_email($emailremetente)) {
                $form_errors[] = 'Email inválido.';
            }

            if (!validate_subject($assunto)) {
                $form_errors[] = 'Assunto inválido.';
            }

            if (!validate_message($mensagem)) {
                $form_errors[] = 'Mensagem inválida. Deve ter entre 10 e 2000 caracteres.';
            }

            // Verificar palavras de spam
            if (contains_spam_keywords($mensagem)) {
                $form_errors[] = 'Mensagem contém conteúdo não permitido.';
            }

            // Se passou todas as validações
            if (empty($form_errors)) {
                $mensagemHTML = '<strong>Formulário de Contato</strong>
                                 <p><b>Nome:</b> ' . sanitize_html($nomeremetente) . '</p>
                                 <p><b>E-Mail:</b> ' . sanitize_html($emailremetente) . '</p>
                                 <p><b>Assunto:</b> ' . sanitize_html($assunto) . '</p>
                                 <p><b>Mensagem:</b> ' . nl2br(sanitize_html($mensagem)) . '</p>
                                 <hr>
                                 <p><small>IP: ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . '</small></p>';

                // Verificar se credenciais do Mailgun estão configuradas
                if (!isset($MailGunUsername) || empty($MailGunUsername) || !isset($MailGunPassword) || empty($MailGunPassword)) {
                    $form_errors[] = 'Configuração de email não encontrada. Entre em contato com o administrador.';
                    error_log('Mailgun credentials not configured');
                    increment_rate_limit();
                } else {
                    $is_mail_sent = true;
                }
            } else {
                // Incrementar rate limit mesmo em caso de erro
                increment_rate_limit();
            }
        }
    }

    if ($is_mail_sent) {
        // Em desenvolvimento, salvar email em arquivo ao invés de enviar
        if (defined('APP_ENV') && APP_ENV === 'development') {
            $devEmailDir = __DIR__ . '/dev-emails';
            if (!is_dir($devEmailDir)) {
                mkdir($devEmailDir, 0755, true);
            }
            
            $emailFile = $devEmailDir . '/email_' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.html';
            $emailContent = "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Email de Desenvolvimento</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .email-container { background: white; padding: 20px; border-radius: 5px; max-width: 600px; margin: 0 auto; }
        .header { background: #ccb7bc; color: white; padding: 15px; border-radius: 5px 5px 0 0; margin: -20px -20px 20px -20px; }
        .meta { background: #f9f9f9; padding: 10px; border-left: 3px solid #ccb7bc; margin: 10px 0; }
    </style>
</head>
<body>
    <div class='email-container'>
        <div class='header'>
            <h2>📧 Email de Desenvolvimento (não enviado)</h2>
            <p>Este email foi salvo em arquivo porque APP_ENV=development</p>
        </div>
        <div class='meta'>
            <strong>De:</strong> contato@minhamimo.com.br<br>
            <strong>Para:</strong> atendimento@minhamimo.com.br<br>
            <strong>Assunto:</strong> Formulário site - {$assunto}<br>
            <strong>Data:</strong> " . date('d/m/Y H:i:s') . "<br>
            <strong>Arquivo:</strong> {$emailFile}
        </div>
        <hr>
        {$mensagemHTML}
    </div>
</body>
</html>";
            
            file_put_contents($emailFile, $emailContent);
            error_log("DEV: Email salvo em: {$emailFile}");
            
            // Incrementar rate limit
            increment_rate_limit();
            
        } else {
            // Produção: enviar email real via SMTP
            $mail = new PHPMailer(true);
            $email_error = null;

            try {
                $mail->SMTPDebug = 0;
                $mail->Debugoutput = function($str, $level) use (&$email_error) {
                    if ($level > 0) {
                        error_log("PHPMailer Debug: $str");
                        $email_error .= $str . "\n";
                    }
                };
                
                $mail->isSMTP();
                $mail->Host = 'smtp.mailgun.org';
                $mail->SMTPAuth = true;
                $mail->Username = $MailGunUsername;
                $mail->Password = $MailGunPassword;
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                $mail->Timeout = 10;

                $mail->setFrom('contato@minhamimo.com.br', 'Estética MIMO');
                $mail->addAddress('atendimento@minhamimo.com.br', 'Atendimento');
                $mail->addReplyTo($emailremetente ?? 'contato@minhamimo.com.br', $nomeremetente ?? 'Estética MIMO');

                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';
                $mail->Subject = 'Formulário site - ' . $assunto;
                $mail->Body = $mensagemHTML;
                $mail->AltBody = strip_tags($mensagemHTML);
                
                $mail->send();
                
                // Incrementar rate limit apenas após envio bem-sucedido
                increment_rate_limit();

            } catch (Exception $e) {
                // Log do erro detalhado
                $error_message = 'Email send failed: ' . $mail->ErrorInfo;
                if ($e->getMessage()) {
                    $error_message .= ' | Exception: ' . $e->getMessage();
                }
                error_log($error_message);
                
                $is_mail_sent = false;
            }
        }
    }
}
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta name="generator" content="Mimo Site v<?php echo APP_VERSION; ?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Victor Penter">
    
    <!-- Meta tags no-cache como fallback (caso headers HTTP não funcionem) -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    
    <?php
    // SEO Meta Tags
    $pageTitle = 'Mimo - Centro de Beleza em São Paulo | Estética, Salão, Cílios e Design';
    $pageDescription = 'Centro de beleza e estética em São Paulo oferecendo serviços de qualidade: estética facial, estética corporal, salão, esmalteria, micropigmentação e cílios. Preços acessíveis. Você merece esse mimo!';
    $pageKeywords = 'estética são paulo, centro de beleza vila madalena, salão de beleza, estética facial, estética corporal, esmalteria, micropigmentação, cílios e design, alongamento de unhas, design de sobrancelha';
    
    echo generate_seo_meta_tags($pageTitle, $pageDescription, $pageKeywords);
    echo generate_canonical_url();
    echo generate_open_graph_tags($pageTitle, $pageDescription, 'img/bgheader.jpg');
    echo generate_twitter_cards($pageTitle, $pageDescription, 'img/bgheader.jpg');
    ?>

    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3a505a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Mimo">
    <link rel="apple-touch-icon" href="/favicon/apple-touch-icon.png">
    
    <!-- Resource Hints for Performance -->
    <!-- DNS Prefetch para domínios externos -->
    <!-- CRITICAL: Preload LCP images FIRST (highest priority) - must be before any other resources -->
    <?php
    // Preload mobile header (LCP element no mobile) - prefer AVIF/WebP
    // CRITICAL: Esta é a imagem LCP no mobile, precisa ser carregada o mais rápido possível
    // IMPORTANTE: Preload deve vir ANTES de qualquer outro recurso para máxima prioridade
    if (file_exists(__DIR__ . '/img/header_dezembro_mobile.avif')) {
        echo '<link rel="preload" href="/img/header_dezembro_mobile.avif" as="image" type="image/avif" fetchpriority="high" media="(max-width: 750px)">';
    } elseif (file_exists(__DIR__ . '/img/header_dezembro_mobile.webp')) {
        echo '<link rel="preload" href="/img/header_dezembro_mobile.webp" as="image" type="image/webp" fetchpriority="high" media="(max-width: 750px)">';
    } elseif (file_exists(__DIR__ . '/img/header_dezembro_mobile.png')) {
        echo '<link rel="preload" href="/img/header_dezembro_mobile.png" as="image" fetchpriority="high" media="(max-width: 750px)">';
    }
    
    // Preload desktop header (LCP element no desktop) - prefer AVIF/WebP
    if (file_exists(__DIR__ . '/img/bgheader.avif')) {
        echo '<link rel="preload" href="/img/bgheader.avif" as="image" type="image/avif" fetchpriority="high" media="(min-width: 751px)">';
    } elseif (file_exists(__DIR__ . '/img/bgheader.webp')) {
        echo '<link rel="preload" href="/img/bgheader.webp" as="image" type="image/webp" fetchpriority="high" media="(min-width: 751px)">';
    } elseif (file_exists(__DIR__ . '/img/bgheader.jpg')) {
        echo '<link rel="preload" href="/img/bgheader.jpg" as="image" fetchpriority="high" media="(min-width: 751px)">';
    }
    
    // Preload hero image (mimo5.png) - above the fold, não lazy
    if (file_exists(__DIR__ . '/img/mimo5.avif')) {
        echo '<link rel="preload" href="/img/mimo5.avif" as="image" type="image/avif" fetchpriority="high">';
    } elseif (file_exists(__DIR__ . '/img/mimo5.webp')) {
        echo '<link rel="preload" href="/img/mimo5.webp" as="image" type="image/webp" fetchpriority="high">';
    } elseif (file_exists(__DIR__ . '/img/mimo5.png')) {
        echo '<link rel="preload" href="/img/mimo5.png" as="image" fetchpriority="high">';
    }
    ?>
    
    <!-- CRITICAL: Resource hints optimization (Phase 9.4) -->
    <!-- Preconnect for critical resources (faster than dns-prefetch) - must come first -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- CRITICAL: Desktop optimization - preconnect for Google user images (240ms LCP savings) -->
    <link rel="preconnect" href="https://lh3.googleusercontent.com" crossorigin>
    <!-- Preconnect to own domain for faster asset loading -->
    <link rel="preconnect" href="https://minhamimo.com.br" crossorigin>
    
    <!-- DNS prefetch for non-critical third-party resources (fallback for preconnect) -->
    <link rel="dns-prefetch" href="https://stackpath.bootstrapcdn.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://www.google-analytics.com">
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">
    
    <!-- Prefetch below-fold resources for faster subsequent page loads -->
    <link rel="prefetch" href="<?php echo get_css_asset('servicos.css'); ?>" as="style">
    <link rel="prefetch" href="<?php echo get_js_asset('main.js'); ?>" as="script">
    
    <!-- Preload fontes críticas -->
    <link rel="preload" href="/Akrobat-Regular.woff" as="font" type="font/woff" crossorigin>
    
    <!-- Preconnect para domínio próprio (imagens e fontes) -->
    <link rel="preconnect" href="https://minhamimo.com.br" crossorigin>
    
    <!-- Critical CSS (Above the fold) -->
    <?php include 'inc/critical-css.php'; ?>

    <!-- Script loader for deferred CSS - Must come before deferred resources -->
    <!-- CRITICAL: loadCSS deve ser inline e síncrono para funcionar antes do CSS defer -->
    <script>
    /*! loadCSS. [c]2017 Filament Group, Inc. MIT License */
    (function(w){"use strict";if(!w.loadCSS){w.loadCSS=function(href,media,rel){var ss=w.document.createElement("link");var ref;if(rel){ref=w.document.getElementsByTagName(rel)[0];}else{var refs=w.document.getElementsByTagName("script");ref=refs[refs.length-1];}ss.rel="stylesheet";ss.href=href;ss.media="only x";function onloadcssdefined(cb){for(var i=0;i<w.document.styleSheets.length;i++){if(w.document.styleSheets[i].href===ss.href){return cb();}}setTimeout(function(){onloadcssdefined(cb);});}onloadcssdefined(function(){ss.media=media||"all";});if(ref){ref.parentNode.insertBefore(ss,ref.nextSibling);}else{w.document.head.appendChild(ss);}return ss;};}})(this);
    /*! Polyfill for rel="preload" onload - Fallback for browsers that don't support preload onload */
    (function(){var rel=document.querySelector('link[rel="preload"][as="style"]');if(rel&&!rel.onload){rel.onload=function(){this.onload=null;this.rel='stylesheet';};rel.addEventListener('load',rel.onload);}}());
    </script>
    
    <!-- Accessibility fixes CSS - Defer (not critical for FCP) -->
    <script>loadCSS("<?php echo get_css_asset('css/modules/accessibility-fixes.css'); ?>");</script>
    <noscript><link rel="stylesheet" href="<?php echo get_css_asset('css/modules/accessibility-fixes.css'); ?>"></noscript>

    <!-- Fonts - Optimized: Preload CSS + onload (better than loadCSS for fonts) -->
    <!-- Preconnect já configurado acima -->
    <!-- Nunito: fonte principal, usa swap para garantir legibilidade -->
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Nunito:200,300,400&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400&display=swap" rel="stylesheet"></noscript>
    <!-- EB Garamond: fonte decorativa, usa optional para melhor performance -->
    <link rel="preload" href="https://fonts.googleapis.com/css?family=EB+Garamond:400,400i,700i&display=optional" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css?family=EB+Garamond:400,400i,700i&display=optional" rel="stylesheet"></noscript>
    <!-- Akrobat font loaded via CSS @font-face in product.css with font-display: optional -->
    
    <!-- Bootstrap core CSS - Optimized: Preload + onload (better than loadCSS) -->
    <link rel="preload" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"></noscript>

    <!-- CSS Variables agora inline no critical CSS (evita render blocking) -->
    
    <!-- Custom styles for this template - Defer para melhorar FCP (mobile: -4,060ms, desktop: -1,400ms) -->
    <script>loadCSS("<?php echo get_css_asset('product.css'); ?>");</script>
    <noscript><?php echo css_tag('product.css'); ?></noscript>
    
    <!-- Dark Mode Styles - Defer (não crítico para FCP, carrega apenas quando dark mode ativado) -->
    <script>loadCSS("<?php echo get_css_asset('css/modules/dark-mode.css'); ?>");</script>
    <noscript><?php echo css_tag('css/modules/dark-mode.css'); ?></noscript>
    
    <!-- Animations - Defer (não crítico para FCP) -->
    <script>loadCSS("<?php echo get_css_asset('css/modules/animations.css'); ?>");</script>
    <noscript><link rel="stylesheet" href="<?php echo get_css_asset('css/modules/animations.css'); ?>"></noscript>
    
    <!-- Mobile UI Improvements - Defer (não crítico para FCP) -->
    <script>loadCSS("<?php echo get_css_asset('css/modules/mobile-ui-improvements.css'); ?>");</script>
    <noscript><link rel="stylesheet" href="<?php echo get_css_asset('css/modules/mobile-ui-improvements.css'); ?>"></noscript>

    <!-- Fix para ícones Font Awesome no footer -->
    <style>
    /* OVERRIDE INDICADORES TESTIMONIALS - Máxima prioridade */
    #testimonialsCarousel .carousel-indicators.testimonials-indicators li {
        width: 12px !important;
        height: 12px !important;
        border-radius: 50% !important;
        background: rgba(58, 80, 90, 0.4) !important;
        border: 2px solid rgba(58, 80, 90, 0.6) !important;
        margin: 0 6px !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2) !important;
        text-indent: 0 !important;
    }
    
    #testimonialsCarousel .carousel-indicators.testimonials-indicators li:hover {
        background: rgba(58, 80, 90, 0.7) !important;
        border-color: rgba(58, 80, 90, 0.9) !important;
        transform: scale(1.3) !important;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3) !important;
    }
    
    #testimonialsCarousel .carousel-indicators.testimonials-indicators li.active {
        background: #3a505a !important;
        border-color: #3a505a !important;
        width: 32px !important;
        height: 12px !important;
        border-radius: 6px !important;
        box-shadow: 0 3px 10px rgba(58, 80, 90, 0.6) !important;
    }
    
    /* OVERRIDE BOTÃO GOOGLE REVIEWS - minimalista */
    .google-reviews-link {
        background: transparent !important;
        border: none !important;
        padding: 0 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
    }
    
    .google-reviews-link:hover {
        background: transparent !important;
        border: none !important;
        opacity: 0.8 !important;
        transform: none !important;
        box-shadow: none !important;
    }
    
    .google-reviews-link span {
        color: #31265b !important; /* Brand dark (blue) text for light mode - direct value */
        font-weight: 500 !important; /* Slightly bolder for better visibility */
    }
    
    /* Light mode: ensure brand dark (blue) color */
    html:not([data-theme="dark"]) .google-reviews-link span,
    [data-theme="light"] .google-reviews-link span {
        color: #31265b !important; /* Brand dark (blue) - direct value */
    }
    
    /* Light mode: icon also uses brand dark (blue) */
    html:not([data-theme="dark"]) .google-reviews-link i,
    html:not([data-theme="dark"]) .google-reviews-link svg,
    [data-theme="light"] .google-reviews-link i,
    [data-theme="light"] .google-reviews-link svg {
        color: #31265b !important; /* Brand dark (blue) - direct value */
    }
    
    /* Dark mode: white text for excellent contrast */
    [data-theme="dark"] .google-reviews-link span {
        color: #ffffff !important; /* White text for excellent contrast in dark mode */
        font-weight: 500 !important; /* Slightly bolder for better visibility */
    }
    
    [data-theme="dark"] .google-reviews-link i,
    [data-theme="dark"] .google-reviews-link svg {
        color: #ffffff !important; /* White icons for excellent contrast in dark mode */
    }
    
    /* Light mode hover: darker brand dark (blue) */
    html:not([data-theme="dark"]) .google-reviews-link:hover span,
    [data-theme="light"] .google-reviews-link:hover span {
        color: var(--color-grey, #4a3d6b) !important; /* Darker brand dark (blue) on hover */
    }
    
    /* GARANTIR QUE IMAGENS FUNCIONEM */
    #testimonialsCarousel .testimonial-avatar {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        width: 80px !important;
        height: 80px !important;
    }
    
    #testimonialsCarousel .testimonial-avatar img {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        object-position: center !important;
    }
    
    /* Controles simples */
    #testimonialsCarousel .carousel-control-prev,
    #testimonialsCarousel .carousel-control-next,
    #testimonialsCarousel .testimonials-control {
        width: 50px !important;
        height: 50px !important;
        background: white !important;
        border-radius: 50% !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        opacity: 1 !important;
    }
    
    /* Esconder ícones SVG do Bootstrap */
    #testimonialsCarousel .carousel-control-prev-icon,
    #testimonialsCarousel .carousel-control-next-icon {
        display: none !important;
    }
    
    /* Footer styles are now centralized in product.css - no need for inline styles here */
    </style>
    <!-- Form CSS - Defer (not critical for FCP) -->
    <script>loadCSS("<?php echo get_css_asset('form/main.css'); ?>");</script>
    <noscript><?php echo css_tag('form/main.css', ['id' => 'form-css']); ?></noscript>

    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png?20211226">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png?20211226">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png?20211226">
    <link rel="manifest" href="/manifest.json">

</head>

<body>

    <?php include "inc/header.php"; ?>
    
    <main id="main-content" role="main">
    
    <div class="position-relative overflow-hidden text-center hero-section" role="banner" aria-label="Hero section">
        <!-- FIX: Mudar LCP de background-image para <img> tag para poder usar fetchpriority -->
        <?php
        // Desktop LCP image
        if (file_exists(__DIR__ . '/img/bgheader.avif')) {
            echo '<picture class="hero-image-desktop d-none d-md-block" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 0;">
                <source srcset="/img/bgheader.avif" type="image/avif">
                <source srcset="/img/bgheader.webp" type="image/webp">
                <img src="/img/bgheader.jpg" alt="Mimo - Centro de Beleza" fetchpriority="high" loading="eager" width="1920" height="1080" style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
            </picture>';
        }
        // Mobile LCP image
        if (file_exists(__DIR__ . '/img/header_dezembro_mobile.avif')) {
            echo '<picture class="hero-image-mobile d-block d-md-none" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 0;">
                <source srcset="/img/header_dezembro_mobile.avif" type="image/avif" media="(max-width: 750px)">
                <source srcset="/img/header_dezembro_mobile.webp" type="image/webp" media="(max-width: 750px)">
                <img src="/img/header_dezembro_mobile.png" alt="Mimo - Centro de Beleza" fetchpriority="high" loading="eager" width="750" height="422" style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
            </picture>';
        }
        ?>
        <!-- Overlay escuro para contraste (oculto em light mode para imagem mais vívida) -->
        <div class="hero-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(61, 61, 61, 0.5); z-index: 1;"></div>
    </div>

    <!-- FIX: Adicionar content-visibility: auto para melhorar performance (seção abaixo da dobra) -->
    <div class="position-relative overflow-hidden pt-3 text-center backgroundGrey" id="about" style="content-visibility: auto; contain-intrinsic-size: 600px;">
        <!--<div class=" container mt-3" >&nbsp;</div>-->
        <div class="container">
            <div class="row mx-auto hero-content-wrapper">
                <div class="col-md-5 mt-lg-5 p-0 fade-in-left hero-image-wrapper visible" id="florzinha">
                    <?php echo picture_webp('img/mimo5.png', 'Mimo - Beleza sem padrão', 'img-fluid img-hover', ['width' => '500', 'height' => '500', 'style' => 'width: 100%; height: auto; max-width: 100%; aspect-ratio: 1 / 1;'], false); ?>
                </div>
                <div class="col-md-7 mx-auto my-5 overflow-hidden">
                <h1 class="display-4 font-weight-normal text-align-right text-uppercase fade-in-right hero-title visible">
                    BELEZA SEM PADRÃO</h1>
                <p class="lead font-weight-normal textDarkGrey Akrobat text-justify fade-in-up hero-subtitle visible">
                    Acreditamos na quebra de padrões que vem se estendendo ao decorrer dos anos, e por isso trabalhamos
                    de maneira única com cada cliente, preservando suas características naturais; Oferecemos atendimento
                    e prestação de serviços de qualidade, com profissionais capacitados e que acreditam no propósito de
                    cuidado e carinho. Entendemos e vivenciamos diariamente o mal do século, doenças como depressão e
                    ansiedade, e buscamos proporcionar aos nossos clientes um ambiente em que eles consigam se sentir
                    queridos e aceitos como são; Focamos na satisfação como parâmetro de melhorias e desenvolvimentos,
                    tomando assim medidas e decisões mais assertivas.
                </p>
                <p class="lead font-weight-normal text-align-right text-uppercase Akrobat hero-tagline">Você
                    merece esse mimo!</p>
                </div>
            </div>
        </div>
    </div>

    <div class="position-relative overflow-hidden text-center backgroundPink">
        <div class="col-md-12 mx-auto py-3">
            <p class="lead font-weight-normal mb-0">TODAS AS ÁREAS DE BELEZA PARA VOCÊ SE SENTIR COMPLETA</p>
        </div>
    </div>
    <!-- FIX: Adicionar content-visibility: auto para melhorar performance (seção abaixo da dobra) -->
    <div id="services" style="content-visibility: auto; contain-intrinsic-size: 800px;">
        <!-- Mobile -->
        <nav class="container nav nav-pills mt-5 mb-5 d-sm-none" id="pills-tab" role="navigation" aria-label="Categorias de serviços">
            <div class="nav-item nav-item-centered">
                <a class="nav-link active" data-toggle="pill" role="button" id="pills-alongamentos-tab"
                    aria-label="Categorias de serviços">
                    CATEGORIAS</a>
            </div>
        </nav>
        <div class="d-block d-sm-none text-center my-3 mobile-categories-container">
            <!-- Grid de categorias mobile (2 colunas) -->
            <div class="mobile-categories-grid">
                <a href="esteticafacial/" class="mobile-category-item fade-in-up">
                    <?php echo picture_webp('img/categoria_facial.png', 'ESTÉTICA FACIAL', 'img-cat img-hover', ['width' => '150', 'height' => '150'], true); ?>
                    <p class="textPink mobile-category-label">ESTÉTICA <br /> FACIAL</p>
                </a>
                <a href="estetica/" class="mobile-category-item fade-in-up">
                    <?php echo picture_webp('img/menu_estetica_corporal.png', 'ESTÉTICA CORPORAL', 'img-cat img-hover', ['width' => '150', 'height' => '150'], true); ?>
                    <p class="textPink mobile-category-label">ESTÉTICA</p>
                </a>
                <a href="esmalteria/" class="mobile-category-item fade-in-up">
                    <?php echo picture_webp('img/MENU-ESMALTERIA.png', 'ESMALTERIA', 'img-cat img-hover', ['width' => '150', 'height' => '150'], true); ?>
                    <p class="textPink mobile-category-label">ESMALTERIA</p>
                </a>
                <a href="salao/" class="mobile-category-item fade-in-up">
                    <?php echo picture_webp('img/menu_salao.png', 'SALÃO', 'img-cat img-hover', ['width' => '150', 'height' => '150'], true); ?>
                    <p class="textPink mobile-category-label">SALÃO</p>
                </a>
                <a href="micropigmentacao/" class="mobile-category-item fade-in-up">
                    <?php echo picture_webp('img/micro.png', 'MICROPIGMENTAÇÃO', 'img-cat img-hover', ['width' => '150', 'height' => '150'], true); ?>
                    <p class="textPink mobile-category-label">MICROPIGMENTAÇÃO</p>
                </a>
                <a href="cilios/" class="mobile-category-item fade-in-up">
                    <?php echo picture_webp('img/categoria_cilios.png', 'CÍLIOS E DESIGN', 'img-cat img-hover', ['width' => '150', 'height' => '150'], true); ?>
                    <p class="textPink mobile-category-label">CÍLIOS E <br />DESIGN</p>
                </a>
            </div>
            <!-- Botão VAGAS separado (full-width) -->
            <a href="vagas.php" class="mobile-vagas-button fade-in-up">
                <div class="mobile-vagas-card card-hover">
                    <i data-lucide="briefcase" class="lucide-icon-briefcase"></i>
                    <p class="mobile-vagas-title">VAGAS</p>
                    <p class="mobile-vagas-subtitle">Trabalhe Conosco</p>
                </div>
            </a>
        </div>

        <!-- Desktop -->
        <div class="d-none d-sm-block">
            <div class="sessoes container fade-in-up">
                <div class="content">
                    <div class="content-overlay"></div>
                    <?php echo picture_webp('img/esmalteria.png', 'ESMALTERIA', 'content-image', ['style' => 'min-width: 500px;', 'width' => '500', 'height' => '400'], true); ?>
                    <div class="content-details fadeIn-top">
                        <h2>ESMALTERIA</h2>
                        <a class="btn btnSeeMore" href="esmalteria/" aria-label="Ver procedimentos de esmalteria">PROCEDIMENTOS</a>
                    </div>
                </div>
            </div>

            <div class="sessoes container fade-in-up">
                <div class="content">
                    <div class="content-overlay"></div>
                    <?php echo picture_webp('img/corporal.png', 'ESTÉTICA', 'content-image', ['style' => 'min-width: 500px;', 'width' => '500', 'height' => '400'], true); ?>
                    <div class="content-details fadeIn-top">
                        <h2>ESTÉTICA</h2>
                        <a class="btn btnSeeMore" href="estetica/" aria-label="Ver procedimentos de estética corporal">PROCEDIMENTOS</a>
                    </div>
                </div>
            </div>

            <div class="sessoes container fade-in-up">
                <div class="content">
                    <div class="content-overlay"></div>
                    <?php echo picture_webp('img/salao.png', 'SALÃO', 'content-image', ['style' => 'min-width:600px;', 'width' => '600', 'height' => '400'], true); ?>
                    <div class="content-details fadeIn-top">
                        <h2>SALÃO</h2>
                        <a class="btn btnSeeMore" href="salao/" aria-label="Ver procedimentos de salão">PROCEDIMENTOS</a>
                    </div>
                </div>
            </div>

            <div class="sessoes container fade-in-up">
                <div class="content">
                    <div class="content-overlay"></div>
                    <?php echo picture_webp('img/facial.png', 'ESTÉTICA FACIAL', 'content-image', ['style' => 'min-width: 500px;', 'width' => '500', 'height' => '400'], true); ?>
                    <div class="content-details fadeIn-top">
                        <h2>ESTÉTICA FACIAL</h2>
                        <a class="btn btnSeeMore" href="esteticafacial/" aria-label="Ver procedimentos de estética facial">PROCEDIMENTOS</a>
                    </div>
                </div>
            </div>

            <div class="sessoes container fade-in-up">
                <div class="content">
                    <div class="content-overlay"></div>
                    <?php echo picture_webp('img/cilios.png', 'CÍLIOS E DESIGN', 'content-image', ['style' => 'min-width: 500px;', 'width' => '500', 'height' => '400'], true); ?>
                    <div class="content-details fadeIn-top">
                        <h2>CÍLIOS E DESIGN</h2>
                        <a class="btn btnSeeMore" href="cilios/" aria-label="Ver procedimentos de cílios e design">PROCEDIMENTOS</a>
                    </div>
                </div>
            </div>

            <div class="sessoes container fade-in-up">
                <div class="content">
                    <div class="content-overlay"></div>
                    <?php echo picture_webp('img/micro.png', 'MICROPIGMENTAÇÃO', 'content-image', ['style' => 'min-width:600px;', 'width' => '600', 'height' => '400'], true); ?>
                    <div class="content-details fadeIn-top">
                        <h2>MICROPIGMENTAÇÃO</h2>
                        <a class="btn btnSeeMore" href="micropigmentacao/" aria-label="Ver procedimentos de micropigmentação">PROCEDIMENTOS</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Depoimentos -->
    <!-- FIX: Adicionar content-visibility: auto para melhorar performance (seção abaixo da dobra) -->
    <div class="position-relative overflow-hidden text-center backgroundGrey testimonials-section" style="content-visibility: auto; contain-intrinsic-size: 600px; padding-top: 1rem; padding-left: 1rem; padding-right: 1rem; padding-bottom: 0.5rem;">
        <div class="col-md-12 p-lg-12 mx-auto testimonials-container">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-center m-auto">
                        <h2 class="testimonials-title">O QUE NOSSAS CLIENTES DIZEM</h2>
                        <?php
                        // Buscar reviews do Google (4 e 5 estrelas, ordenados por qualidade)
                        $googleReviews = [];
                        if (defined('GOOGLE_PLACES_API_KEY') && !empty(GOOGLE_PLACES_API_KEY) && 
                            defined('GOOGLE_PLACE_ID') && !empty(GOOGLE_PLACE_ID)) {
                            // Buscar mais reviews para ter opções de randomização (top 50 melhores)
                            // Depois randomizamos e pegamos 10 aleatórios para variar a cada carregamento
                            $fetchedReviews = get_google_reviews(GOOGLE_PLACE_ID, GOOGLE_PLACES_API_KEY, 4, 50);
                            $googleReviews = is_array($fetchedReviews) ? $fetchedReviews : [];
                        }
                        
                        // Normalizar campos de foto nos reviews do Google (scraper usa profile_picture)
                        foreach ($googleReviews as &$review) {
                            // Mapear profile_picture para profile_photo se não existir
                            if (isset($review['profile_picture']) && !isset($review['profile_photo'])) {
                                $review['profile_photo'] = $review['profile_picture'];
                            }
                            // Garantir que has_photo está definido
                            if (!isset($review['has_photo'])) {
                                $review['has_photo'] = !empty($review['profile_photo']) || !empty($review['profile_picture']);
                            }
                        }
                        unset($review); // Limpar referência
                        
                        // Se tiver menos de 10 reviews do Google, complementar com reviews manuais
                        if (count($googleReviews) < 10) {
                            $manualReviews = get_manual_reviews(4, 10);
                            // Converter formato manual para formato compatível
                            foreach ($manualReviews as &$review) {
                                if (isset($review['date'])) {
                                    $review['time'] = strtotime($review['date']);
                                }
                                // Normalizar campos de foto (scraper usa profile_picture)
                                if (isset($review['profile_picture']) && !isset($review['profile_photo'])) {
                                    $review['profile_photo'] = $review['profile_picture'];
                                }
                                if (!isset($review['profile_photo'])) {
                                    $review['profile_photo'] = null;
                                }
                                // Garantir que has_photo está definido
                                if (!isset($review['has_photo'])) {
                                    $review['has_photo'] = !empty($review['profile_photo']) || !empty($review['profile_picture']);
                                }
                            }
                            
                            // Combinar reviews do Google com manuais, evitando duplicatas
                            $existingAuthors = array_map(function($r) { return mb_strtolower($r['author']); }, $googleReviews);
                            foreach ($manualReviews as $manualReview) {
                                if (count($googleReviews) >= 20) break; // Coletar mais para depois ordenar
                                $authorLower = mb_strtolower($manualReview['author']);
                                if (!in_array($authorLower, $existingAuthors)) {
                                    // Adicionar campos necessários para ordenação
                                    if (!isset($manualReview['text_length'])) {
                                        $manualReview['text_length'] = mb_strlen($manualReview['text'] ?? '');
                                    }
                                    if (!isset($manualReview['has_photo'])) {
                                        $manualReview['has_photo'] = !empty($manualReview['profile_photo']);
                                    }
                                    $googleReviews[] = $manualReview;
                                    $existingAuthors[] = $authorLower;
                                }
                            }
                            
                            // Filtrar reviews indesejados
                            $googleReviews = array_filter($googleReviews, function($review) {
                                // Remover reviews excluídos (conflito de interesse)
                                if (review_should_be_excluded($review)) {
                                    return false;
                                }
                                // Remover reviews que mencionam COVID
                                if (review_mentions_covid($review)) {
                                    return false;
                                }
                                return true;
                            });
                            
                            // Reordenar todos os reviews combinados com a mesma lógica de prioridade
                            // 1. Reviews com foto de perfil (foto dá credibilidade) - MAIS IMPORTANTE
                            // 2. Tamanho médio de texto (100-500 chars - nem muito curto, nem muito longo)
                            // 3. Rating (5 estrelas antes de 4)
                            // 4. Reviews dos últimos 2 anos primeiro (mas não limitar apenas a isso)
                            // 5. Mais recentes primeiro
                            usort($googleReviews, function($a, $b) {
                                // Prioridade 1: Reviews com foto REAL de perfil (foto dá credibilidade) - MAIS IMPORTANTE
                                // Não priorizar placeholders (apenas inicial do nome)
                                $aHasRealPhoto = review_has_real_photo($a);
                                $bHasRealPhoto = review_has_real_photo($b);
                                if ($aHasRealPhoto != $bHasRealPhoto) {
                                    return $bHasRealPhoto ? 1 : -1; // Com foto REAL primeiro
                                }
                                
                                // Prioridade 2: Tamanho médio de texto (100-500 chars é ideal - credibilidade)
                                $aLength = isset($a['text_length']) ? $a['text_length'] : mb_strlen(get_review_text($a));
                                $bLength = isset($b['text_length']) ? $b['text_length'] : mb_strlen(get_review_text($b));
                                
                                // Função para calcular score de tamanho (tamanho médio = melhor)
                                $getSizeScore = function($length) {
                                    if ($length >= 100 && $length <= 500) {
                                        return 3; // Tamanho médio ideal - melhor score
                                    } elseif ($length >= 50 && $length < 100) {
                                        return 2; // Curto mas aceitável
                                    } elseif ($length > 500 && $length <= 1000) {
                                        return 2; // Longo mas ainda bom
                                    } elseif ($length > 1000) {
                                        return 1; // Muito longo
                                    } else {
                                        return 0; // Muito curto
                                    }
                                };
                                
                                $aSizeScore = $getSizeScore($aLength);
                                $bSizeScore = $getSizeScore($bLength);
                                if ($aSizeScore != $bSizeScore) {
                                    return $bSizeScore - $aSizeScore; // Melhor score primeiro
                                }
                                
                                // Se mesmo score de tamanho, preferir tamanho médio dentro do range
                                if ($aSizeScore == 3 && $bSizeScore == 3) {
                                    // Dentro do range ideal, preferir mais próximo de 300 chars (meio do range)
                                    $aDistance = abs($aLength - 300);
                                    $bDistance = abs($bLength - 300);
                                    if ($aDistance != $bDistance) {
                                        return $aDistance - $bDistance; // Mais próximo de 300 primeiro
                                    }
                                }
                                
                                // Prioridade 3: Rating (5 estrelas antes de 4)
                                if ($a['rating'] != $b['rating']) {
                                    return $b['rating'] - $a['rating'];
                                }
                                
                                // Prioridade 4: Reviews dos últimos 2 anos primeiro (mas não limitar)
                                $aRecent = review_is_recent($a);
                                $bRecent = review_is_recent($b);
                                if ($aRecent != $bRecent) {
                                    return $bRecent ? 1 : -1; // Recentes primeiro
                                }
                                
                                // Prioridade 5: Mais recentes primeiro
                                $aTime = isset($a['time']) ? $a['time'] : 0;
                                $bTime = isset($b['time']) ? $b['time'] : 0;
                                return $bTime - $aTime;
                            });
                        }
                        
                        // Se ainda não tiver reviews, usar apenas manuais
                        if (empty($googleReviews)) {
                            $googleReviews = get_manual_reviews(4, 10);
                            // Converter formato manual para formato compatível
                            foreach ($googleReviews as &$review) {
                                if (isset($review['date'])) {
                                    $review['time'] = strtotime($review['date']);
                                }
                                // Normalizar campos de foto (scraper usa profile_picture)
                                if (isset($review['profile_picture']) && !isset($review['profile_photo'])) {
                                    $review['profile_photo'] = $review['profile_picture'];
                                }
                                if (!isset($review['profile_photo'])) {
                                    $review['profile_photo'] = null;
                                }
                                // Garantir que has_photo está definido
                                if (!isset($review['has_photo'])) {
                                    $review['has_photo'] = !empty($review['profile_photo']) || !empty($review['profile_picture']);
                                }
                                if (!isset($review['text_length'])) {
                                    $review['text_length'] = mb_strlen(get_review_text($review));
                                }
                                if (!isset($review['has_photo'])) {
                                    $review['has_photo'] = false;
                                }
                            }
                            
                            // Filtrar reviews indesejados
                            $googleReviews = array_filter($googleReviews, function($review) {
                                // Remover reviews excluídos (conflito de interesse)
                                if (review_should_be_excluded($review)) {
                                    return false;
                                }
                                // Remover reviews que mencionam COVID
                                if (review_mentions_covid($review)) {
                                    return false;
                                }
                                return true;
                            });
                            
                            // Ordenar reviews manuais também
                            usort($googleReviews, function($a, $b) {
                                // Prioridade 1: Reviews dos últimos 2 anos primeiro (mas não limitar)
                                $aRecent = review_is_recent($a);
                                $bRecent = review_is_recent($b);
                                if ($aRecent != $bRecent) {
                                    return $bRecent ? 1 : -1; // Recentes primeiro
                                }
                                
                                // Prioridade 2: Rating (5 estrelas antes de 4)
                                if ($a['rating'] != $b['rating']) {
                                    return $b['rating'] - $a['rating'];
                                }
                                
                                // Prioridade 3: Reviews com foto
                                $aHasPhoto = isset($a['has_photo']) ? $a['has_photo'] : (!empty($a['profile_photo']) || !empty($a['profile_picture']));
                                $bHasPhoto = isset($b['has_photo']) ? $b['has_photo'] : (!empty($b['profile_photo']) || !empty($b['profile_picture']));
                                if ($aHasPhoto != $bHasPhoto) {
                                    return $bHasPhoto ? 1 : -1;
                                }
                                
                                // Prioridade 4: Comprimento do texto (mais longos primeiro)
                                $aLength = isset($a['text_length']) ? $a['text_length'] : mb_strlen(get_review_text($a));
                                $bLength = isset($b['text_length']) ? $b['text_length'] : mb_strlen(get_review_text($b));
                                if ($aLength != $bLength) {
                                    return $bLength - $aLength;
                                }
                                
                                // Prioridade 5: Mais recentes primeiro
                                $aTime = isset($a['time']) ? $a['time'] : 0;
                                $bTime = isset($b['time']) ? $b['time'] : 0;
                                return $bTime - $aTime;
                            });
                        }
                        
                        // Limitar a 10 reviews para exibição
                        $googleReviews = array_slice($googleReviews, 0, 10);
                        
                        // Se ainda não tiver reviews suficientes, usar apenas manuais
                        if (count($googleReviews) < 5) {
                            $manualOnly = get_manual_reviews(4, 10);
                            foreach ($manualOnly as &$review) {
                                if (isset($review['date'])) {
                                    $review['time'] = strtotime($review['date']);
                                }
                                // Normalizar campos de foto (scraper usa profile_picture)
                                if (isset($review['profile_picture']) && !isset($review['profile_photo'])) {
                                    $review['profile_photo'] = $review['profile_picture'];
                                }
                                if (!isset($review['profile_photo'])) {
                                    $review['profile_photo'] = null;
                                }
                                // Garantir que has_photo está definido
                                if (!isset($review['has_photo'])) {
                                    $review['has_photo'] = !empty($review['profile_photo']) || !empty($review['profile_picture']);
                                }
                                if (!isset($review['text_length'])) {
                                    $review['text_length'] = mb_strlen(get_review_text($review));
                                }
                                if (!isset($review['has_photo'])) {
                                    $review['has_photo'] = false;
                                }
                            }
                            
                            // Filtrar e ordenar reviews manuais
                            $manualOnly = array_filter($manualOnly, function($review) {
                                return !review_mentions_covid($review);
                            });
                            
                            usort($manualOnly, function($a, $b) {
                                $aRecent = review_is_recent($a);
                                $bRecent = review_is_recent($b);
                                if ($aRecent != $bRecent) {
                                    return $bRecent ? 1 : -1;
                                }
                                if ($a['rating'] != $b['rating']) {
                                    return $b['rating'] - $a['rating'];
                                }
                                $aHasPhoto = isset($a['has_photo']) ? $a['has_photo'] : (!empty($a['profile_photo']) || !empty($a['profile_picture']));
                                $bHasPhoto = isset($b['has_photo']) ? $b['has_photo'] : (!empty($b['profile_photo']) || !empty($b['profile_picture']));
                                if ($aHasPhoto != $bHasPhoto) {
                                    return $bHasPhoto ? 1 : -1;
                                }
                                $aLength = isset($a['text_length']) ? $a['text_length'] : 0;
                                $bLength = isset($b['text_length']) ? $b['text_length'] : 0;
                                if ($aLength != $bLength) {
                                    return $bLength - $aLength;
                                }
                                $aTime = isset($a['time']) ? $a['time'] : 0;
                                $bTime = isset($b['time']) ? $b['time'] : 0;
                                return $aTime - $bTime;
                            });
                        }
                        
                        // Pegar mais reviews para ter opções de randomização (top 30-50 melhores)
                        // Depois randomizar e pegar 10 aleatórios para variar a cada carregamento
                        $topReviews = array_slice($googleReviews, 0, min(50, count($googleReviews)));
                        
                        // Randomizar entre os melhores para variar a cada carregamento da página
                        // Isso mantém qualidade (sempre entre os melhores) mas adiciona variedade
                        shuffle($topReviews);
                        
                        // Limitar a 10 reviews aleatórios (mas sempre entre os melhores)
                        $googleReviews = array_slice($topReviews, 0, 10);
                        
                        // Garantir normalização final dos campos de foto antes de exibir
                        foreach ($googleReviews as &$review) {
                            // Mapear profile_picture para profile_photo se não existir
                            if (isset($review['profile_picture']) && !isset($review['profile_photo'])) {
                                $review['profile_photo'] = $review['profile_picture'];
                            }
                            // Garantir que has_photo está definido
                            if (!isset($review['has_photo'])) {
                                $review['has_photo'] = !empty($review['profile_photo']) || !empty($review['profile_picture']);
                            }
                        }
                        unset($review); // Limpar referência
                        
                        if (!empty($googleReviews)) {
                            $reviewCount = count($googleReviews);
                            ?>
                            <div id="testimonialsCarousel" class="testimonials-carousel carousel slide carousel-fade" data-ride="carousel" data-interval="7000" role="region" aria-label="Depoimentos de clientes">
                            <!-- Carousel indicators -->
                                <ol class="carousel-indicators testimonials-indicators" role="tablist" aria-label="Indicadores de depoimentos" aria-live="polite">
                                    <?php for ($i = 0; $i < $reviewCount; $i++): ?>
                                        <li data-target="#testimonialsCarousel" data-slide-to="<?php echo $i; ?>" <?php echo $i === 0 ? 'class="active"' : ''; ?> role="tab" id="testimonial-indicator-<?php echo $i; ?>" aria-controls="testimonial-<?php echo $i; ?>" aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>" aria-label="Depoimento <?php echo $i + 1; ?>"></li>
                                    <?php endfor; ?>
                            </ol>
                            <!-- Wrapper for carousel items -->
                                <div class="carousel-inner testimonials-inner">
                                    <?php foreach ($googleReviews as $index => $review): ?>
                                        <div class="carousel-item testimonial-card <?php echo $index === 0 ? 'active' : ''; ?>" id="testimonial-<?php echo $index; ?>" role="tabpanel" aria-labelledby="testimonial-indicator-<?php echo $index; ?>">
                                            <div class="testimonial-content">
                                                <?php
                                                // Foto do perfil (se disponível) ou placeholder
                                                // Garantir normalização uma última vez antes de exibir
                                                if (isset($review['profile_picture']) && empty($review['profile_photo'])) {
                                                    $review['profile_photo'] = $review['profile_picture'];
                                                }
                                                
                                                // Verificar tanto profile_photo quanto profile_picture (formato do scraper)
                                                $photoUrl = !empty($review['profile_photo']) ? $review['profile_photo'] : (!empty($review['profile_picture']) ? $review['profile_picture'] : null);
                                                
                                                if (!empty($photoUrl)) {
                                                    // Usar URL completa da foto do Google
                                                    // FIX: Garantir dimensões explícitas e aspect-ratio para prevenir CLS
                                                    echo '<div class="testimonial-avatar"><img src="' . htmlspecialchars($photoUrl) . '" alt="Foto de ' . htmlspecialchars($review['author']) . '" loading="lazy" width="80" height="80" style="aspect-ratio: 1 / 1; object-fit: cover;" onerror="this.style.display=\'none\'; this.parentElement.classList.add(\'testimonial-avatar-placeholder\'); this.parentElement.textContent=\'' . htmlspecialchars(mb_substr(mb_strtoupper($review['author']), 0, 1)) . '\';"></div>';
                                                } else {
                                                    // Placeholder com inicial do nome
                                                    $initial = mb_substr(mb_strtoupper($review['author']), 0, 1);
                                                    echo '<div class="testimonial-avatar testimonial-avatar-placeholder">' . htmlspecialchars($initial) . '</div>';
                                                }
                                                ?>
                                                <div class="testimonial-author">
                                                    <strong><?php echo htmlspecialchars($review['author']); ?></strong>
                                </div>
                                                <div class="testimonial-rating">
                                                    <?php 
                                                    $rating = isset($review['rating']) ? (int)$review['rating'] : 0;
                                                    if ($rating > 0): 
                                                        // Usar estrelas Unicode para garantir que sempre apareçam
                                                        for ($i = 1; $i <= 5; $i++): 
                                                            if ($i <= $rating): 
                                                                echo '<span class="star-filled">★</span>';
                                                            else: 
                                                                echo '<span class="star-empty">☆</span>';
                                                            endif;
                                                        endfor;
                                                    endif; 
                                                    ?>
                                </div>
                                                <blockquote class="testimonial-text">
                                                    <?php 
                                                    // Usar função que suporta múltiplos formatos (text, comment, description)
                                                    $reviewText = get_review_text($review);
                                                    echo nl2br(htmlspecialchars($reviewText)); 
                                                    ?>
                                                </blockquote>
                                </div>
                                </div>
                                    <?php endforeach; ?>
                            </div>
                            <!-- Carousel controls -->
                                <button type="button" class="carousel-control-prev testimonials-control" data-target="#testimonialsCarousel" data-slide="prev" aria-label="Anterior depoimento" aria-controls="testimonialsCarousel">
                                    <span class="carousel-arrow" aria-hidden="true">‹</span>
                                    <span class="sr-only">Anterior</span>
                                </button>
                                <button type="button" class="carousel-control-next testimonials-control" data-target="#testimonialsCarousel" data-slide="next" aria-label="Próximo depoimento" aria-controls="testimonialsCarousel">
                                    <span class="carousel-arrow" aria-hidden="true">›</span>
                                    <span class="sr-only">Próximo</span>
                                </button>
                        </div>
                            <?php
                            // Link para reviews do Google (se tiver Place ID configurado)
                            // Movido para fora do container do carousel para evitar sobreposição com indicadores
                            if (defined('GOOGLE_PLACE_ID') && !empty(GOOGLE_PLACE_ID)) {
                                $googleMapsUrl = 'https://www.google.com/maps/place/?q=place_id:' . urlencode(GOOGLE_PLACE_ID);
                                ?>
                                <div class="text-center mt-4 mb-4 google-reviews-container">
                                    <a href="<?php echo htmlspecialchars($googleMapsUrl); ?>" target="_blank" rel="noopener noreferrer" class="google-reviews-link">
                                        <i data-lucide="chrome" class="google-reviews-icon"></i>
                                        <span class="google-reviews-text">Ver todos os reviews no Google</span>
                                    </a>
                    </div>
                                <?php
                            }
                            ?>
                            <?php
                        } else {
                            // Fallback se não houver reviews
                            ?>
                            <p class="textDarkGrey">Aguardando depoimentos...</p>
                            <?php
                        }
                        ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'inc/footer.php'; ?>

    <?php
    // Schema.org Structured Data - LocalBusiness
    echo generate_local_business_schema([
        'geo' => [
            'latitude' => '-23.5505',
            'longitude' => '-46.6333'
        ]
    ]);
    
    // Google Reviews Schema
    // Opção 1: Se tiver API key configurada, usa reviews do Google
    if (defined('GOOGLE_PLACES_API_KEY') && !empty(GOOGLE_PLACES_API_KEY) && 
        defined('GOOGLE_PLACE_ID') && !empty(GOOGLE_PLACE_ID)) {
        $reviews = get_google_reviews(GOOGLE_PLACE_ID, GOOGLE_PLACES_API_KEY, 4, 10);
        
        if ($reviews && !empty($reviews)) {
            // Calcular rating médio
            $totalRating = 0;
            foreach ($reviews as $review) {
                $totalRating += $review['rating'];
            }
            $avgRating = $totalRating / count($reviews);
            
            // AggregateRating Schema
            echo generate_aggregate_rating_schema($avgRating, count($reviews));
            
            // Individual Review Schemas (primeiros 3)
            foreach (array_slice($reviews, 0, 3) as $review) {
                echo generate_review_schema($review, 'Mimo');
            }
        }
    } else {
        // Opção 2: Usa reviews manuais (grátis, sem API)
        $reviews = get_manual_reviews(4, 10);
        
        if (!empty($reviews)) {
            // Calcular rating médio
            $totalRating = 0;
            foreach ($reviews as $review) {
                $totalRating += $review['rating'];
            }
            $avgRating = $totalRating / count($reviews);
            
            // AggregateRating Schema
            echo generate_manual_aggregate_rating_schema($avgRating, count($reviews));
            
            // Individual Review Schemas (primeiros 3)
            foreach (array_slice($reviews, 0, 3) as $review) {
                echo generate_manual_review_schema($review, 'Mimo');
            }
        }
    }
    ?>


    <!-- Bootstrap core JavaScript
================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- FIX: jQuery carregado com defer para não bloquear render (Bootstrap funciona com defer) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous" defer></script>
    <script>
    // FIX: Fallback para jQuery local se CDN falhar (executado após DOM ready)
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof jQuery === 'undefined') {
            var script = document.createElement('script');
            script.src = 'bootstrap/jquery/dist/jquery.slim.min.js';
            script.defer = true;
            document.head.appendChild(script);
            // Aguardar jQuery carregar antes de inicializar Bootstrap
            script.onload = function() {
                // Bootstrap será inicializado quando seus scripts carregarem
            };
        }
    });
    </script>
    <script src="bootstrap/popper.js/dist/umd/popper.min.js" defer></script>
    <!-- Bootstrap JS - Usar completo temporariamente até corrigir build custom -->
    <script src="bootstrap/bootstrap/dist/js/bootstrap.min.js" defer></script>
    <?php echo js_tag('form/main.js', ['defer' => true]); ?>
    <?php echo js_tag('js/bc-swipe.js', ['defer' => true]); ?>
    <?php echo js_tag('main.js', ['defer' => true]); ?>
    <?php echo js_tag('js/dark-mode.js', ['defer' => true]); // FIX: Mudado para defer ?>
    <?php echo js_tag('js/animations.js', ['defer' => true]); ?>
    <!-- Fallback navbar scroll handler - ensures animation works even if main.js fails -->
    <script>
    (function() {
        function handleNavbarScroll() {
            var navbar = document.querySelector('.navbar');
            if (!navbar) return;
            
            var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
            var shouldBeCompressed = scrollTop >= 20;
            
            if (shouldBeCompressed && !navbar.classList.contains('compressed')) {
                navbar.classList.add('compressed');
            } else if (!shouldBeCompressed && navbar.classList.contains('compressed')) {
                navbar.classList.remove('compressed');
            }
        }
        
        // Run on scroll
        window.addEventListener('scroll', handleNavbarScroll, { passive: true });
        
        // Run on load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', handleNavbarScroll);
        } else {
            handleNavbarScroll();
        }
        
        // Polling fallback
        var lastScroll = -1;
        setInterval(function() {
            var currentScroll = window.pageYOffset || document.documentElement.scrollTop || 0;
            if (Math.abs(currentScroll - lastScroll) > 1) {
                lastScroll = currentScroll;
                handleNavbarScroll();
            }
        }, 50);
    })();
    </script>
    <!-- jquery.touchswipe removido - bc-swipe.js já fornece funcionalidade de swipe -->
    
    <!-- Lucide Icons - Defer para não bloquear render (movido do <head> para melhorar FCP) -->
    <!-- FIX: Use jsdelivr CDN (already allowed in CSP) with correct path -->
    <script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.min.js" defer></script>
    
    <!-- Inicializar Lucide Icons após carregar -->
    <script>
        // FIX: Wait for window load to ensure all scripts have executed
        (function() {
            function initLucideIcons() {
                if (typeof lucide !== "undefined") {
                    // Clear any incorrectly processed icons first
                    document.querySelectorAll('[data-lucide]').forEach(function(el) {
                        // If icon has path/rect but no SVG wrapper, clear it
                        if (el.querySelector('path, rect') && !el.querySelector('svg')) {
                            el.innerHTML = '';
                        }
                    });
                    // Now create icons properly
                    lucide.createIcons();
                }
            }
            
            // Wait for window load to ensure defer scripts have executed
            if (document.readyState === "complete") {
                // Page already loaded
                setTimeout(initLucideIcons, 100);
            } else {
                window.addEventListener('load', function() {
                    setTimeout(initLucideIcons, 100);
                });
            }
        })();
    </script>
    <script>
        // Wait for DOM and jQuery to be ready (defer ensures scripts load after DOM)
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof jQuery !== 'undefined') {
                // FIX: Usar requestAnimationFrame para evitar forced reflow na inicialização
                requestAnimationFrame(function() {
                    // Inicializar todos os carousels
                    jQuery('.carousel').each(function() {
                    var $carousel = jQuery(this);
                    $carousel.carousel({ 
                        interval: 7000, 
                        pause: 'hover',
                        wrap: true,
                        ride: 'carousel'
                    });
                    
                    // Otimizar transições do carousel de reviews
                    if ($carousel.attr('id') === 'testimonialsCarousel') {
                        // Detectar mobile
                        var isMobile = window.innerWidth <= 768 || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
                        
                        if (isMobile) {
                            // No mobile: desabilitar animações do carousel mas manter funcionalidade
                            // FIX: Usar requestAnimationFrame para evitar forced reflow
                            requestAnimationFrame(function() {
                                $carousel.removeClass('carousel-fade');
                                $carousel.find('.carousel-item').css({
                                    'transition': 'none',
                                    'opacity': '1'
                                });
                            });
                            
                            // Garantir que indicadores funcionem
                            $carousel.find('.carousel-indicators li').on('click', function(e) {
                                e.preventDefault();
                                var slideTo = jQuery(this).data('slide-to');
                                $carousel.carousel(slideTo);
                            });
                            
                            // Garantir que controles funcionem
                            $carousel.find('.carousel-control-prev, .carousel-control-next').on('click', function(e) {
                                e.preventDefault();
                                var direction = jQuery(this).hasClass('carousel-control-prev') ? 'prev' : 'next';
                                $carousel.carousel(direction);
                            });
                        } else {
                            // Desktop: manter animações normais
                            // Pre-carregar imagens do próximo item para evitar delay
                            $carousel.on('slide.bs.carousel', function (e) {
                                var $nextItem = jQuery(e.relatedTarget);
                                var $nextImg = $nextItem.find('img[data-src]');
                                if ($nextImg.length) {
                                    $nextImg.attr('src', $nextImg.attr('data-src'));
                                    $nextImg.removeAttr('data-src');
                                }
                            });
                            
                            // Forçar repaint para suavizar transição
                            // FIX: Usar requestAnimationFrame para evitar forced reflow
                            $carousel.on('slid.bs.carousel', function () {
                                var $active = jQuery(this).find('.carousel-item.active');
                                requestAnimationFrame(function() {
                                    $active.css('transform', 'translateZ(0)');
                                });
                            });
                        }
                    }
                    
                    // Swipe para mobile
                    if (jQuery.fn.swipe) {
                        $carousel.swipe({
                swipe: function (event, direction, distance, duration, fingerCount, fingerData) {
                                if (direction == 'left') $carousel.carousel('next');
                                if (direction == 'right') $carousel.carousel('prev');
                },
                allowPageScroll: "vertical"
            });
        }
                });
            });
        }
    });
    </script>

    <!-- Piwik -->
    <script type="text/javascript">
        var _paq = _paq || [];
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function () {
            var u = "https://cluster-piwik.locaweb.com.br/";
            _paq.push(['setTrackerUrl', u + 'piwik.php']);
            _paq.push(['setSiteId', 28515]);
            var d = document, g = d.createElement('script'), s = d.getElementsByTagName('script')[0];
            g.type = 'text/javascript'; g.async = true; g.defer = true; g.src = u + 'piwik.js'; s.parentNode.insertBefore(g, s);
        })();
    </script>
    <!-- End Piwik Code -->

    <!-- Tidio chat removido - script retorna 404 -->

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
            // Re-apply after a short delay to override any late-loading CSS
            setTimeout(fixFooterNav, 100);
        })();
    </script>
    
    <!-- Footer social icons - Force flex display and consistent margin to ensure consistency -->
    <style>
        .site-footer .footer-social,
        footer .footer-social,
        .footer-social-col .footer-social {
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
        }
        
        /* Force consistent footer title margin and container width */
        .site-footer .footer-social-col .footer-title,
        footer .footer-social-col .footer-title,
        .footer-social-col .footer-title,
        .site-footer .footer-social-col h2.footer-title,
        footer .footer-social-col h2.footer-title,
        .footer-social-col h2.footer-title {
            margin-bottom: 20px !important;
        }
        
        /* Force consistent footer social container width */
        .site-footer .footer-social,
        footer .footer-social,
        .footer-social-col .footer-social {
            width: 100% !important;
            min-width: 100% !important;
            max-width: 100% !important;
            flex-basis: 100% !important;
            flex-grow: 1 !important;
            flex-shrink: 0 !important;
            box-sizing: border-box !important;
            align-self: stretch !important;
        }
        
        /* Force footer social col to use full width */
        .site-footer .footer-social-col,
        footer .footer-social-col,
        .footer-social-col {
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
    </style>

    <!-- Botão Voltar ao Topo -->
    <?php include 'inc/back-to-top.php'; ?>

    <!-- Service Worker Registration - Optimized -->
    <script>
        if ('serviceWorker' in navigator) {
            // Register immediately, don't wait for load event
            navigator.serviceWorker.register('/sw.js', {
                scope: '/'
            })
                    .then(function(registration) {
                // Check for updates
                registration.addEventListener('updatefound', function() {
                    const newWorker = registration.installing;
                    newWorker.addEventListener('statechange', function() {
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                            // New service worker available
                            console.log('New service worker available');
                        }
                    });
                });
                    })
                    .catch(function(error) {
                console.warn('Service Worker registration failed:', error);
            });
        }
    </script>
    
    </main>

</body>

</html>
<?php
// CRITICAL: End HTML minification output buffer (Phase 9.1)
end_html_minify();
?>
