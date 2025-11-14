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
    $pageTitle = 'Página Não Encontrada - 404 | MIMO Estética';
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
    
    <!-- Custom CSS -->
    <link href="product.css?<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : '20211226'; ?>" rel="stylesheet">
    
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
            max-width: 600px;
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
        .error-404-links {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #e0e0e0;
        }
        .error-404-links a {
            color: #ccb7bc;
            text-decoration: none;
            margin: 0 1rem;
            font-size: 1rem;
            transition: color 0.3s ease;
        }
        .error-404-links a:hover {
            color: #b8a3a8;
            text-decoration: underline;
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
                Mas não se preocupe, aqui na MIMO Estética sempre cuidamos de você! 
                Que tal conhecer nossos serviços?
            </p>
            
            <div>
                <a href="/" class="btn btn-primary">Voltar para Home</a>
                <a href="/#services" class="btn btn-outline-secondary">Ver Serviços</a>
            </div>
            
            <div class="error-404-links">
                <a href="/esmalteria/">Esmalteria</a> |
                <a href="/cilios/">Cílios e Design</a> |
                <a href="/estetica/">Estética Corporal</a> |
                <a href="/esteticafacial/">Estética Facial</a> |
                <a href="/micropigmentacao/">Micropigmentação</a> |
                <a href="/salao/">Salão</a>
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

