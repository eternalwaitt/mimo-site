<?php
/**
 * Página 404 Personalizada
 * 
 * Desenvolvido por: Victor Penter
 * Versão: <?php echo APP_VERSION ?? '2.1.0'; ?>
 * 
 * Página de erro 404 com humor e otimizada para SEO
 */

// IMPORTANTE: Cache headers DEVEM ser os PRIMEIROS headers enviados
// Carregar configuração primeiro (necessário para ASSET_VERSION)
require_once 'config.php';

// Cache headers para páginas HTML (ANTES de qualquer outro header)
require_once 'inc/cache-headers.php';
set_html_cache_headers();

// Cabeçalhos de segurança (depois dos cache headers)
require_once 'inc/security-headers.php';

// Helper de SEO
require_once 'inc/seo-helper.php';

// Helper de imagem
require_once 'inc/image-helper.php';

// Asset helper para CSS/JS minificados
require_once 'inc/asset-helper.php';

// Definir status 404
http_response_code(404);
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
    $pageTitle = 'Página Não Encontrada - 404 | Mimo';
    $pageDescription = 'Ops! A página que você procura não foi encontrada. Mas não se preocupe, você ainda pode conhecer nossos serviços de beleza e estética em São Paulo!';
    $pageKeywords = '404, página não encontrada, mimo estética, são paulo';
    
    echo generate_seo_meta_tags($pageTitle, $pageDescription, $pageKeywords);
    echo generate_canonical_url();
    echo generate_open_graph_tags($pageTitle, $pageDescription, 'img/logobranco1.png');
    echo generate_twitter_cards($pageTitle, $pageDescription, 'img/logobranco1.png');
    ?>
    
    <!-- Resource Hints -->
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="dns-prefetch" href="https://stackpath.bootstrapcdn.com">
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=EB+Garamond:400,400i,700i" rel="stylesheet">
    <!-- Akrobat font loaded via CSS @font-face in product.css -->
    
    <!-- Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Lucide Icons - Lightweight replacement for Font Awesome -->
    <script src="https://cdn.jsdelivr.net/npm/lucide@0.263.1/dist/umd/lucide.js"></script>
    
    <!-- CSS Variables (deve vir antes de product.css) -->
    <link rel="stylesheet" href="<?php echo get_css_asset('css/modules/_variables.css'); ?>">
    
    <!-- Custom CSS -->
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
    
    /* Footer social links já usam SVG inline - sem necessidade de Font Awesome */
    </style>
    
    <style>
        .error-404 {
            min-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f5f5 0%, #ffffff 100%);
            padding: 4rem 1rem;
        }
        .error-404-content {
            text-align: center;
            max-width: 1000px;
            width: 100%;
        }
        .error-404 h1 {
            font-size: 8rem;
            font-weight: 700;
            color: #ccb7bc;
            margin-bottom: 1rem;
            line-height: 1;
            font-family: 'EB Garamond', serif;
        }
        .error-404 h2 {
            font-size: 2rem;
            color: #3a505a;
            margin-bottom: 1.5rem;
            font-weight: 400;
        }
        .error-404 p {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .error-404 .btn {
            padding: 12px 40px;
            font-size: 1.1rem;
            border-radius: 30px;
            margin: 0.5rem;
            transition: all 0.3s ease;
        }
        .error-404 .btn-primary {
            background-color: #ccb7bc;
            border-color: #ccb7bc;
            color: white;
        }
        .error-404 .btn-primary:hover {
            background-color: #b8a3a8;
            border-color: #b8a3a8;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .error-404 .btn-outline-secondary {
            border-color: #3a505a;
            color: #3a505a;
        }
        .error-404 .btn-outline-secondary:hover {
            background-color: #3a505a;
            color: white;
            transform: translateY(-2px);
        }
        .error-404-services {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #e0e0e0;
            width: 100%;
        }
        .service-card-404 {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 25px 15px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-decoration: none;
            color: #3a505a;
            transition: all 0.3s ease;
            height: 100%;
            border: 2px solid transparent;
        }
        .service-card-404:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(204, 183, 188, 0.3);
            border-color: #ccb7bc;
            text-decoration: none;
            color: #3a505a;
        }
        .service-icon-404 {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ccb7bc, #b8a3a8);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        .service-card-404:hover .service-icon-404 {
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(204, 183, 188, 0.4);
        }
        .service-icon-404 i {
            font-size: 2rem;
            color: white;
        }
        .service-card-404 h4 {
            font-size: 1rem;
            font-weight: 600;
            margin: 0;
            color: #3a505a;
        }
        @media (max-width: 768px) {
            .error-404 h1 {
                font-size: 5rem;
            }
            .error-404 h2 {
                font-size: 1.5rem;
            }
            .error-404 p {
                font-size: 1rem;
            }
            .service-card-404 {
                padding: 20px 10px;
            }
            .service-icon-404 {
                width: 60px;
                height: 60px;
            }
            .service-icon-404 i {
                font-size: 1.5rem;
            }
            .service-card-404 h4 {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'inc/header.php'; ?>
    
    <div class="error-404">
        <div class="error-404-content">
            <h1>404</h1>
            <h2>Deixou a coroa cair, princesa? 👑</h2>
            <p>
                Ops! A página que você procura não foi encontrada. 
                Mas não se preocupe, aqui na Mimo sempre cuidamos de você! 
                Que tal conhecer nossos serviços?
            </p>
            
            <div>
                <a href="/" class="btn btn-primary">Voltar para Home</a>
                <a href="/#services" class="btn btn-outline-secondary">Ver Serviços</a>
            </div>
            
            <div class="error-404-services">
                <h3 style="color: #3a505a; margin-top: 3rem; margin-bottom: 2rem; font-size: 1.5rem;">Conheça Nossos Serviços</h3>
                <div class="row justify-content-center">
                    <div class="col-6 col-md-4 col-lg-3 mb-3">
                        <a href="/esmalteria/" class="service-card-404">
                            <div class="service-icon-404">
                                <?php echo lucide_icon('sparkles', 'mr-2', 24); ?>
                            </div>
                            <h4>Esmalteria</h4>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-3 mb-3">
                        <a href="/cilios/" class="service-card-404">
                            <div class="service-icon-404">
                                <?php echo lucide_icon('eye', 'mr-2', 24); ?>
                            </div>
                            <h4>Cílios e Design</h4>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-3 mb-3">
                        <a href="/estetica/" class="service-card-404">
                            <div class="service-icon-404">
                                <?php echo lucide_icon('sparkles', 'mr-2', 24); ?>
                            </div>
                            <h4>Estética Corporal</h4>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-3 mb-3">
                        <a href="/esteticafacial/" class="service-card-404">
                            <div class="service-icon-404">
                                <?php echo lucide_icon('smile', 'mr-2', 24); ?>
                            </div>
                            <h4>Estética Facial</h4>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-3 mb-3">
                        <a href="/micropigmentacao/" class="service-card-404">
                            <div class="service-icon-404">
                                <?php echo lucide_icon('palette', 'mr-2', 24); ?>
                            </div>
                            <h4>Micropigmentação</h4>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-3 mb-3">
                        <a href="/salao/" class="service-card-404">
                            <div class="service-icon-404">
                                <?php echo lucide_icon('scissors', 'mr-2', 24); ?>
                            </div>
                            <h4>Salão</h4>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <?php include 'inc/footer.php'; ?>
    
    <?php
    // Schema.org para 404 (opcional, mas ajuda SEO)
    echo generate_local_business_schema();
    ?>
    
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    
    <!-- Bootstrap Carousel Swipe Plugin -->
    <?php echo js_tag('js/bc-swipe.js'); ?>
    
    <!-- Main JS (navbar scroll behavior) -->
    <?php echo js_tag('main.js', ['defer' => true]); ?>
    <?php echo js_tag('js/dark-mode.js', ['defer' => false]); ?>
    
    <!-- Botão Voltar ao Topo -->
    <?php include 'inc/back-to-top.php'; ?>

    <script>
        // Forçar navbar com fundo desde o início em páginas internas
        (function() {
            const pageHero = document.querySelector('.page-hero, .error-404');
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

