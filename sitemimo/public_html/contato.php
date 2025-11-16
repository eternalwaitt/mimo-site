<?php
/**
 * Site Mimo - Página de Contato
 * 
 * Desenvolvido por: Victor Penter
 * Versão: 2.3.5
 * 
 * Página dedicada de contato com mapa, informações e formulário
 */

// Suprimir avisos de depreciação (compatibilidade PHP 8.4)
error_reporting(E_ALL & ~E_DEPRECATED);

// IMPORTANTE: Cache headers DEVEM ser os PRIMEIROS headers enviados
// Carregar configuração primeiro (necessário para ASSET_VERSION)
require_once 'config.php';

// Cache headers para páginas HTML (ANTES de qualquer outro header)
require_once 'inc/cache-headers.php';
set_html_cache_headers();

// Cabeçalhos de segurança (depois dos cache headers)
require_once 'inc/security-headers.php';

// Funções auxiliares de imagem para suporte WebP
require_once 'inc/image-helper.php';

// Helper de ícones Lucide
require_once 'inc/icon-helper.php';

// Helper de SEO
require_once 'inc/seo-helper.php';

// Asset helper para CSS/JS minificados
require_once 'inc/asset-helper.php';

// Helper de segurança do formulário
require_once 'inc/form-security.php';

// Autoloader do Composer (PHPMailer, etc.)
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Processar formulário de contato (mesma lógica do index.php)
$form_errors = [];
$is_mail_sent = false;
$form_submitted = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $form_submitted = true;
    // Verificar honeypot
    if (is_honeypot_filled()) {
        $form_errors[] = 'Spam detectado.';
    } else {
        // Rate limiting
        $rateLimit = check_rate_limit(3, 3600);
        if (!$rateLimit['allowed']) {
            $form_errors[] = 'Muitas tentativas. Tente novamente mais tarde.';
        } else {
            // Incrementar contador
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $sessionKey = 'form_attempts_' . md5($ip);
            $attempts = $_SESSION[$sessionKey] ?? ['count' => 0, 'first_attempt' => time()];
            $attempts['count']++;
            $_SESSION[$sessionKey] = $attempts;
            
            // Validar campos
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            if (empty($name) || strlen($name) < 2) {
                $form_errors[] = 'Nome inválido.';
            }
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $form_errors[] = 'Email inválido.';
            }
            if (empty($subject)) {
                $form_errors[] = 'Selecione um assunto.';
            }
            if (empty($message) || strlen($message) < 10) {
                $form_errors[] = 'Mensagem deve ter no mínimo 10 caracteres.';
            }
            
            // Verificar palavras-chave de spam
            $spam_keywords = ['viagra', 'casino', 'loan', 'credit', 'debt'];
            $message_lower = mb_strtolower($message);
            foreach ($spam_keywords as $keyword) {
                if (strpos($message_lower, $keyword) !== false) {
                    $form_errors[] = 'Mensagem contém conteúdo não permitido.';
                    break;
                }
            }
            
            if (empty($form_errors)) {
                // Verificar se estamos em desenvolvimento ou se credenciais não estão configuradas
                $isDevelopment = (defined('APP_ENV') && APP_ENV === 'development');
                $hasMailgunCredentials = !empty(getenv('MAILGUN_USERNAME') ?: ($MailGunUsername ?? '')) && 
                                         !empty(getenv('MAILGUN_PASSWORD') ?: ($MailGunPassword ?? ''));
                
                // Se estiver em desenvolvimento OU não tiver credenciais, salvar em arquivo
                if ($isDevelopment || !$hasMailgunCredentials) {
                    // Modo desenvolvimento: salvar em arquivo
                    $devEmailDir = __DIR__ . '/dev-emails';
                    if (!is_dir($devEmailDir)) {
                        mkdir($devEmailDir, 0755, true);
                    }
                    $emailContent = "De: $name <$email>\nAssunto: $subject\nData: " . date('Y-m-d H:i:s') . "\n\n$message";
                    $filename = $devEmailDir . '/email_' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.txt';
                    if (file_put_contents($filename, $emailContent)) {
                        $is_mail_sent = true;
                    } else {
                        $form_errors[] = 'Erro ao salvar mensagem. Verifique permissões do diretório.';
                    }
                } else {
                    // Modo produção: enviar via SMTP
                    $mail = new PHPMailer(true);
                    
                    try {
                        $mail->isSMTP();
                        $mail->Host = 'smtp.mailgun.org';
                        $mail->SMTPAuth = true;
                        $mail->Username = getenv('MAILGUN_USERNAME') ?: $MailGunUsername ?? '';
                        $mail->Password = getenv('MAILGUN_PASSWORD') ?: $MailGunPassword ?? '';
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;
                        $mail->CharSet = 'UTF-8';
                        $mail->SMTPDebug = 0; // Desabilitar debug em produção
                        
                        // Remetente e destinatário
                        $mail->setFrom('noreply@minhamimo.com.br', 'Site Mimo');
                        $mail->addAddress('atendimento@minhamimo.com.br', 'Mimo Atendimento');
                        $mail->addReplyTo($email, $name);
                        
                        // Conteúdo
                        $mail->isHTML(true);
                        $mail->Subject = 'Contato do Site: ' . $subject;
                        $mail->Body = "
                            <h2>Nova mensagem do formulário de contato</h2>
                            <p><strong>Nome:</strong> " . htmlspecialchars($name) . "</p>
                            <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
                            <p><strong>Assunto:</strong> " . htmlspecialchars($subject) . "</p>
                            <p><strong>Mensagem:</strong></p>
                            <p>" . nl2br(htmlspecialchars($message)) . "</p>
                        ";
                        $mail->AltBody = "Nome: $name\nEmail: $email\nAssunto: $subject\n\nMensagem:\n$message";
                        
                        $mail->send();
                        $is_mail_sent = true;
                    } catch (Exception $e) {
                        $form_errors[] = 'Erro ao enviar mensagem. Tente novamente mais tarde.';
                        if ($isDevelopment) {
                            $form_errors[] = 'Detalhes: ' . $mail->ErrorInfo;
                        }
                    }
                }
            }
        }
    }
}

