<?php
/**
 * Site Mimo - Página Inicial
 * 
 * Desenvolvido por: Victor Penter
 * Versão: 2.2.5
 * 
 * Este arquivo contém a página principal do site com formulário de contato
 * e seções de serviços, depoimentos e informações de contato.
 */

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

    <!-- Resource Hints for Performance -->
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="dns-prefetch" href="https://stackpath.bootstrapcdn.com">
    <link rel="dns-prefetch" href="https://use.fontawesome.com">
    <link rel="dns-prefetch" href="https://www.google-analytics.com">
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="img/bgheader.jpg" as="image">
    <link rel="preload" href="product.css?<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : '20211226'; ?>" as="style">

    <!-- Critical CSS (Above the fold) -->
    <?php include 'inc/critical-css.php'; ?>

    <!-- Fonts with font-display: swap for better performance -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=EB+Garamond:400,400i,700i&display=swap" rel="stylesheet">
    <link href="Akrobat-Regular.woff" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- Bootstrap core CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <?php echo css_tag('product.css'); ?>

    <!-- Fix para ícones Font Awesome no footer -->
    <style>
    /* Garantir que o Font Awesome carregue */
    @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css');
    
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
    <?php echo css_tag('form/main.css', ['id' => 'form-css']); ?>

    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png?20211226">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png?20211226">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png?20211226">
    <link rel="manifest" href="favicon/site.webmanifest">

</head>

