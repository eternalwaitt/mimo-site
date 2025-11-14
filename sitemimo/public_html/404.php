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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" crossorigin="anonymous">
    
    <!-- Custom CSS -->
    <?php echo css_tag('product.css'); ?>
    
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
    
    <footer class="container">
        <div class="row">
            <div class="col-12 col-md my-2 py-2">
                <small class="d-block text-muted text-center" style="line-height: 3;">
                    &copy; MIMO CENTRO DE BELEZA LTDA | 57.659.472/0001-78 | 2018 | Todos os direitos reservados
                </small>
            </div>
        </div>
    </footer>
    
    <?php
    // Schema.org para 404 (opcional, mas ajuda SEO)
    echo generate_local_business_schema();
    ?>
    
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>

