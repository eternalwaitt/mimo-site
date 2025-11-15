<?php
/**
 * Site Mimo - Vagas Disponíveis
 * 
 * Desenvolvido por: Victor Penter
 * Versão: 2.2.5
 * 
 * Página para exibir vagas disponíveis na Mimo
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

// Helper de SEO
require_once 'inc/seo-helper.php';

// Asset helper para CSS/JS minificados
require_once 'inc/asset-helper.php';

// Breadcrumbs helper
require_once 'inc/breadcrumbs.php';

// Definir variáveis para SEO
$pageTitle = 'Vagas Disponíveis - Mimo | Trabalhe Conosco';
$pageDescription = 'Venha fazer parte da equipe Mimo! Confira as vagas disponíveis e trabalhe em um ambiente acolhedor e profissional.';
$pageKeywords = 'vagas mimo estética, trabalhe conosco, emprego estética são paulo, vaga salão beleza, emprego centro beleza';
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
    echo generate_canonical_url('vagas.php');
    echo generate_open_graph_tags($pageTitle, $pageDescription, 'img/bgheader.jpg');
    echo generate_twitter_cards($pageTitle, $pageDescription, 'img/bgheader.jpg');
    ?>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap/bootstrap/dist/css/bootstrap.min.css?<?php echo ASSET_VERSION; ?>">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">

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

    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png?20211226">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png?20211226">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png?20211226">
    <link rel="manifest" href="favicon/site.webmanifest">


    <?php include 'inc/gtm-head.php'; ?>
</head>

<body>

    <?php include "inc/header.php"; ?>

    <!-- Hero Section -->
    <div class="page-hero">
        <div class="container">
            <h1 class="Akrobat">TRABALHE CONOSCO</h1>
            <p>Faça parte da equipe Mimo</p>
        </div>
    </div>

    <!-- Vagas Section -->
    <section class="page-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="text-center mb-5 textDarkGrey">
                        Vagas Disponíveis
                    </h2>

                    <?php
                    // Array de vagas disponíveis
                    $vagas = [
                        [
                            'titulo' => 'Social Media & CRM',
                            'tipo' => 'Tempo Integral',
                            'localizacao' => 'Vila Madalena, São Paulo',
                            'salario' => 'A combinar',
                            'sobre_mimo' => 'A Mimo é a marca que facilita o dia a dia da mulher moderna ao oferecer serviços de cabelo, unha, cílios, sobrancelhas, micropigmentação, estética facial e corporal com baixa manutenção, qualidade e ciência em um só lugar.',
                            'sobre_vaga' => 'Buscamos alguém que acredite que a beleza é uma experiência cotidiana e que entenda o poder de construir conexões autênticas através da comunicação. Como Social Media & CRM da Mimo, você será responsável por dar vida à presença digital da marca — cuidando de conteúdo, comunidade e relacionamento com a mesma atenção com que cuidamos de cada cliente.',
                            'atividades' => [
                                'Criação de cronograma de postagens: desenvolver um calendário editorial estratégico para garantir uma presença consistente e relevante em todas as plataformas.',
                                'Estratégia de divulgação e campanhas: planejar e executar campanhas de comunicação digital que ampliem o alcance e reforcem a identidade da marca.',
                                'Captação de imagens: realizar e coordenar sessões fotográficas e audiovisuais, traduzindo a estética e o sentimento da Mimo em imagens reais e inspiradoras.',
                                'Acompanhamento de parcerias: gerenciar colaborações com parceiras e influenciadoras, garantindo alinhamento com os valores e o posicionamento da marca.',
                                'Edição de imagens e posts: criar conteúdos visuais atrativos e coerentes com a identidade visual da Mimo.',
                                'Publicação de posts e stories: atualizar regularmente as redes sociais, mantendo o diálogo próximo e humano com a comunidade.',
                                'Comunicação com parceiras via WhatsApp: manter contato direto com parceiras, coordenando agendas, entregas e feedbacks.',
                                'Agendamento de parcerias: organizar e acompanhar o cronograma de colaborações e produções, assegurando eficiência e harmonia no processo.',
                                'Análise de desempenho: monitorar e interpretar métricas de engajamento e crescimento, ajustando estratégias de forma contínua.',
                                'Criação de artes em geral: desenvolver peças gráficas para uso em campanhas, redes sociais e comunicações internas.',
                                'Redação e copy de vídeos: elaborar legendas, roteiros e textos que comuniquem com clareza e sensibilidade.'
                            ],
                            'experiencia' => [
                                'Experiência comprovada em Social Media, Marketing ou Comunicação.',
                                'Habilidade com design gráfico, fotografia e edição de imagem/vídeo.',
                                'Excelente comunicação escrita e verbal.',
                                'Criatividade e olhar estético apurado.',
                                'Conhecimento das principais tendências e ferramentas de mídia social.',
                                'Capacidade de atuar com autonomia, organização e colaboração.',
                                'Ensino superior completo preferencialmente em Comunicação, Marketing ou áreas afins (ou experiência equivalente).'
                            ],
                            'competencias' => [
                                'Sensibilidade para criar conteúdo original e visualmente coerente com o universo Mimo.',
                                'Boa comunicação escrita e visual, alinhada ao tom leve e consciente da marca.',
                                'Domínio das plataformas de mídia social e suas ferramentas analíticas.',
                                'Planejamento e disciplina para manter cronogramas e campanhas em dia.',
                                'Capacidade de captação e edição de imagens e vídeos com autenticidade.',
                                'Análise crítica de dados e adaptação de estratégias conforme resultados.',
                                'Gestão de parcerias e relacionamento com influenciadoras e clientes.',
                                'Flexibilidade para acompanhar tendências e traduzir feedbacks em melhorias.',
                                'Foco genuíno na experiência da cliente e na construção de comunidade.',
                                'Colaboração efetiva com diferentes áreas e disposição para inovar.',
                                'Iniciativa, atenção aos detalhes e responsabilidade em cada entrega.'
                            ],
                            'nosso_jeito' => 'Na Mimo, acreditamos que o cuidado com o outro começa pelo cuidado com o que comunicamos. Buscamos pessoas que queiram crescer em um ambiente que valoriza a beleza, a consistência e o prazer de fazer bem feito.'
                        ]
                    ];

                    // Se não houver vagas, mostrar mensagem
                    if (empty($vagas)) {
                        echo '<div class="no-vagas">
                                <i class="fas fa-briefcase"></i>
                                <h3>Não há vagas disponíveis no momento</h3>
                                <p>Mas estamos sempre em busca de novos talentos! Envie seu currículo para <a href="mailto:atendimento@minhamimo.com.br">atendimento@minhamimo.com.br</a></p>
                              </div>';
                    } else {
                        // Mostrar cada vaga
                        foreach ($vagas as $vaga) {
                            echo '<div class="vaga-card">';
                            echo '<h3 class="vaga-title">' . htmlspecialchars($vaga['titulo']) . '</h3>';
                            
                            echo '<div class="vaga-info">';
                            echo '<div class="vaga-info-item"><i class="fas fa-briefcase"></i> <strong>Tipo:</strong> ' . htmlspecialchars($vaga['tipo']) . '</div>';
                            echo '<div class="vaga-info-item"><i class="fas fa-map-marker-alt"></i> <strong>Localização:</strong> ' . htmlspecialchars($vaga['localizacao']) . '</div>';
                            echo '<div class="vaga-info-item"><i class="fas fa-money-bill-wave"></i> <strong>Salário:</strong> ' . htmlspecialchars($vaga['salario']) . '</div>';
                            echo '</div>';
                            
                            // Sobre a Mimo
                            if (!empty($vaga['sobre_mimo'])) {
                                echo '<div class="vaga-requirements vaga-about-mimo">';
                                echo '<h4><i class="fas fa-building"></i> Sobre a Mimo</h4>';
                                echo '<p>' . htmlspecialchars($vaga['sobre_mimo']) . '</p>';
                                echo '</div>';
                            }
                            
                            // Sobre a Vaga
                            if (!empty($vaga['sobre_vaga'])) {
                                echo '<div class="vaga-description">';
                                echo '<h4><i class="fas fa-info-circle"></i> Sobre a Vaga</h4>';
                                echo '<p>' . htmlspecialchars($vaga['sobre_vaga']) . '</p>';
                                echo '</div>';
                            }
                            
                            // Atividades Exercidas
                            if (!empty($vaga['atividades'])) {
                                echo '<div class="vaga-requirements">';
                                echo '<h4><i class="fas fa-tasks"></i> Atividades Exercidas</h4>';
                                echo '<ul>';
                                foreach ($vaga['atividades'] as $atividade) {
                                    echo '<li>' . htmlspecialchars($atividade) . '</li>';
                                }
                                echo '</ul>';
                                echo '</div>';
                            }
                            
                            // Experiência e Formação
                            if (!empty($vaga['experiencia'])) {
                                echo '<div class="vaga-requirements vaga-experiencia">';
                                echo '<h4><i class="fas fa-graduation-cap"></i> Experiência e Formação</h4>';
                                echo '<ul>';
                                foreach ($vaga['experiencia'] as $exp) {
                                    echo '<li>' . htmlspecialchars($exp) . '</li>';
                                }
                                echo '</ul>';
                                echo '</div>';
                            }
                            
                            // Competências
                            if (!empty($vaga['competencias'])) {
                                echo '<div class="vaga-requirements vaga-competencias">';
                                echo '<h4><i class="fas fa-star"></i> Competências</h4>';
                                echo '<ul>';
                                foreach ($vaga['competencias'] as $competencia) {
                                    echo '<li>' . htmlspecialchars($competencia) . '</li>';
                                }
                                echo '</ul>';
                                echo '</div>';
                            }
                            
                            // Nosso Jeito de Trabalhar
                            if (!empty($vaga['nosso_jeito'])) {
                                echo '<div class="vaga-requirements vaga-nosso-jeito">';
                                echo '<h4><i class="fas fa-heart"></i> Nosso Jeito de Trabalhar</h4>';
                                echo '<p class="italic">' . htmlspecialchars($vaga['nosso_jeito']) . '</p>';
                                echo '</div>';
                            }
                            
                            echo '<div class="text-center mt-5">';
                            echo '<a href="mailto:atendimento@minhamimo.com.br?subject=Candidatura - ' . urlencode($vaga['titulo']) . '" class="btn-candidatar">';
                            echo '<i class="fas fa-paper-plane"></i> Candidatar-se';
                            echo '</a>';
                            echo '</div>';
                            
                            echo '</div>';
                        }
                    }
                    ?>

                    <!-- Informações Adicionais -->
                    <div class="vaga-card vaga-candidatar">
                        <h3>Como se candidatar</h3>
                        <p>
                            Envie seu currículo para <strong>atendimento@minhamimo.com.br</strong> com o assunto contendo o nome da vaga de interesse.
                        </p>
                        <p>
                            <i class="fas fa-info-circle"></i> <strong>Dica:</strong> Inclua uma carta de apresentação contando por que você gostaria de fazer parte da equipe Mimo!
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
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

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <?php echo js_tag('js/bc-swipe.js'); ?>
    <?php echo js_tag('main.js'); ?>

    <?php include 'inc/gtm-body.php'; ?>

    <!-- Botão Voltar ao Topo -->
    <?php include 'inc/back-to-top.php'; ?>

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
    </style>
    
    <!-- Fix para remover barra branca entre conteúdo e footer -->
    <style>
    .page-section {
        padding-bottom: 0 !important;
        margin-bottom: 0 !important;
    }
    .site-footer {
        margin-top: 0 !important;
    }
    </style>
</body>

</html>