<body>

    <?php include "inc/header.php"; ?>




    <div class="position-relative overflow-hidden p-3 text-center bg-header">

    </div>

    <div class="row position-relative overflow-hidden pt-3 text-center backgroundGrey" id="about">
        <!--<div class=" container mt-3" >&nbsp;</div>-->
        <div class="container row mx-auto" style="display: flex; flex-wrap: wrap;">
            <div class="col-md-5 mx-auto mt-lg-5 overflow-hidden" id="florzinha">
                <?php echo picture_webp('img/mimo5.png', 'foto-flores', 'img-fluid'); ?>
            </div>
            <div class="col-md-7 mx-auto my-5 overflow-hidden">
                <h1 class="display-4 font-weight-normal text-align-right text-uppercase"
                    style="font-size: 43px;color:#fff">
                    BELEZA SEM PADRÃO</h1>
                <p class="lead font-weight-normal textDarkGrey Akrobat text-justify" style="margin-bottom:0">
                    Acreditamos na quebra de padrões que vem se estendendo ao decorrer dos anos, e por isso trabalhamos
                    de maneira única com cada cliente, preservando suas características naturais; Oferecemos atendimento
                    e prestação de serviços de qualidade, com profissionais capacitados e que acreditam no propósito de
                    cuidado e carinho. Entendemos e vivenciamos diariamente o mal do século, doenças como depressão e
                    ansiedade, e buscamos proporcionar aos nossos clientes um ambiente em que eles consigam se sentir
                    queridos e aceitos como são; Focamos na satisfação como parâmetro de melhorias e desenvolvimentos,
                    tomando assim medidas e decisões mais assertivas.
                </p>
                <p class="lead font-weight-normal text-align-right text-uppercase Akrobat " style="color: #fff">Você
                    merece esse mimo!</p>
            </div>
        </div>
    </div>

    <div class="position-relative overflow-hidden p-3 text-center backgroundPink">
        <div class="col-md-12 mx-auto my-5">
            <p class="lead text-white font-weight-normal">TODAS AS ÁREAS DE BELEZA PARA VOCÊ SE SENTIR COMPLETA</p>
        </div>
    </div>
    <div id="services">
        <!-- Mobile -->
        <ul class="container nav nav-pills mt-5 mb-5   d-sm-none" id="pills-tab" role="tablist">
            <li class="nav-item" style="margin: auto">
                <a class="nav-link active" data-toggle="pill" role="tab" aria-controls="pills-alongamentos"
                    aria-selected="true">
                    CATEGORIAS</a>
            </li>
        </ul>
        <div class="d-block d-sm-none text-center my-3" style="font-weight: 600; font-size: 12px;">
            <div class="row col-xs-12" style="display: inline-flex; margin-top:30px">
                <a href="esteticafacial/">
                    <div class="col-xs-6" style="margin-right: 50px;">
                        <?php echo picture_webp('img/categoria_facial.png', 'ESTÉTICA FACIAL', 'img-cat'); ?>
                        <p class="textPink" style="margin-top: 15px;">ESTÉTICA <br /> FACIAL</p>
                    </div>
                </a>
                <a href="estetica/">
                    <div class="col-xs-6">
                        <?php echo picture_webp('img/menu_estetica_corporal.png', 'ESTÉTICA CORPORAL', 'img-cat'); ?>
                        <p class="textPink" style="margin-top: 15px;">ESTÉTICA</p>
                    </div>
                </a>
            </div>
            <div class="row col-xs-12" style="display: inline-flex; margin-top:30px">
                <a href="esmalteria/">
                    <div class="col-xs-6" style="margin-right: 50px;">
                        <?php echo picture_webp('img/MENU-ESMALTERIA.png', 'ESMALTERIA', 'img-cat'); ?>
                        <p class="textPink" style="margin-top: 15px;">ESMALTERIA</p>
                    </div>
                </a>
                <a href="salao/">
                    <div class="col-xs-6">
                        <?php echo picture_webp('img/menu_salao.png', 'SALÃO', 'img-cat'); ?>
                        <p class="textPink" style="margin-top: 15px;">SALÃO</p>
                    </div>
                </a>
            </div>
            <div class="row col-xs-12" style="display: inline-flex; margin-top:30px">
                <a href="vagas.php">
                    <div class="col-xs-6" style="margin-right: 50px;">
                        <div style="background: linear-gradient(135deg, rgba(204, 183, 188, 0.8), rgba(58, 80, 90, 0.8)); border-radius: 15px; padding: 40px; text-align: center; height: 200px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                            <i class="fas fa-briefcase" style="font-size: 3rem; color: white; margin-bottom: 15px;"></i>
                            <p class="text-white" style="font-size: 1.2rem; font-weight: 600; margin: 0;">VAGAS</p>
                            <p class="text-white" style="font-size: 0.9rem; margin-top: 5px; opacity: 0.9;">Trabalhe Conosco</p>
                        </div>
                    </div>
                </a>
                <a href="micropigmentacao/">
                    <div class="col-xs-6"
                        style="margin-right: 50px;width: 100px;height: 100px;overflow: hidden;border-radius: 50%;">
                        <?php echo picture_webp('img/micro.png', 'MICROPIGMENTAÇÃO', 'img-cat'); ?>
                    </div>
                    <p class="textPink" style="margin-top: 15px; font-size:10px;width: 100px">MICROPIGMENTAÇÃO</p>
                </a>
                <a href="cilios/">
                    <div class="col-xs-6">
                        <?php echo picture_webp('img/categoria_cilios.png', 'CÍLIOS E DESIGN', 'img-cat'); ?>
                        <p class="textPink" style="margin-top: 15px;">CÍLIOS E <br />DESIGN</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Desktop -->
        <div class="d-none d-sm-block">
            <div class="sessoes container">
                <div class="content">
                    <div class="content-overlay"></div>
                    <?php echo picture_webp('img/esmalteria.png', 'ESMALTERIA', 'content-image', ['style' => 'min-width: 500px;']); ?>
                    <div class="content-details fadeIn-top">
                        <h3>ESMALTERIA</h3>
                        <a class="btn btnSeeMore" href="esmalteria/">PROCEDIMENTOS</a>
                    </div>
                </div>
            </div>

            <div class="sessoes container">
                <div class="content">
                    <div class="content-overlay"></div>
                    <?php echo picture_webp('img/corporal.png', 'ESTÉTICA', 'content-image', ['style' => 'min-width: 500px;']); ?>
                    <div class="content-details fadeIn-top">
                        <h3>ESTÉTICA</h3>
                        <a class="btn btnSeeMore" href="estetica/">PROCEDIMENTOS</a>
                    </div>
                </div>
            </div>

            <div class="sessoes container">
                <div class="content">
                    <div class="content-overlay"></div>
                    <?php echo picture_webp('img/salao.png', 'SALÃO', 'content-image', ['style' => 'min-width:600px;']); ?>
                    <div class="content-details fadeIn-top">
                        <h3>SALÃO</h3>
                        <a class="btn btnSeeMore" href="salao/">PROCEDIMENTOS</a>
                    </div>
                </div>
            </div>

            <div class="sessoes container">
                <div class="content">
                    <div class="content-overlay"></div>
                    <?php echo picture_webp('img/facial.png', 'ESTÉTICA FACIAL', 'content-image', ['style' => 'min-width: 500px;']); ?>
                    <div class="content-details fadeIn-top">
                        <h3>ESTÉTICA FACIAL</h3>
                        <a class="btn btnSeeMore" href="esteticafacial/">PROCEDIMENTOS</a>
                    </div>
                </div>
            </div>

            <div class="sessoes container">
                <div class="content">
                    <div class="content-overlay"></div>
                    <?php echo picture_webp('img/cilios.png', 'CÍLIOS E DESIGN', 'content-image', ['style' => 'min-width: 500px;']); ?>
                    <div class="content-details fadeIn-top">
                        <h3>CÍLIOS E DESIGN</h3>
                        <a class="btn btnSeeMore" href="cilios/">PROCEDIMENTOS</a>
                    </div>
                </div>
            </div>

            <div class="sessoes container">
                <div class="content">
                    <div class="content-overlay"></div>
                    <?php echo picture_webp('img/micro.png', 'MICROPIGMENTAÇÃO', 'content-image', ['style' => 'min-width:600px;']); ?>
                    <div class="content-details fadeIn-top">
                        <h3>MICROPIGMENTAÇÃO </h3>
                        <a class="btn btnSeeMore" href="micropigmentacao/">PROCEDIMENTOS</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Depoimentos -->
    <div class="position-relative overflow-hidden p-3 text-center backgroundGrey" style="margin-bottom: 0; padding-bottom: 0;">
        <div class="col-md-12 p-lg-12 mx-auto" style="margin-top: 3rem; margin-bottom: 0;">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-center m-auto">
                        <h3 style="color:#fff; margin-bottom: 30px;">O QUE NOSSAS CLIENTES DIZEM</h3>
                        <?php
                        // Buscar reviews do Google (4 e 5 estrelas, ordenados por qualidade)
                        $googleReviews = [];
                        if (defined('GOOGLE_PLACES_API_KEY') && !empty(GOOGLE_PLACES_API_KEY) && 
                            defined('GOOGLE_PLACE_ID') && !empty(GOOGLE_PLACE_ID)) {
                            $googleReviews = get_google_reviews(GOOGLE_PLACE_ID, GOOGLE_PLACES_API_KEY, 4, 10);
                        }
                        
                        // Se tiver menos de 10 reviews do Google, complementar com reviews manuais
                        if (count($googleReviews) < 10) {
                            $manualReviews = get_manual_reviews(4, 10);
                            // Converter formato manual para formato compatível
                            foreach ($manualReviews as &$review) {
                                if (isset($review['date'])) {
                                    $review['time'] = strtotime($review['date']);
                                }
                                if (!isset($review['profile_photo'])) {
                                    $review['profile_photo'] = null;
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
                            
                            // Reordenar todos os reviews combinados com a mesma lógica de prioridade
                            // 1. Reviews com foto
                            // 2. Rating (5 estrelas antes de 4)
                            // 3. Comprimento do texto (mais longos primeiro)
                            // 4. Mais antigos primeiro (para ter variedade temporal)
                            usort($googleReviews, function($a, $b) {
                                $aHasPhoto = isset($a['has_photo']) ? $a['has_photo'] : !empty($a['profile_photo']);
                                $bHasPhoto = isset($b['has_photo']) ? $b['has_photo'] : !empty($b['profile_photo']);
                                
                                // Prioridade 1: Reviews com foto
                                if ($aHasPhoto != $bHasPhoto) {
                                    return $bHasPhoto ? 1 : -1;
                                }
                                
                                // Prioridade 2: Rating (5 estrelas antes de 4)
                                if ($a['rating'] != $b['rating']) {
                                    return $b['rating'] - $a['rating'];
                                }
                                
                                // Prioridade 3: Comprimento do texto (mais longos primeiro)
                                $aLength = isset($a['text_length']) ? $a['text_length'] : mb_strlen($a['text'] ?? '');
                                $bLength = isset($b['text_length']) ? $b['text_length'] : mb_strlen($b['text'] ?? '');
                                if ($aLength != $bLength) {
                                    return $bLength - $aLength;
                                }
                                
                                // Prioridade 4: Mais antigos primeiro (para ter variedade temporal)
                                $aTime = isset($a['time']) ? $a['time'] : 0;
                                $bTime = isset($b['time']) ? $b['time'] : 0;
                                return $aTime - $bTime;
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
                                if (!isset($review['profile_photo'])) {
                                    $review['profile_photo'] = null;
                                }
                                if (!isset($review['text_length'])) {
                                    $review['text_length'] = mb_strlen($review['text'] ?? '');
                                }
                                if (!isset($review['has_photo'])) {
                                    $review['has_photo'] = false;
                                }
                            }
                            
                            // Ordenar reviews manuais também
                            usort($googleReviews, function($a, $b) {
                                $aHasPhoto = isset($a['has_photo']) ? $a['has_photo'] : !empty($a['profile_photo']);
                                $bHasPhoto = isset($b['has_photo']) ? $b['has_photo'] : !empty($b['profile_photo']);
                                if ($aHasPhoto != $bHasPhoto) {
                                    return $bHasPhoto ? 1 : -1;
                                }
                                if ($a['rating'] != $b['rating']) {
                                    return $b['rating'] - $a['rating'];
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
                        
                        // Limitar a 10 reviews no máximo (já ordenados)
                        $googleReviews = array_slice($googleReviews, 0, 10);
                        
                        if (!empty($googleReviews)) {
                            $reviewCount = count($googleReviews);
                            ?>
                            <div id="testimonialsCarousel" class="testimonials-carousel carousel slide carousel-fade" data-ride="carousel" data-interval="7000">
                            <!-- Carousel indicators -->
                                <ol class="carousel-indicators testimonials-indicators">
                                    <?php for ($i = 0; $i < $reviewCount; $i++): ?>
                                        <li data-target="#testimonialsCarousel" data-slide-to="<?php echo $i; ?>" <?php echo $i === 0 ? 'class="active"' : ''; ?>></li>
                                    <?php endfor; ?>
                            </ol>
                            <!-- Wrapper for carousel items -->
                                <div class="carousel-inner testimonials-inner">
                                    <?php foreach ($googleReviews as $index => $review): ?>
                                        <div class="carousel-item testimonial-card <?php echo $index === 0 ? 'active' : ''; ?>">
                                            <div class="testimonial-content">
                                                <?php
                                                // Foto do perfil (se disponível) ou placeholder
                                                if (!empty($review['profile_photo'])) {
                                                    echo '<div class="testimonial-avatar"><img src="' . htmlspecialchars($review['profile_photo']) . '" alt="' . htmlspecialchars($review['author']) . '" loading="lazy"></div>';
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
                                                    <?php if (isset($review['rating'])): ?>
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                            <?php if ($i <= $review['rating']): ?>
                                                                <i class="fas fa-star"></i>
                                                            <?php else: ?>
                                                                <i class="far fa-star"></i>
                                                            <?php endif; ?>
                                                        <?php endfor; ?>
                                                    <?php endif; ?>
                                </div>
                                                <blockquote class="testimonial-text">
                                                    <?php echo nl2br(htmlspecialchars($review['text'])); ?>
                                                </blockquote>
                                </div>
                                </div>
                                    <?php endforeach; ?>
                            </div>
                            <!-- Carousel controls -->
                                <a class="carousel-control-prev testimonials-control" href="#testimonialsCarousel" role="button" data-slide="prev">
                                    <i class="fa fa-angle-left" aria-hidden="true"></i>
                                    <span class="sr-only">Anterior</span>
                                </a>
                                <a class="carousel-control-next testimonials-control" href="#testimonialsCarousel" role="button" data-slide="next">
                                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                                    <span class="sr-only">Próximo</span>
                            </a>
                        </div>
                            <?php
                            // Link para reviews do Google (se tiver Place ID configurado)
                            if (defined('GOOGLE_PLACE_ID') && !empty(GOOGLE_PLACE_ID)) {
                                $googleMapsUrl = 'https://www.google.com/maps/place/?q=place_id:' . urlencode(GOOGLE_PLACE_ID);
                                ?>
                                <div class="text-center mt-3 mb-0">
                                    <a href="<?php echo htmlspecialchars($googleMapsUrl); ?>" target="_blank" rel="noopener noreferrer" class="google-reviews-link">
                                        <i class="fab fa-google" style="color: #4285F4; font-size: 0.9rem;"></i>
                                        <span style="color: #fff; font-size: 0.9rem; margin-left: 5px;">Ver todos os reviews no Google</span>
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

    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <!-- Links de Navegação -->
                <div class="col-12 col-md-4 mb-4 mb-md-0">
                    <h5 class="footer-title">Navegação</h5>
                    <nav class="footer-nav-vertical">
                        <a href="/#about" class="footer-link">Sobre</a>
                        <a href="/#services" class="footer-link">Serviços</a>
                        <a href="/contato.php" class="footer-link">Contato</a>
                        <a href="/faq/" class="footer-link">FAQ</a>
                        <a href="/vagas.php" class="footer-link">Trabalhe Conosco</a>
                    </nav>
                </div>

                <!-- Informações de Contato -->
                <div class="col-12 col-md-4 mb-4 mb-md-0">
                    <h5 class="footer-title">Contato</h5>
                    <div class="footer-contact">
                        <p class="footer-contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Rua Heitor Penteado, 626<br>Vila Madalena, São Paulo - SP</span>
                        </p>
                        <p class="footer-contact-item">
                            <i class="fas fa-phone"></i>
                            <span><strong>Telefone:</strong> (11) 3062-8295</span>
                        </p>
                        <p class="footer-contact-item">
                            <i class="fab fa-whatsapp"></i>
                            <span><strong>WhatsApp:</strong> (11) 99478-1012</span>
                        </p>
                                        </div>
                                        </div>

                <!-- Redes Sociais -->
                <div class="col-12 col-md-4">
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
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="bootstrap/jquery/dist/jquery.slim.min.js"><\/script>')</script>
    <script src="bootstrap/popper.js/dist/popper.min.js" defer></script>
    <script src="bootstrap/bootstrap/dist/js/bootstrap.min.js" defer></script>
    <?php echo js_tag('form/main.js', ['defer' => true]); ?>
    <?php echo js_tag('main.js', ['defer' => true]); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.18/jquery.touchSwipe.min.js" defer></script>
    <script>
        // Wait for DOM and jQuery to be ready (defer ensures scripts load after DOM)
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof jQuery !== 'undefined') {
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
                        $carousel.on('slid.bs.carousel', function () {
                            var $active = jQuery(this).find('.carousel-item.active');
                            $active.css('transform', 'translateZ(0)');
                        });
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

    <script src="https://code.tidio.co/ylbfxpiqcmi2on8duid7rpjgqydlrqne.js" defer></script>

    <!-- Botão Voltar ao Topo -->
    <?php include 'inc/back-to-top.php'; ?>

</body>

</html>