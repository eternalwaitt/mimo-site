<?php
/**
 * Site Mimo - Página Inicial
 * 
 * Desenvolvido por: Victor Penter
 * Versão: 2.2.3
 * 
 * Este arquivo contém a página principal do site com formulário de contato
 * e seções de serviços, depoimentos e informações de contato.
 */

// Suprimir avisos de depreciação de bibliotecas de terceiros (compatibilidade PHP 8.4)
error_reporting(E_ALL & ~E_DEPRECATED);

// Cabeçalhos de segurança
require_once 'inc/security-headers.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Carregar configuração (inclui variáveis de ambiente)
require_once 'config.php';

// Cache headers para páginas HTML
require_once 'inc/cache-headers.php';
set_html_cache_headers();

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
    
    <?php
    // SEO Meta Tags
    $pageTitle = 'MIMO Estética - Centro de Beleza em São Paulo | Estética, Salão, Cílios e Design';
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

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=EB+Garamond:400,400i,700i" rel="stylesheet">
    <link href="Akrobat-Regular.woff" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"
        integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">


    <!-- Bootstrap core CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <?php echo css_tag('product.css'); ?>

    <!-- Form -->
    <link rel="stylesheet" type="text/css" href="form/css/font-awesome.min.css">
    <?php echo css_tag('form/main.css', ['id' => 'form-css']); ?>

    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png?20211226">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png?20211226">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png?20211226">
    <link rel="manifest" href="favicon/site.webmanifest">

</head>