// Definir variáveis para SEO
$pageTitle = 'Contato - Mimo | Entre em Contato Conosco';
$pageDescription = 'Entre em contato com a Mimo! Estamos na Rua Heitor Penteado, 626, próximo ao metrô Vila Madalena. Telefone: (11) 3062-8295. Horário: Terça a Sábado, 08h30 às 22h.';
$pageKeywords = 'contato mimo, endereço mimo, telefone mimo, como chegar mimo, minhamimo contato';

// Coordenadas para mapa (Vila Madalena, SP - Rua Heitor Penteado, 626)
// Coordenadas aproximadas - ajustar se necessário
$latitude = '-23.5505';
$longitude = '-46.6333';
$googleMapsUrl = 'https://www.google.com/maps?q=Rua+Heitor+Penteado,+626,+Vila+Madalena,+São+Paulo';
$googleMapsEmbed = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3657.5!2d-46.6333!3d-23.5505!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjPCsDMzJzAxLjgiUyA0NsKwMzcnNTkuOSJX!5e0!3m2!1spt-BR!2sbr!4v1234567890';
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta name="generator" content="Mimo Site v<?php echo APP_VERSION; ?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Victor Penter">
    
    <?php
    // SEO Meta Tags
    echo generate_seo_meta_tags($pageTitle, $pageDescription, $pageKeywords);
    echo generate_canonical_url('contato.php');
    echo generate_open_graph_tags($pageTitle, $pageDescription, 'img/bgheader.jpg');
    echo generate_twitter_cards($pageTitle, $pageDescription, 'img/bgheader.jpg');
    ?>

    <!-- Script loader for deferred CSS - Must come before deferred resources -->
    <script>
    /*! loadCSS. [c]2017 Filament Group, Inc. MIT License */
    (function(w){"use strict";if(!w.loadCSS){w.loadCSS=function(href,media,rel){var ss=w.document.createElement("link");var ref;if(rel){ref=w.document.getElementsByTagName(rel)[0];}else{var refs=w.document.getElementsByTagName("script");ref=refs[refs.length-1];}ss.rel="stylesheet";ss.href=href;ss.media="only x";function onloadcssdefined(cb){for(var i=0;i<w.document.styleSheets.length;i++){if(w.document.styleSheets[i].href===ss.href){return cb();}}setTimeout(function(){onloadcssdefined(cb);});}onloadcssdefined(function(){ss.media=media||"all";});if(ref){ref.parentNode.insertBefore(ss,ref.nextSibling);}else{w.document.head.appendChild(ss);}return ss;};}})(this);
    </script>

    <!-- Bootstrap CSS - Defer -->
    <script>loadCSS("bootstrap/bootstrap/dist/css/bootstrap.min.css?<?php echo ASSET_VERSION; ?>");</script>
    <noscript><link rel="stylesheet" href="bootstrap/bootstrap/dist/css/bootstrap.min.css?<?php echo ASSET_VERSION; ?>"></noscript>

    <!-- Lucide Icons - Lightweight replacement for Font Awesome -->
    <script src="https://cdn.jsdelivr.net/npm/lucide@0.263.1/dist/umd/lucide.js"></script>

    <!-- Google Fonts - Defer -->
    <script>loadCSS("https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap");</script>
    <noscript><link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet"></noscript>

    <!-- CSS Variables - Defer (deve vir antes de product.css mas não bloqueia renderização) -->
    <script>loadCSS("<?php echo get_css_asset('css/modules/_variables.css'); ?>");</script>
    <noscript><link rel="stylesheet" href="<?php echo get_css_asset('css/modules/_variables.css'); ?>"></noscript>

    <!-- Custom styles - Defer -->
    <script>loadCSS("<?php echo get_css_asset('product.css'); ?>");</script>
    <noscript><?php echo css_tag('product.css'); ?></noscript>

    <!-- Dark Mode Styles - Defer -->
    <script>loadCSS("<?php echo get_css_asset('css/modules/dark-mode.css'); ?>");</script>
    <noscript><link rel="stylesheet" href="<?php echo get_css_asset('css/modules/dark-mode.css'); ?>"></noscript>

    <!-- Mobile UI Improvements - Defer -->
    <script>loadCSS("<?php echo get_css_asset('css/modules/mobile-ui-improvements.css'); ?>");</script>
    <noscript><link rel="stylesheet" href="<?php echo get_css_asset('css/modules/mobile-ui-improvements.css'); ?>"></noscript>
    
    <!-- Fix para remover barra branca entre conteúdo e footer -->
    <style>
    .page-section {
        padding-bottom: 0 !important;
        margin-bottom: 0 !important;
    }
    .site-footer {
        margin-top: 0 !important;
    }
    
    /* Customizar cores dos alerts do Bootstrap para combinar com o design */
    .info-card .alert-success {
        background-color: #f0f9f4;
        border-color: rgba(204, 183, 188, 0.3);
        color: #2a3a42;
    }
    
    .info-card .alert-success strong {
        color: #1a252a;
    }
    
    .info-card .alert-danger {
        background-color: #fff5f5;
        border-color: rgba(204, 183, 188, 0.3);
        color: #2a3a42;
    }
    
    .info-card .alert-danger strong {
        color: #1a252a;
    }
    
    .info-card .alert-warning {
        background-color: #fffbf0;
        border-color: rgba(204, 183, 188, 0.3);
        color: #2a3a42;
    }
    
    .info-card .alert-warning strong {
        color: #1a252a;
    }
    </style>
    
    <!-- Fix para ícones Font Awesome no footer -->
    <style>
    .site-footer .footer-social-link {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 42px !important;
        height: 42px !important;
    }
    
    /* Footer social links já usam SVG inline - sem necessidade de Font Awesome */
    </style>
    <link rel="stylesheet" href="form/main.css?<?php echo ASSET_VERSION; ?>">

    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png?20211226">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png?20211226">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png?20211226">
    <link rel="manifest" href="/manifest.json">

