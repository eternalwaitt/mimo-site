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

// Helper de ícones Lucide
require_once 'inc/icon-helper.php';

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

    <!-- Script loader for deferred CSS - Must come before deferred resources -->
    <script>
    /*! loadCSS. [c]2017 Filament Group, Inc. MIT License */
    (function(w){"use strict";if(!w.loadCSS){w.loadCSS=function(href,media,rel){var ss=w.document.createElement("link");var ref;if(rel){ref=w.document.getElementsByTagName(rel)[0];}else{var refs=w.document.getElementsByTagName("script");ref=refs[refs.length-1];}ss.rel="stylesheet";ss.href=href;ss.media="only x";function onloadcssdefined(cb){for(var i=0;i<w.document.styleSheets.length;i++){if(w.document.styleSheets[i].href===ss.href){return cb();}}setTimeout(function(){onloadcssdefined(cb);});}onloadcssdefined(function(){ss.media=media||"all";});if(ref){ref.parentNode.insertBefore(ss,ref.nextSibling);}else{w.document.head.appendChild(ss);}return ss;};}})(this);
    </script>

    <!-- Bootstrap CSS - Defer -->
    <script>loadCSS("bootstrap/bootstrap/dist/css/bootstrap.min.css?<?php echo ASSET_VERSION; ?>");</script>
    <noscript><link rel="stylesheet" href="bootstrap/bootstrap/dist/css/bootstrap.min.css?<?php echo ASSET_VERSION; ?>"></noscript>

    <!-- Lucide Icons - Defer para não bloquear render (padronizado com homepage) -->
    <script src="https://cdn.jsdelivr.net/npm/lucide@0.263.1/dist/umd/lucide.js" defer></script>

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
    
    <!-- Critical CSS for header - Always compact, only logo animates -->
    <style>
    /* Header sempre compacto - apenas logo anima */
    .navbar {
        padding-top: 8px !important;
        padding-bottom: 8px !important;
        background-color: rgba(45, 45, 45, 0.95) !important;
        backdrop-filter: blur(10px) !important;
        -webkit-backdrop-filter: blur(10px) !important;
        box-shadow: rgba(0, 0, 0, 0.15) 2px 2px 4px 0px !important;
        transition: background-color 0.3s ease, backdrop-filter 0.3s ease, box-shadow 0.3s ease !important;
    }
    .navbar.compressed {
        padding-top: 8px !important;
        padding-bottom: 8px !important;
        background-color: rgba(45, 45, 45, 0.95) !important;
        backdrop-filter: blur(10px) !important;
        -webkit-backdrop-filter: blur(10px) !important;
        box-shadow: rgba(0, 0, 0, 0.15) 2px 2px 4px 0px !important;
    }
    /* Logo inicial - maior */
    .logonav {
        height: 55px !important;
        max-width: 150px !important;
        transition: height 0.3s ease, max-width 0.3s ease, transform 0.3s ease !important;
    }
    /* Logo comprimido - menor */
    .navbar.compressed .logonav {
        height: 28px !important;
        max-width: 100px !important;
    }
    .navbar-nav .nav-link {
        color: #ffffff !important;
    }
    
    .site-footer .footer-social-link {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 42px !important;
        height: 42px !important;
    }
    
    /* Footer social links já usam SVG inline - sem necessidade de Font Awesome */
    
    /* CLS Fix: Vaga cards e containers - prevenir layout shift */
    .vaga-card {
        contain: layout style !important; /* Prevenir layout shift */
        min-height: 300px !important; /* Reservar espaço para conteúdo */
    }
    
    .vaga-info,
    .vaga-requirements,
    .vaga-description {
        contain: layout !important;
        min-height: 50px !important; /* Reservar espaço mínimo */
    }
    
    .vaga-info-item {
        contain: layout !important;
        min-height: 1.5em !important; /* Reservar espaço para texto */
    }
    </style>

    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png?20211226">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png?20211226">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png?20211226">
    <link rel="manifest" href="/manifest.json">


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
                                'Organização de parcerias: organizar e acompanhar o cronograma de colaborações e produções, assegurando eficiência e harmonia no processo.',
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
                                ' . lucide_icon('briefcase', 'mr-2', 24) . '
                                <h3>Não há vagas disponíveis no momento</h3>
                                <p>Mas estamos sempre em busca de novos talentos! Envie seu currículo para <a href="mailto:atendimento@minhamimo.com.br">atendimento@minhamimo.com.br</a></p>
                              </div>';
                    } else {
                        // Mostrar cada vaga
                        foreach ($vagas as $vaga) {
                            echo '<div class="vaga-card">';
                            echo '<h3 class="vaga-title">' . htmlspecialchars($vaga['titulo']) . '</h3>';
                            
                            echo '<div class="vaga-info">';
                            echo '<div class="vaga-info-item">' . lucide_icon('briefcase', 'mr-2', 18) . ' <strong>Tipo:</strong> ' . htmlspecialchars($vaga['tipo']) . '</div>';
                            echo '<div class="vaga-info-item">' . lucide_icon('map-pin', 'mr-2', 18) . ' <strong>Localização:</strong> ' . htmlspecialchars($vaga['localizacao']) . '</div>';
                            echo '<div class="vaga-info-item">' . lucide_icon('dollar-sign', 'mr-2', 18) . ' <strong>Salário:</strong> ' . htmlspecialchars($vaga['salario']) . '</div>';
                            echo '</div>';
                            
                            // Sobre a Mimo
                            if (!empty($vaga['sobre_mimo'])) {
                                echo '<div class="vaga-requirements vaga-about-mimo">';
                                echo '<h4>' . lucide_icon('building', 'mr-2', 18) . ' Sobre a Mimo</h4>';
                                echo '<p>' . htmlspecialchars($vaga['sobre_mimo']) . '</p>';
                                echo '</div>';
                            }
                            
                            // Sobre a Vaga
                            if (!empty($vaga['sobre_vaga'])) {
                                echo '<div class="vaga-description">';
                                echo '<h4>' . lucide_icon('info', 'mr-2', 18) . ' Sobre a Vaga</h4>';
                                echo '<p>' . htmlspecialchars($vaga['sobre_vaga']) . '</p>';
                                echo '</div>';
                            }
                            
                            // Atividades Exercidas
                            if (!empty($vaga['atividades'])) {
                                echo '<div class="vaga-requirements">';
                                echo '<h4>' . lucide_icon('list-checks', 'mr-2', 18) . ' Atividades Exercidas</h4>';
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
                                echo '<h4>' . lucide_icon('graduation-cap', 'mr-2', 18) . ' Experiência e Formação</h4>';
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
                                echo '<h4>' . lucide_icon('star', 'mr-2', 18) . ' Competências</h4>';
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
                                echo '<h4>' . lucide_icon('heart', 'mr-2', 18) . ' Nosso Jeito de Trabalhar</h4>';
                                echo '<p class="italic">' . htmlspecialchars($vaga['nosso_jeito']) . '</p>';
                                echo '</div>';
                            }
                            
                            echo '<div class="text-center mt-5">';
                            echo '<a href="mailto:atendimento@minhamimo.com.br?subject=Candidatura - ' . urlencode($vaga['titulo']) . '" class="btn-candidatar">';
                            echo lucide_icon('send', 'mr-2', 18) . ' Candidatar-se';
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
                            <?php echo lucide_icon('info', 'mr-2', 18); ?> <strong>Dica:</strong> Inclua uma carta de apresentação contando por que você gostaria de fazer parte da equipe Mimo!
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'inc/footer.php'; ?>

    <!-- Scripts -->
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
    <?php echo js_tag('js/bc-swipe.js', ['defer' => true]); ?>
    <?php echo js_tag('main.js', ['defer' => true]); ?>
    <?php echo js_tag('js/dark-mode.js', ['defer' => false]); ?>

    <?php include 'inc/gtm-body.php'; ?>

    <!-- Botão Voltar ao Topo -->
    <?php include 'inc/back-to-top.php'; ?>

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

        // Initial call
        handleNavbarScroll();

        // Listen to scroll events
        window.addEventListener('scroll', handleNavbarScroll, { passive: true });
        window.addEventListener('DOMContentLoaded', handleNavbarScroll);
        window.addEventListener('load', handleNavbarScroll);

        // Polling fallback for programmatic scrolls or if events are missed
        var lastScrollTop = 0;
        var checkInterval = setInterval(function() {
            var currentScroll = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
            if (Math.abs(currentScroll - lastScrollTop) > 0) { // Only trigger if scroll position actually changed
                lastScrollTop = currentScroll;
                handleNavbarScroll();
            }
        }, 50); // Check every 50ms for responsiveness

        // Clear interval on page unload to prevent memory leaks
        window.addEventListener('beforeunload', function() {
            clearInterval(checkInterval);
        });
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
    <!-- Inicializar Lucide Icons após carregar -->
    <script>
        // Inicializar Lucide Icons após DOM ready e script carregar
        function initLucideIcons() {
            if (typeof lucide !== "undefined") {
                lucide.createIcons();
            } else {
                // Se ainda não carregou, tentar novamente após um delay
                setTimeout(initLucideIcons, 100);
            }
        }
        
        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", initLucideIcons);
        } else {
            initLucideIcons();
        }
    </script>

</body>

</html>