<body>

    <?php include "inc/header.php"; ?>

    <!-- Modal Impedimento -->
    <div class="modal fade" id="exampleModalCenterNav" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="textPink text-center my-3">ATENÇÃO!</h3>
                    <p class="textDarkGrey text-center">
                        Para concluir o agendamento é necessário depósito de <b>R$20</b> para cada procedimento
                        agendado, a fim de evitar segurarmos vagas na agenda e haver falta no dia. Solicitamos que seja
                        depositado em até <b>48 horas</b> após o pré-agendamento para confirmar seu horário. Caso não
                        seja efetuado o agendamento é cancelado para não segurar vaga na agenda.
                        <span class="textPink font-weight-bold">
                            O restante do valor deverá ser pago no dia do atendimento. Aceitamos dinheiro ou cartão
                            débito/crédito.
                        </span><br />
                        Pedimos por gentileza que se atente ao horário agendado, pois atrasos superiores a 10 minutos
                        podem ocasionar o cancelamento do seu atendimento sem a possibilidade de novo agendamento.<br />
                        Obs. O não comparecimento com aviso prévio de 48hrs acarretará na perda integral do valor
                        depositado. Caso não possa comparecer nos comunique com 48hrs de antecedência e o valor será
                        creditado para reagendar uma nova visita.
                        <br /><br />
                        <span class="textPink font-weight-bold">
                            Dados bancários:<br />
                        </span>
                        MIMO CENTRO DE BELEZA LTDA<br />
                        28.038.663/0001-14<br />
                        BANCO ITAÚ: 341<br />
                        AGÊNCIA VILLA LOBOS: 2954<br />
                        CONTA CORRENTE: 17793-3 <br>
                        PIX: 28.038.663/0001-14
                    </p>
                </div>
                <button data-dismiss="modal" class="d-none d-sm-block">
                    <a class="btn btnAgendamento" data-toggle="modal" data-target="#exampleModalCenterNav2"
                        style="width: 100%">ENTENDI E QUERO AGENDAR</a>
                </button>
                <button class="d-sm-none">
                    <a class="btn btnAgendamento" href="https://agendamento.salaovip.com.br/?slug=mimoestetica"
                        target="_blank" style="width: 100%">ENTENDI E QUERO AGENDAR</a>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenterNav2" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <iframe id="agendaWidgetSalaoNav" width="100%" height="520px"
                        style="border:none; border-radius: 10px;"
                        src="https://agendamento.salaovip.com.br/?slug=mimoestetica"></iframe>
                </div>
            </div>
        </div>
    </div>



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
    <div class="position-relative overflow-hidden p-3 text-center backgroundGrey">
        <div class="col-md-12 p-lg-12 mx-auto my-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-center m-auto">
                        <h4 class="textDarkGrey" style="margin-bottom: 0!important">MAIS SOBRE NÓS</h4>
                        <h3 style="color:#fff">DEPOIMENTOS</h3>
                        <div id="myCarousel" class="carousel slide" data-ride="carousel">
                            <!-- Carousel indicators -->
                            <ol class="carousel-indicators ">
                                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                                <li data-target="#myCarousel" data-slide-to="1"></li>
                                <li data-target="#myCarousel" data-slide-to="2"></li>
                                <li data-target="#myCarousel" data-slide-to="3"></li>
                                <li data-target="#myCarousel" data-slide-to="4"></li>
                                <li data-target="#myCarousel" data-slide-to="5"></li>
                            </ol>
                            <!-- Wrapper for carousel items -->
                            <div class="carousel-inner">
                                <div class="item carousel-item active">
                                    <div class="img-box"><?php echo picture_webp('img/depo/pdamora.png', 'foto-pdamora', 'img-fluid'); ?></div>
                                    <h5 class="font-weight-bold textDarkGrey mt-4">@pdamora</h5>
                                    <p class="testimonial">
                                        No meu caso eu só compartilho o que faz bem para mim. Com a Mimo não é
                                        diferente. Não tem ninguém que eu conheça que não me pergunte aonde eu faço meu
                                        alongamento de unhas e a cor do meu cabelo! Faço questão de recomendar a Mimo,
                                        pois confio de olhos fechados nas profissionais, que além de talentosas são
                                        responsáveis, cuidadosas e o mais importante: sabem o que estão fazendo. Sempre
                                        saio de lá feliz da vida :)
                                    </p>
                                </div>
                                <div class="item carousel-item">
                                    <div class="img-box"><?php echo picture_webp('img/depo/barbara.jpeg', 'foto-barbara', 'img-fluid'); ?></div>
                                    <h5 class="font-weight-bold textDarkGrey mt-4">@babiputtini</h5>
                                    <p class="testimonial">
                                        Desde que me mudei pra SP, há 7 anos atrás, eu perdi a rotina que eu tinha de ir
                                        ao salão. Não sei se pela correria do dia a dia, se pela preguiça… Mas fato é
                                        que, desde que comecei a me aventurar por salões de beleza por aqui, eu nunca
                                        encontrei um que me fazia sentir à vontade. Era conhecer um, fazer a sobrancelha
                                        e ok, próximo. Gostar de uma manicure, fazer por um mês, toda semana, e já ficar
                                        desanimada pra ir de novo. De verdade, eu aproveito esse espaço pra dizer que ir
                                        pra Mimo não me dá preguiça, não me exige nenhum esforço. Eu gosto de ir, bater
                                        papo… eu sinto as profissionais muito próximas e muito verdadeiras. Ninguém
                                        tenta te empurrar nada, os serviços não são feitos de forma corrida e são sempre
                                        feitos com muito cuidado. Eu faço sobrancelha, cílios, unhas, massagem, limpeza
                                        de pele… Teve semana que cheguei a ir 3x pra fazer todas as princesices que eu
                                        queria. Obrigada, meninas por todo o cuidado e paciência, desde a hora de marcar
                                        até a hora de pagar. Vocês são seres humanas especiais e construíram uma cultura
                                        de trabalho de atendimento leve e gostoso, o que é muito difícil de achar. <3
                                            </p>
                                </div>
                                <div class="item carousel-item">
                                    <div class="img-box"><?php echo picture_webp('img/depo/amanda.jpeg', 'foto-amanda', 'img-fluid'); ?></div>
                                    <h5 class="font-weight-bold textDarkGrey mt-4">@amandices</h5>
                                    <p class="testimonial">
                                        Eu simplesmente amo a Mimo. Frequento há mais de 2 anos e já fiz quase tudo que
                                        tem disponível: unha, cabelo, cílios, sobrancelha, estética corporal... Sempre
                                        saio de lá me sentindo melhor comigo mesma, tanto pela qualidade dos
                                        procedimentos, quanto pelo tratamento da equipe que é sempre muito atenciosa,
                                        desde o momento do agendamento de horário, até a hora que saio de lá. Meus dias
                                        são muito corridos e, quando estou na Mimo, posso parar pra relaxar e ter um
                                        momento de cuidado comigo mesma com total confiança.
                                    </p>
                                </div>
                                <div class="item carousel-item">
                                    <div class="img-box"><?php echo picture_webp('img/depo/mamoderoso.jpeg', 'foto-mamoderoso', 'img-fluid'); ?></div>
                                    <h5 class="font-weight-bold textDarkGrey mt-4">@mamoderoso</h5>
                                    <h4 class="font-weight-light textDarkGrey text-uppercase">Assim que cheguei na Mimo,
                                        nunca mais saí!</h4>
                                    <p class="testimonial">
                                        A mimo pra mim foi a descoberta de um lugar novo na minha vida. Era uma portinha
                                        com uma recepção e duas salas, com duas meninas que conquistaram meu coração.
                                        Assim que cheguei na Mimo, nunca mais saí. Fez parte de me sentir mais eu e mais
                                        feliz comigo mesma. E ver cada fase dessa portinha com duas salas, crescer
                                        tanto, num lugar incrível, com diversas opções pra fazer milhares de meninas
                                        como eu se sentirem mais felizes consigo mesmas. Todo mundo que trabalha lá me
                                        conquistou, além de fazerem um trabalho impecável e com todo carinho. Desejo só
                                        mais sucesso e amor pra esse lugar incrível, e pra essas duas meninas que eu
                                        amo! ❤
                                    </p>
                                </div>
                                <div class="item carousel-item">
                                    <div class="img-box"><?php echo picture_webp('img/depo/livcordeiro.jpeg', 'foto-livcordeiro', 'img-fluid'); ?></div>
                                    <h5 class="font-weight-bold textDarkGrey mt-4">@livcordeiro</h5>
                                    <h4 class="font-weight-light textDarkGrey text-uppercase">Saio de lá me sentindo bem
                                        comigo mesma e com as energias renovadas!</h4>
                                    <p class="testimonial">
                                        Já vou na mimo a uma ano e com certeza é um dos meus lugares favoritos da vida!
                                        Sempre fui muito bem atendida e todos os procedimentos me agradaram muito, além
                                        da energia maravilhosa que o lugar tem! Saio de lá me sentindo bem comigo mesma
                                        e com as energias renovadas. Recomendo muito!
                                    </p>
                                </div>
                                <div class="item carousel-item">
                                    <div class="img-box"><?php echo picture_webp('img/depo/cathamendonca.jpeg', 'foto-cathamendonca', 'img-fluid'); ?></div>
                                    <h5 class="font-weight-bold textDarkGrey mt-4">@cathamendonca</h5>
                                    <h4 class="font-weight-light textDarkGrey text-uppercase">indico pra todo mundo ❤
                                    </h4>
                                    <p class="testimonial">
                                        Pra quem vem de outro estado, é bem difícil encontrar lugares onde a gente se
                                        sinta em casa. Com a Mimo foi assim, eu comecei indo fazer uma coisinha aqui e
                                        outra ali e agora faço boa parte dos procedimentos disponíveis. E são as horas
                                        mais relaxantes da semana. Depois disso, eu mudei muito a forma como eu me vejo
                                        e as outras pessoas sempre comentam como notam essa diferença em mim. Me sinto
                                        parte, indico pra todo mundo e torço pra que a Mimo cresça muito mais.
                                    </p>
                                </div>
                            </div>
                            <!-- Carousel controls -->
                            <a class="carousel-control left carousel-control-prev d-none d-md-flex" href="#myCarousel"
                                data-slide="prev">
                                <i class="fa fa-angle-left"></i>
                            </a>
                            <a class="carousel-control right carousel-control-next d-none d-md-flex" href="#myCarousel"
                                data-slide="next">
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="layerCor" id="contact">
        <div class="backgroundLogo py-md-5">
            <div class="d-md-flex flex-md-equal row py-md-5" style="margin-right:0!important; overflow-x: hidden">
                <div class="infos-form text-white col-md-12">
                    <ul class="float-right text-align-right mr-5" style="margin-top: 85px;" id="infos-gerais">
                        <li class="nav-item mt-md-3">
                            <h5 class="font-weight-normal mb-md-1">Endereço</h5>
                            <p>Rua Heitor Penteado, 626 <br>(Próximo ao metrô Vila Madalena e Sumaré)</p>
                            <p>SÃO PAULO - SP</p>
                        </li>
                        <li class="nav-item mt-md-3">
                            <h5 class="font-weight-normal mb-md-1">Telefone</h5>
                            <p>(11) 3062-8295</p>
                            <p>(11) 99478-1012 (Somente Whatsapp)</p>
                        </li>
                        <li class="nav-item font-weight-light mt-md-3">
                            <h5 class="font-weight-normal mb-md-1">Horário de <br />funcionamento</h5>
                            <p>Terça-Feira à Sábado</p>
                            <p>08h30 às 22h</p>
                        </li>
                    </ul>
                </div>
                <div class="bg-form text-center text-white overflow-hidden">
                    <div class="form">
                        <div class="container-contact100">

                            <div class="wrap-contact100">
                                <form class="contact100-form validate-form" id="form-mobile" 
                                    enctype="multipart/form-data" action="#contact" method="POST" data-ajax-form="true">
                                    <h5 class="font-weight-light text-align-left">DÚVIDAS OU SUGESTÕES?</h5>
                                    <h4 class="font-weight-bold text-align-left mb-4" style="letter-spacing: 2px;">ENTRE
                                        EM CONTATO</h4>

                                    <?php if (isset($is_mail_sent) && $is_mail_sent) { ?>
                                        <div class="alert alert-success" role="alert">
                                            <strong>Sucesso!</strong> Sua mensagem foi enviada com sucesso!
                                            <?php if (defined('APP_ENV') && APP_ENV === 'development'): ?>
                                                <br><small>⚠️ Modo desenvolvimento: Email salvo em arquivo (não enviado via SMTP)</small>
                                            <?php endif; ?>
                                        </div>
                                    <?php } else if (isset($form_errors) && !empty($form_errors)) { ?>
                                        <div class="alert alert-danger" role="alert">
                                            <strong>Erro:</strong>
                                            <ul class="mb-0 mt-2">
                                                <?php foreach ($form_errors as $error): ?>
                                                    <li><?php echo htmlspecialchars($error); ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php } else if (isset($is_mail_sent) && !$is_mail_sent) { ?>
                                        <div class="alert alert-warning" role="alert">
                                            <strong>Erro ao enviar:</strong> Desculpe, sua mensagem não pode ser enviada no momento.
                                            <?php if (defined('APP_ENV') && APP_ENV === 'development' && !empty($form_errors)): ?>
                                                <br><small>Detalhes: <?php echo htmlspecialchars(implode(', ', $form_errors)); ?></small>
                                            <?php else: ?>
                                                <br>Tente novamente mais tarde ou entre em contato pelo WhatsApp.
                                            <?php endif; ?>
                                        </div>
                                    <?php } ?>

                                    <div class="wrap-input100 validate-input" data-validate="Insira seu nome completo">
                                        <input class="input100" type="text" name="name" placeholder="Nome completo" 
                                            value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8') : ''; ?>" 
                                            required minlength="2" maxlength="100">
                                    </div>

                                    <div class="wrap-input100 validate-input"
                                        data-validate="Insira um e-mail válido, ex: nome@algo.com.br">
                                        <input class="input100" type="email" name="email" placeholder="E-mail" 
                                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : ''; ?>" 
                                            required>
                                    </div>

                                    <div class="" data-validate="Selecione o assunto">
                                        <select class="wrap-input100 validate-input assunto-form"
                                            id="exampleFormControlSelect1" name="subject" required>
                                            <option value="" disabled <?php echo !isset($_POST['subject']) ? 'selected' : ''; ?>>Selecione o assunto</option>
                                            <option value="Dúvidas" <?php echo (isset($_POST['subject']) && $_POST['subject'] === 'Dúvidas') ? 'selected' : ''; ?>>Dúvidas</option>
                                            <option value="Agradecimentos/Depoimentos" <?php echo (isset($_POST['subject']) && $_POST['subject'] === 'Agradecimentos/Depoimentos') ? 'selected' : ''; ?>>Agradecimentos/Depoimentos</option>
                                            <option value="Outro" <?php echo (isset($_POST['subject']) && $_POST['subject'] === 'Outro') ? 'selected' : ''; ?>>Outro</option>
                                        </select>
                                    </div>


                                    <div class="wrap-input100 validate-input"
                                        data-validate="Por favor, digite uma mensagem aqui (mínimo 10 caracteres).">
                                        <textarea class="input100" name="message" placeholder="Sua mensagem aqui (mínimo 10 caracteres)" 
                                            required minlength="10" maxlength="2000"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                                        <span id="message-counter" class="message-counter">0 / 2000</span>
                                    </div>

                                    <!-- Honeypot field (escondido, bots preenchem) -->
                                    <div style="position: absolute; left: -9999px; opacity: 0; pointer-events: none;" aria-hidden="true">
                                        <input type="text" name="website" tabindex="-1" autocomplete="off">
                                    </div>

                                    <div class="container-contact100-form-btn" style="justify-content: flex-end;">
                                        <button class="contact100-form-btn" type="submit" name="submit" id="submit">
                                            <span class="font-weight-bold textDarkGrey"
                                                style="letter-spacing: 2px;">ENVIAR</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="align-content-center text-center col-md-2">
                    <div class="box-redes-sociais">
                        <h5 class="text-white text-redes">NOS ACOMPANHE <br /> NAS REDES SOCIAIS</h5>
                        <ul class="navbar-nav ml-auto" style="display: -webkit-inline-box;">
                            <li class="nav-item active">
                                <a class="nav-link" href="https://www.instagram.com/minhamimo/" target="_blank"><i
                                        class="fab fa-instagram"></i></a>
                            </li>
                            <li class="nav-item active">
                                <a class="nav-link" href="https://www.facebook.com/mimocuidadoebeleza/"
                                    target="_blank"><i class="fab fa-facebook-square pl-4 pr-4 "></i></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="https://api.whatsapp.com/send?1=pt_BR&phone=5511994781012"
                                    target="_blank"><i class="fab fa-whatsapp"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
    </div>


    <footer class="container">
        <div class="row">
            <div class="col-12 col-md my-2 py-2">
                <small class="d-block text-muted text-center" style="line-height: 3;">&copy; MIMO CENTRO DE BELEZA LTDA
                    | 57.659.472/0001-78 | 2018 | Todos os direitos reservados</small>
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
                echo generate_review_schema($review, 'MIMO Estética');
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
                echo generate_manual_review_schema($review, 'MIMO Estética');
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
    <script src="bootstrap/popper.js/dist/popper.min.js"></script>
    <script src="bootstrap/bootstrap/dist/js/bootstrap.min.js"></script>
    <?php echo js_tag('form/main.js'); ?>
    <?php echo js_tag('main.js'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.18/jquery.touchSwipe.min.js"></script>

    <script>
        if ($('.carousel').length) {
            $('.carousel').carousel({ interval: 7000, pause: false });
            $(".carousel").swipe({
                swipe: function (event, direction, distance, duration, fingerCount, fingerData) {
                    if (direction == 'left') $(this).carousel('next');
                    if (direction == 'right') $(this).carousel('prev');
                },
                allowPageScroll: "vertical"
            });
        }
    </script>

    <!-- Piwik -->
    <script type="text/javascript">
        var _paq = _paq || [];
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function () {
            var u = "http://cluster-piwik.locaweb.com.br/";
            _paq.push(['setTrackerUrl', u + 'piwik.php']);
            _paq.push(['setSiteId', 28515]);
            var d = document, g = d.createElement('script'), s = d.getElementsByTagName('script')[0];
            g.type = 'text/javascript'; g.async = true; g.defer = true; g.src = u + 'piwik.js'; s.parentNode.insertBefore(g, s);
        })();
    </script>
    <!-- End Piwik Code -->

    <script src="//code.tidio.co/ylbfxpiqcmi2on8duid7rpjgqydlrqne.js"></script>

    <!-- Botão Voltar ao Topo -->
    <?php include 'inc/back-to-top.php'; ?>

</body>

</html>