</head>

<body>
    <?php include 'inc/header.php'; ?>

    <!-- Hero Section -->
    <div class="page-hero">
        <div class="container">
            <h1 class="Akrobat">ENTRE EM CONTATO</h1>
            <p>Estamos prontas para atender você com carinho e dedicação</p>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="page-section">
        <div class="container">
            <div class="row">
                <!-- Informações de Contato -->
                <div class="col-lg-5 mb-4">
                    <div class="info-card">
                        <h3><?php echo lucide_icon('map-pin', 'mr-2', 20); ?> Endereço</h3>
                        <p><strong>Rua Heitor Penteado, 626</strong></p>
                        <p>(Próximo ao metrô Vila Madalena e Sumaré)</p>
                        <p>SÃO PAULO - SP</p>
                        <div class="action-buttons">
                            <a href="<?php echo $googleMapsUrl; ?>" target="_blank" class="action-btn action-btn-primary">
                                <?php echo lucide_icon('route', 'mr-2', 18); ?> Como chegar
                            </a>
                        </div>
                    </div>

                    <div class="info-card">
                        <h3><?php echo lucide_icon('phone', 'mr-2', 20); ?> Telefone</h3>
                        <p><strong>(11) 3062-8295</strong></p>
                        <p>(11) 99478-1012 (Somente WhatsApp)</p>
                        <div class="action-buttons">
                            <a href="tel:+551130628295" class="action-btn action-btn-secondary">
                                <?php echo lucide_icon('phone', 'mr-2', 18); ?> Ligar
                            </a>
                            <a href="https://api.whatsapp.com/send?1=pt_BR&phone=5511994781012" target="_blank" class="action-btn action-btn-primary">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" style="margin-right: 6px; vertical-align: middle;">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg> WhatsApp
                            </a>
                        </div>
                    </div>

                    <div class="info-card">
                        <h3><?php echo lucide_icon('clock', 'mr-2', 20); ?> Horário de Funcionamento</h3>
                        <p><strong>Terça-Feira à Sábado</strong></p>
                        <p>08h30 às 22h</p>
                        <?php
                        // Calcular se está aberto
                        $now = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
                        $dayOfWeek = (int)$now->format('w'); // 0 = domingo, 6 = sábado
                        $hour = (int)$now->format('H');
                        $minute = (int)$now->format('i');
                        $currentTime = $hour * 60 + $minute;
                        $openTime = 8 * 60 + 30; // 08:30
                        $closeTime = 22 * 60; // 22:00
                        
                        $isOpen = false;
                        if ($dayOfWeek >= 2 && $dayOfWeek <= 6) { // Terça a Sábado
                            if ($currentTime >= $openTime && $currentTime < $closeTime) {
                                $isOpen = true;
                            }
                        }
                        ?>
                        <span class="horario-status <?php echo $isOpen ? 'aberto' : 'fechado'; ?>">
                            <?php echo lucide_icon('circle', 'mr-2', 12); ?> <?php echo $isOpen ? 'Aberto agora' : 'Fechado agora'; ?>
                        </span>
                    </div>

                    <div class="info-card">
                        <h3><?php echo lucide_icon('share-2', 'mr-2', 20); ?> Redes Sociais</h3>
                        <div class="action-buttons">
                            <a href="https://www.instagram.com/minhamimo/" target="_blank" class="action-btn action-btn-secondary">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" style="margin-right: 8px;">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg> Instagram
                            </a>
                            <a href="https://www.facebook.com/mimocuidadoebeleza/" target="_blank" class="action-btn action-btn-secondary">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" style="margin-right: 8px;">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg> Facebook
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Mapa e Formulário -->
                <div class="col-lg-7">
                    <!-- Mapa -->
                    <div class="map-container">
                        <iframe 
                            src="https://www.google.com/maps/embed/v1/place?key=<?php echo htmlspecialchars(GOOGLE_PLACES_API_KEY); ?>&q=Rua+Heitor+Penteado,+626,+Vila+Madalena,+São+Paulo"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>

                    <!-- Formulário -->
                    <div class="info-card">
                        <h3><?php echo lucide_icon('mail', 'mr-2', 20); ?> Envie sua Mensagem</h3>
                        
                        <?php if ($form_submitted && isset($is_mail_sent) && $is_mail_sent) { ?>
                            <div class="alert alert-success" role="alert">
                                <strong>Sucesso!</strong> Sua mensagem foi enviada com sucesso!
                                <?php 
                                $isDev = (defined('APP_ENV') && APP_ENV === 'development');
                                $hasCreds = !empty(getenv('MAILGUN_USERNAME') ?: ($MailGunUsername ?? '')) && 
                                           !empty(getenv('MAILGUN_PASSWORD') ?: ($MailGunPassword ?? ''));
                                if ($isDev || !$hasCreds): 
                                ?>
                                    <br><small>⚠️ Modo desenvolvimento: Email salvo em arquivo (não enviado via SMTP)</small>
                                <?php endif; ?>
                            </div>
                        <?php } else if ($form_submitted && isset($form_errors) && !empty($form_errors)) { ?>
                            <div class="alert alert-danger" role="alert">
                                <strong>Erro:</strong>
                                <ul class="mb-0 mt-2">
                                    <?php foreach ($form_errors as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php } else if ($form_submitted && isset($is_mail_sent) && !$is_mail_sent) { ?>
                            <div class="alert alert-warning" role="alert">
                                <strong>Erro ao enviar:</strong> Desculpe, sua mensagem não pode ser enviada no momento.
                                <br>Tente novamente mais tarde ou entre em contato pelo WhatsApp.
                                <?php if (defined('APP_ENV') && APP_ENV === 'development'): ?>
                                    <br><small>💡 Dica: Em desenvolvimento, mensagens são salvas em arquivo se não houver credenciais SMTP configuradas.</small>
                                <?php endif; ?>
                            </div>
                        <?php } ?>

                        <form class="contact100-form validate-form" method="POST" action="#form">
                            <div class="wrap-input100 validate-input" data-validate="Insira seu nome completo">
                                <label for="name" class="form-label">Nome completo</label>
                                <input class="input100" type="text" name="name" id="name" placeholder="Digite seu nome completo" 
                                    value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8') : ''; ?>" 
                                    required minlength="2" maxlength="100">
                            </div>

                            <div class="wrap-input100 validate-input" data-validate="Insira um e-mail válido">
                                <label for="email" class="form-label">E-mail</label>
                                <input class="input100" type="email" name="email" id="email" placeholder="Digite seu e-mail" 
                                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : ''; ?>" 
                                    required>
                            </div>

                            <div class="wrap-input100 validate-input" data-validate="Selecione o assunto">
                                <label for="subject" class="form-label">Assunto</label>
                                <select class="input100" name="subject" id="subject" required>
                                    <option value="" disabled <?php echo !isset($_POST['subject']) ? 'selected' : ''; ?>>Selecione o assunto</option>
                                    <option value="Dúvidas" <?php echo (isset($_POST['subject']) && $_POST['subject'] === 'Dúvidas') ? 'selected' : ''; ?>>Dúvidas</option>
                                    <option value="Agradecimentos/Depoimentos" <?php echo (isset($_POST['subject']) && $_POST['subject'] === 'Agradecimentos/Depoimentos') ? 'selected' : ''; ?>>Agradecimentos/Depoimentos</option>
                                    <option value="Outro" <?php echo (isset($_POST['subject']) && $_POST['subject'] === 'Outro') ? 'selected' : ''; ?>>Outro</option>
                                </select>
                            </div>

                            <div class="wrap-input100 validate-input" data-validate="Por favor, digite uma mensagem (mínimo 10 caracteres)">
                                <label for="message" class="form-label">Mensagem</label>
                                <textarea class="input100" name="message" id="message" placeholder="Digite sua mensagem aqui (mínimo 10 caracteres)" 
                                    required minlength="10" maxlength="2000" rows="5"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                                <span id="message-counter" class="message-counter">0 / 2000</span>
                            </div>

                            <!-- Honeypot field -->
                            <div style="position: absolute; left: -9999px; opacity: 0; pointer-events: none;" aria-hidden="true">
                                <input type="text" name="website" tabindex="-1" autocomplete="off">
                            </div>

                            <div class="container-contact100-form-btn">
                                <button class="contact100-form-btn" type="submit" name="submit" id="submit">
                                    <span style="color: #ffffff !important; font-weight: 600; letter-spacing: 2px;">ENVIAR MENSAGEM</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <!-- Links de Navegação -->
                <div class="col-12 col-md-4 mb-4 mb-md-0">
                    <h2 class="footer-title">Navegação</h2>
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
                    <h2 class="footer-title">Contato</h2>
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
                    <h2 class="footer-title">Redes Sociais</h2>
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
            'latitude' => $latitude,
            'longitude' => $longitude
        ]
    ]);
    ?>

    <!-- Bootstrap JS -->
    <!-- jQuery - Load synchronously to ensure it's available before Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script>
    // Fallback to local jQuery if CDN fails
    if (typeof jQuery === 'undefined') {
        document.write('<script src="bootstrap/jquery/dist/jquery.slim.min.js"><\/script>');
    }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous" defer></script>
    <!-- Bootstrap JS - Usar completo temporariamente até corrigir build custom -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous" defer></script>

    <!-- Form validation script -->
    <script src="form/main.js?<?php echo ASSET_VERSION; ?>" defer></script>

    <!-- Botão Voltar ao Topo -->
    <?php include 'inc/back-to-top.php'; ?>

    <!-- Bootstrap Carousel Swipe Plugin -->
    <?php echo js_tag('js/bc-swipe.js', ['defer' => true]); ?>
    
    <!-- Main JS (navbar scroll behavior) -->
    <?php echo js_tag('main.js', ['defer' => true]); ?>
    <?php echo js_tag('js/dark-mode.js', ['defer' => false]); ?>

    <script>
        // Forçar navbar com fundo desde o início em páginas internas (DEPOIS do main.js)
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

    <script>

        // Contador de caracteres do textarea
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.querySelector('textarea[name="message"]');
            const counter = document.getElementById('message-counter');
            
            if (textarea && counter) {
                textarea.addEventListener('input', function() {
                    const length = this.value.length;
                    counter.textContent = length + ' / 2000';
                    if (length > 2000) {
                        counter.style.color = '#dc3545';
                    } else {
                        counter.style.color = '#666';
                    }
                });
            }
        });
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
    </style>

    <!-- Inicializar Lucide Icons -->
    <script>
        // Inicializar Lucide Icons após DOM ready
        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", function() {
                if (typeof lucide !== "undefined") {
                    lucide.createIcons();
                }
            });
        } else {
            if (typeof lucide !== "undefined") {
                lucide.createIcons();
            }
        }
    </script>

</body>

</html>

