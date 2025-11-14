<?php
/**
 * Site Mimo - Vagas Disponíveis
 * 
 * Desenvolvido por: Victor Penter
 * Versão: 2.2.4
 * 
 * Página para exibir vagas disponíveis na MIMO Estética
 */

// Suprimir avisos de depreciação (compatibilidade PHP 8.4)
error_reporting(E_ALL & ~E_DEPRECATED);

// Cabeçalhos de segurança (devem estar antes de qualquer saída HTML)
require_once 'inc/security-headers.php';

// Carregar configuração para versionamento de assets
require_once 'config.php';

// Cache headers para páginas HTML
require_once 'inc/cache-headers.php';
set_html_cache_headers();

// Funções auxiliares de imagem para suporte WebP
require_once 'inc/image-helper.php';

// Helper de SEO
require_once 'inc/seo-helper.php';

// Asset helper para CSS/JS minificados
require_once 'inc/asset-helper.php';

// Breadcrumbs helper
require_once 'inc/breadcrumbs.php';

// Definir variáveis para SEO
$pageTitle = 'Vagas Disponíveis - MIMO Estética | Trabalhe Conosco';
$pageDescription = 'Venha fazer parte da equipe MIMO Estética! Confira as vagas disponíveis e trabalhe em um ambiente acolhedor e profissional.';
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
    <link rel="stylesheet" type="text/css" href="form/css/font-awesome.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Custom styles for this template -->
    <?php echo css_tag('product.css'); ?>

    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png?20211226">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png?20211226">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png?20211226">
    <link rel="manifest" href="favicon/site.webmanifest">

    <style>
        .vagas-hero {
            background: linear-gradient(135deg, rgba(204, 183, 188, 0.9), rgba(58, 80, 90, 0.9)), url('/img/bgheader.jpg');
            background-size: cover;
            background-position: center;
            height: 40vh;
            min-height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
        }

        .vagas-hero h1 {
            font-size: 3rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            margin-bottom: 1rem;
        }

        .vagas-hero p {
            font-size: 1.2rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        .vaga-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .vaga-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .vaga-title {
            color: #3a505a;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            border-bottom: 2px solid #ccb7bc;
            padding-bottom: 10px;
        }

        .vaga-info {
            margin-bottom: 15px;
        }

        .vaga-info-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            color: #666;
        }

        .vaga-info-item i {
            color: #ccb7bc;
            margin-right: 10px;
            width: 20px;
        }

        .vaga-description {
            color: #555;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .vaga-requirements {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .vaga-requirements h4 {
            color: #3a505a;
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .vaga-requirements ul {
            margin: 0;
            padding-left: 20px;
        }

        .vaga-requirements li {
            color: #666;
            margin-bottom: 5px;
        }

        .btn-candidatar {
            background: linear-gradient(135deg, #ccb7bc, #b8a3a8);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-candidatar:hover {
            background: linear-gradient(135deg, #b8a3a8, #ccb7bc);
            transform: scale(1.05);
            color: white;
            text-decoration: none;
        }

        .no-vagas {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .no-vagas i {
            font-size: 4rem;
            color: #ccb7bc;
            margin-bottom: 20px;
        }

        .no-vagas h3 {
            color: #3a505a;
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .vagas-hero h1 {
                font-size: 2rem;
            }

            .vagas-hero p {
                font-size: 1rem;
            }

            .vaga-card {
                padding: 20px;
            }
        }
    </style>

    <?php include 'inc/gtm-head.php'; ?>
</head>

<body>

    <?php include "inc/header.php"; ?>

    <!-- Hero Section -->
    <div class="vagas-hero">
        <div>
            <h1 class="Akrobat">TRABALHE CONOSCO</h1>
            <p>Faça parte da equipe MIMO Estética</p>
        </div>
    </div>

    <!-- Breadcrumbs -->
    <?php
    echo generate_service_breadcrumbs('Vagas', 'vagas.php');
    ?>

    <!-- Vagas Section -->
    <section class="py-5" style="background: #f8f9fa;">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="text-center mb-5" style="color: #3a505a; font-weight: 700;">
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
                            echo '<div class="vaga-info-item"><i class="fas fa-clock"></i> <strong>Tipo:</strong> ' . htmlspecialchars($vaga['tipo']) . '</div>';
                            echo '<div class="vaga-info-item"><i class="fas fa-map-marker-alt"></i> <strong>Localização:</strong> ' . htmlspecialchars($vaga['localizacao']) . '</div>';
                            echo '<div class="vaga-info-item"><i class="fas fa-dollar-sign"></i> <strong>Salário:</strong> ' . htmlspecialchars($vaga['salario']) . '</div>';
                            echo '</div>';
                            
                            // Sobre a Mimo
                            if (!empty($vaga['sobre_mimo'])) {
                                echo '<div class="vaga-requirements" style="background: #f0f4f8; border-left: 4px solid #ccb7bc;">';
                                echo '<h4 style="color: #3a505a; margin-bottom: 10px;"><i class="fas fa-building" style="color: #ccb7bc; margin-right: 8px;"></i> Sobre a Mimo</h4>';
                                echo '<p style="color: #555; margin: 0; line-height: 1.6;">' . htmlspecialchars($vaga['sobre_mimo']) . '</p>';
                                echo '</div>';
                            }
                            
                            // Sobre a Vaga
                            if (!empty($vaga['sobre_vaga'])) {
                                echo '<div class="vaga-description" style="margin-top: 20px;">';
                                echo '<h4 style="color: #3a505a; margin-bottom: 15px;"><i class="fas fa-info-circle" style="color: #ccb7bc; margin-right: 8px;"></i> Sobre a Vaga</h4>';
                                echo '<p style="color: #555; line-height: 1.6;">' . htmlspecialchars($vaga['sobre_vaga']) . '</p>';
                                echo '</div>';
                            }
                            
                            // Atividades Exercidas
                            if (!empty($vaga['atividades'])) {
                                echo '<div class="vaga-requirements" style="margin-top: 20px;">';
                                echo '<h4 style="color: #3a505a; margin-bottom: 15px;"><i class="fas fa-tasks" style="color: #ccb7bc; margin-right: 8px;"></i> Atividades Exercidas</h4>';
                                echo '<ul style="margin: 0; padding-left: 20px;">';
                                foreach ($vaga['atividades'] as $atividade) {
                                    echo '<li style="color: #666; margin-bottom: 10px; line-height: 1.6;">' . htmlspecialchars($atividade) . '</li>';
                                }
                                echo '</ul>';
                                echo '</div>';
                            }
                            
                            // Experiência e Formação
                            if (!empty($vaga['experiencia'])) {
                                echo '<div class="vaga-requirements" style="margin-top: 20px; background: #fff3e0;">';
                                echo '<h4 style="color: #3a505a; margin-bottom: 15px;"><i class="fas fa-graduation-cap" style="color: #ccb7bc; margin-right: 8px;"></i> Experiência e Formação</h4>';
                                echo '<ul style="margin: 0; padding-left: 20px;">';
                                foreach ($vaga['experiencia'] as $exp) {
                                    echo '<li style="color: #666; margin-bottom: 8px; line-height: 1.6;">' . htmlspecialchars($exp) . '</li>';
                                }
                                echo '</ul>';
                                echo '</div>';
                            }
                            
                            // Competências
                            if (!empty($vaga['competencias'])) {
                                echo '<div class="vaga-requirements" style="margin-top: 20px; background: #e8f5e9;">';
                                echo '<h4 style="color: #3a505a; margin-bottom: 15px;"><i class="fas fa-star" style="color: #ccb7bc; margin-right: 8px;"></i> Competências</h4>';
                                echo '<ul style="margin: 0; padding-left: 20px;">';
                                foreach ($vaga['competencias'] as $competencia) {
                                    echo '<li style="color: #666; margin-bottom: 8px; line-height: 1.6;">' . htmlspecialchars($competencia) . '</li>';
                                }
                                echo '</ul>';
                                echo '</div>';
                            }
                            
                            // Nosso Jeito de Trabalhar
                            if (!empty($vaga['nosso_jeito'])) {
                                echo '<div class="vaga-requirements" style="margin-top: 20px; background: #f3e5f5; border-left: 4px solid #ccb7bc;">';
                                echo '<h4 style="color: #3a505a; margin-bottom: 15px;"><i class="fas fa-heart" style="color: #ccb7bc; margin-right: 8px;"></i> Nosso Jeito de Trabalhar</h4>';
                                echo '<p style="color: #555; margin: 0; line-height: 1.6; font-style: italic;">' . htmlspecialchars($vaga['nosso_jeito']) . '</p>';
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
                    <div class="vaga-card" style="background: linear-gradient(135deg, #ccb7bc, #b8a3a8); color: white;">
                        <h3 style="color: white; border-bottom-color: white;">Como se candidatar</h3>
                        <p style="color: white; margin-bottom: 15px;">
                            Envie seu currículo para <strong>atendimento@minhamimo.com.br</strong> com o assunto contendo o nome da vaga de interesse.
                        </p>
                        <p style="color: white; margin-bottom: 0;">
                            <i class="fas fa-info-circle"></i> <strong>Dica:</strong> Inclua uma carta de apresentação contando por que você gostaria de fazer parte da equipe MIMO!
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <?php include 'inc/back-to-top.php'; ?>

    <!-- Scripts -->
    <script src="bootstrap/jquery/dist/jquery.min.js"></script>
    <script src="bootstrap/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo js_tag('main.js'); ?>

    <?php include 'inc/gtm-body.php'; ?>
</body>

</html>

