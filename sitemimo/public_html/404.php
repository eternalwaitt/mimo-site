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
    <link href="Akrobat-Regular.woff" rel="stylesheet">
    
    <!-- Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    
    <!-- Custom CSS -->
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
                                <i class="fas fa-hand-sparkles"></i>
                            </div>
                            <h4>Esmalteria</h4>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-3 mb-3">
                        <a href="/cilios/" class="service-card-404">
                            <div class="service-icon-404">
                                <i class="fas fa-eye"></i>
                            </div>
                            <h4>Cílios e Design</h4>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-3 mb-3">
                        <a href="/estetica/" class="service-card-404">
                            <div class="service-icon-404">
                                <i class="fas fa-spa"></i>
                            </div>
                            <h4>Estética Corporal</h4>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-3 mb-3">
                        <a href="/esteticafacial/" class="service-card-404">
                            <div class="service-icon-404">
                                <i class="fas fa-smile"></i>
                            </div>
                            <h4>Estética Facial</h4>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-3 mb-3">
                        <a href="/micropigmentacao/" class="service-card-404">
                            <div class="service-icon-404">
                                <i class="fas fa-palette"></i>
                            </div>
                            <h4>Micropigmentação</h4>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-3 mb-3">
                        <a href="/salao/" class="service-card-404">
                            <div class="service-icon-404">
                                <i class="fas fa-cut"></i>
                            </div>
                            <h4>Salão</h4>
                        </a>
                    </div>
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
    // Schema.org para 404 (opcional, mas ajuda SEO)
    echo generate_local_business_schema();
    ?>
    
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    
    <!-- Main JS (navbar scroll behavior) -->
    <?php echo js_tag('main.js'); ?>
    
    <!-- Botão Voltar ao Topo -->
    <?php include 'inc/back-to-top.php'; ?>

    <script>
        // Forçar navbar com fundo desde o início em páginas internas
        $(document).ready(function() {
            if ($('.page-hero, .error-404').length > 0) {
                $('.navbar').addClass('compressed');
                $('.navbar-nav').addClass('changecolormenu');
                $('.navbar-brand').addClass('changecolorlogo');
            }
        });
    </script>
</body>
</html>

