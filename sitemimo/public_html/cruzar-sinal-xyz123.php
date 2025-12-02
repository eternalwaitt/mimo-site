<?php
/**
 * Cruzar Sinal - Página Oculta
 * Cruza agendamentos com clientes que têm crédito ou débito
 * 
 * URL secreta: /cruzar-sinal-xyz123.php
 * Desenvolvido por: Victor Penter
 * Versão: 1.0.0
 */

// LOG DE DEBUG - escrever em arquivo ANTES de qualquer coisa
// Tentar múltiplos locais para garantir que capturemos o erro
$debug_logs = [
    __DIR__ . '/cruzar-sinal-debug.log',
    sys_get_temp_dir() . '/cruzar-sinal-debug.log',
    '/tmp/cruzar-sinal-debug.log'
];

$debug_start = "[" . date('Y-m-d H:i:s') . "] INICIO - PHP " . phpversion() . " - Arquivo: " . __FILE__ . "\n";
foreach ($debug_logs as $log_path) {
    @file_put_contents($log_path, $debug_start);
}

// Habilitar TODOS os erros ANTES de qualquer coisa
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);

// Log em múltiplos locais
foreach ($debug_logs as $log_path) {
    @file_put_contents($log_path, "[" . date('Y-m-d H:i:s') . "] Erros habilitados\n", FILE_APPEND);
}

// Definir constantes necessárias sem depender de config.php
if (!defined('APP_VERSION')) {
    define('APP_VERSION', '2.6.13');
}

// NÃO carregar config.php - pode causar timeout/loops
// Definir constantes diretamente

// Iniciar sessão ANTES de qualquer output
try {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
} catch (Throwable $e) {
    error_log('Erro ao iniciar sessão: ' . $e->getMessage());
    // Continuar mesmo se sessão falhar
}

// Headers primeiro
if (!headers_sent()) {
    header('Content-Type: text/html; charset=utf-8');
    foreach ($debug_logs as $log_path) {
        @file_put_contents($log_path, "[" . date('Y-m-d H:i:s') . "] Headers enviados\n", FILE_APPEND);
    }
}

// Limpar qualquer output buffer existente
while (ob_get_level()) {
    ob_end_clean();
}

// Output imediato SEM buffering para debug
echo "<!-- DEBUG: INICIO -->\n";
foreach ($debug_logs as $log_path) {
    @file_put_contents($log_path, "[" . date('Y-m-d H:i:s') . "] Primeiro echo executado\n", FILE_APPEND);
}
flush();

// Iniciar output buffering DEPOIS do primeiro output
ob_start();
foreach ($debug_logs as $log_path) {
    @file_put_contents($log_path, "[" . date('Y-m-d H:i:s') . "] Output buffering iniciado\n", FILE_APPEND);
}

// Shutdown function DESABILITADO temporariamente para ver erros
// register_shutdown_function removido para debug

// Autoloader do Composer para PhpSpreadsheet
$phpspreadsheet_loaded = false;
$error_details = [];

// Verificar versão do PHP primeiro
foreach ($debug_logs as $log_path) {
    @file_put_contents($log_path, "[" . date('Y-m-d H:i:s') . "] Verificando versão PHP\n", FILE_APPEND);
}
$php_version = phpversion();
$php_version_ok = version_compare($php_version, '8.0.0', '>=');
foreach ($debug_logs as $log_path) {
    @file_put_contents($log_path, "[" . date('Y-m-d H:i:s') . "] PHP Version: $php_version, OK: " . ($php_version_ok ? 'SIM' : 'NÃO') . "\n", FILE_APPEND);
}

// Verificar se vendor/autoload.php existe E se PHP é compatível
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    $error_details[] = 'vendor/autoload.php não encontrado';
    $error_details[] = 'Caminho verificado: ' . __DIR__ . '/vendor/autoload.php';
    $error_details[] = 'Diretório vendor existe: ' . (is_dir(__DIR__ . '/vendor') ? 'SIM' : 'NÃO');
} elseif (!$php_version_ok) {
    $error_details[] = 'PHP ' . $php_version . ' detectado. PhpSpreadsheet requer PHP >= 8.0.0';
    $error_details[] = 'Atualize a versão do PHP no painel cPanel para PHP 8.0 ou superior';
} else {
    // PHP é compatível, tentar carregar
    try {
        require_once __DIR__ . '/vendor/autoload.php';
        $phpspreadsheet_loaded = class_exists('PhpOffice\PhpSpreadsheet\IOFactory');
        
        if (!$phpspreadsheet_loaded) {
            $error_details[] = 'vendor/autoload.php existe mas PhpSpreadsheet não foi carregado';
        }
    } catch (Throwable $e) {
        $error_details[] = 'Erro ao carregar vendor/autoload.php: ' . $e->getMessage();
    }
}

// Verificar se PhpSpreadsheet está disponível
// Não bloquear o carregamento da página - só mostrar aviso se necessário
$phpspreadsheet_error = !$phpspreadsheet_loaded;
// Continuar carregando a página mesmo sem PhpSpreadsheet - o erro será mostrado no formulário

// Definir funções helper diretamente (não depender de includes externos)
if (!function_exists('get_css_asset')) {
    function get_css_asset($file) {
        // Versão simples sem minificação
        return $file . '?v=' . (defined('APP_VERSION') ? APP_VERSION : '2.6.13');
    }
}
if (!function_exists('generate_seo_meta_tags')) {
    function generate_seo_meta_tags($title, $description, $keywords = '') {
        $html = '<meta name="description" content="' . htmlspecialchars($description) . '">';
        if ($keywords) {
            $html .= '<meta name="keywords" content="' . htmlspecialchars($keywords) . '">';
        }
        return $html;
    }
}

// Só carregar funções de cruzar-sinal se PhpSpreadsheet estiver disponível
// IMPORTANTE: Não carregar includes se PhpSpreadsheet não estiver disponível
// para evitar erros fatais que impedem a página de carregar
if ($phpspreadsheet_loaded) {
    echo "<!-- DEBUG: PhpSpreadsheet carregado, tentando carregar includes -->\n";
    flush();
    if (ob_get_level()) {
        ob_flush();
    }
    
    try {
        $validacao_path = __DIR__ . '/inc/cruzar-sinal/validacao.php';
        if (!file_exists($validacao_path)) {
            throw new Exception('Arquivo inc/cruzar-sinal/validacao.php não encontrado');
        }
        require_once $validacao_path;
        echo "<!-- DEBUG: validacao.php carregado -->\n";
        
        $cruzar_dados_path = __DIR__ . '/inc/cruzar-sinal/cruzar-dados.php';
        if (!file_exists($cruzar_dados_path)) {
            throw new Exception('Arquivo inc/cruzar-sinal/cruzar-dados.php não encontrado');
        }
        require_once $cruzar_dados_path;
        echo "<!-- DEBUG: cruzar-dados.php carregado -->\n";
        
        flush();
        if (ob_get_level()) {
            ob_flush();
        }
    } catch (Throwable $e) {
        // Se houver erro ao carregar, desabilitar PhpSpreadsheet mas continuar
        $phpspreadsheet_loaded = false;
        $error_details[] = 'Erro ao carregar funções de cruzar-sinal: ' . $e->getMessage();
        error_log('Erro ao carregar includes cruzar-sinal: ' . $e->getMessage());
        echo "<!-- DEBUG: Erro ao carregar includes: " . htmlspecialchars($e->getMessage()) . " -->\n";
        flush();
        if (ob_get_level()) {
            ob_flush();
        }
    }
} else {
    echo "<!-- DEBUG: PhpSpreadsheet não disponível, pulando includes -->\n";
    flush();
    if (ob_get_level()) {
        ob_flush();
    }
}

// Diretórios
$uploads_dir = __DIR__ . '/cruzar-sinal-uploads';
$outputs_dir = __DIR__ . '/cruzar-sinal-outputs';

// Criar diretórios se não existirem e verificar permissões
if (!is_dir($uploads_dir)) {
    if (!@mkdir($uploads_dir, 0755, true)) {
        $error_details[] = 'Não foi possível criar diretório de uploads: ' . $uploads_dir;
    }
}
if (!is_dir($outputs_dir)) {
    if (!@mkdir($outputs_dir, 0755, true)) {
        $error_details[] = 'Não foi possível criar diretório de outputs: ' . $outputs_dir;
    }
}

// Verificar permissões de escrita
$uploads_writable = is_dir($uploads_dir) && is_writable($uploads_dir);
$outputs_writable = is_dir($outputs_dir) && is_writable($outputs_dir);

if (!$uploads_writable) {
    $error_details[] = 'Diretório de uploads não é gravável: ' . $uploads_dir;
}
if (!$outputs_writable) {
    $error_details[] = 'Diretório de outputs não é gravável: ' . $outputs_dir;
}

// Variáveis de estado
$erro_agendamentos = false;
$erro_credito_debito = false;
$erro_geral = false;
$mensagem_erro_geral = '';
$erros_agendamentos = [];
$erros_credito_debito = [];
$filename_agendamentos = '';
$filename_credito_debito = '';
$sucesso = false;
$filename_resultado = '';
$estatisticas = null;
$arquivo_agend_salvo = $_SESSION['arquivo_agendamentos_validado'] ?? null;
$arquivo_cred_salvo = $_SESSION['arquivo_credito_debito_validado'] ?? null;

// Processar upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['processar'])) {
    // Verificar se PhpSpreadsheet está disponível antes de processar
    if (!$phpspreadsheet_loaded) {
        $erro_geral = true;
        $mensagem_erro_geral = 'PhpSpreadsheet não está instalado. Execute: composer install --no-dev --optimize-autoloader no servidor ou faça upload do diretório vendor/ completo.';
    } else {
        // Processar arquivo de agendamentos
        if (isset($_FILES['arquivo_agendamentos']) && $_FILES['arquivo_agendamentos']['error'] === UPLOAD_ERR_OK) {
            $arquivo_agend = $_FILES['arquivo_agendamentos'];
            $filename_agendamentos = $arquivo_agend['name'];
            $arquivo_temp = $arquivo_agend['tmp_name'];
            
            // Ler arquivo
            $arquivo_bytes = file_get_contents($arquivo_temp);
            
            // Validar (verificar se função existe)
            if (!function_exists('validar_arquivo_agendamentos')) {
                $erro_agendamentos = true;
                $erros_agendamentos[] = 'função de validação não disponível (PhpSpreadsheet não instalado)';
            } else {
                $resultado_validacao = validar_arquivo_agendamentos($arquivo_bytes, $filename_agendamentos);
        
                if (!$resultado_validacao['valido']) {
                    $erro_agendamentos = true;
                    $erros_agendamentos = $resultado_validacao['erros'];
                } else {
                    // Salvar arquivo validado
                    $arquivo_path = $uploads_dir . '/' . uniqid() . '_' . $filename_agendamentos;
                    move_uploaded_file($arquivo_temp, $arquivo_path);
                    $_SESSION['arquivo_agendamentos_validado'] = [
                        'filename' => $filename_agendamentos,
                        'path' => $arquivo_path
                    ];
                    $arquivo_agend_salvo = $_SESSION['arquivo_agendamentos_validado'];
                }
            }
        } elseif ($arquivo_agend_salvo && file_exists($arquivo_agend_salvo['path'])) {
            // Usar arquivo salvo
            $filename_agendamentos = $arquivo_agend_salvo['filename'];
        } else {
            $erro_agendamentos = true;
            $erros_agendamentos[] = 'arquivo de agendamentos é obrigatório';
        }
        
        // Processar arquivo de crédito/débito
        if (isset($_FILES['arquivo_credito_debito']) && $_FILES['arquivo_credito_debito']['error'] === UPLOAD_ERR_OK) {
            $arquivo_cred = $_FILES['arquivo_credito_debito'];
            $filename_credito_debito = $arquivo_cred['name'];
            $arquivo_temp = $arquivo_cred['tmp_name'];
            
            // Ler arquivo
            $arquivo_bytes = file_get_contents($arquivo_temp);
            
            // Validar (verificar se função existe)
            if (!function_exists('validar_arquivo_credito_debito')) {
                $erro_credito_debito = true;
                $erros_credito_debito[] = 'função de validação não disponível (PhpSpreadsheet não instalado)';
            } else {
                $resultado_validacao = validar_arquivo_credito_debito($arquivo_bytes, $filename_credito_debito);
        
                if (!$resultado_validacao['valido']) {
                    $erro_credito_debito = true;
                    $erros_credito_debito = $resultado_validacao['erros'];
                } else {
                    // Salvar arquivo validado
                    $arquivo_path = $uploads_dir . '/' . uniqid() . '_' . $filename_credito_debito;
                    move_uploaded_file($arquivo_temp, $arquivo_path);
                    $_SESSION['arquivo_credito_debito_validado'] = [
                        'filename' => $filename_credito_debito,
                        'path' => $arquivo_path
                    ];
                    $arquivo_cred_salvo = $_SESSION['arquivo_credito_debito_validado'];
                }
            }
        } elseif ($arquivo_cred_salvo && file_exists($arquivo_cred_salvo['path'])) {
            // Usar arquivo salvo
            $filename_credito_debito = $arquivo_cred_salvo['filename'];
        } else {
            $erro_credito_debito = true;
            $erros_credito_debito[] = 'arquivo de crédito/débito é obrigatório';
        }
        
        // Se ambos arquivos estão válidos, processar
        if (!$erro_agendamentos && !$erro_credito_debito && $arquivo_agend_salvo && $arquivo_cred_salvo) {
            // Verificar se PhpSpreadsheet está disponível antes de processar
            if (!$phpspreadsheet_loaded) {
                $erro_geral = true;
                $mensagem_erro_geral = 'PhpSpreadsheet não está instalado. Execute: composer install --no-dev --optimize-autoloader no servidor ou faça upload do diretório vendor/ completo.';
            } else {
                // Verificar se função existe
                if (!function_exists('cruzar_dados')) {
                    $erro_geral = true;
                    $mensagem_erro_geral = 'função cruzar_dados não disponível (PhpSpreadsheet não instalado)';
                } elseif (!function_exists('salvar_resultado_excel')) {
                    $erro_geral = true;
                    $mensagem_erro_geral = 'função salvar_resultado_excel não disponível (PhpSpreadsheet não instalado)';
                } else {
                    try {
                        $resultado = cruzar_dados($arquivo_agend_salvo['path'], $arquivo_cred_salvo['path']);
                        
                        // Garantir que o diretório de outputs existe e é gravável
                        if (!is_dir($outputs_dir)) {
                            if (!@mkdir($outputs_dir, 0755, true)) {
                                throw new Exception('Não foi possível criar diretório de outputs: ' . $outputs_dir);
                            }
                        }
                        if (!is_writable($outputs_dir)) {
                            throw new Exception('Diretório de outputs não é gravável: ' . $outputs_dir);
                        }
                        
                        // Salvar resultado
                        $timestamp = date('Ymd_His');
                        $filename_resultado = "cruzamento_{$timestamp}.xlsx";
                        $arquivo_saida = $outputs_dir . '/' . $filename_resultado;
                        
                        salvar_resultado_excel($resultado['df_resultado'], $arquivo_saida);
                        
                        // Verificar se o arquivo foi salvo
                        if (!file_exists($arquivo_saida)) {
                            throw new Exception('Arquivo não foi salvo: ' . $filename_resultado);
                        }
                        
                        $estatisticas = $resultado['estatisticas'];
                        $sucesso = true;
                        
                        // Limpar sessão
                        unset($_SESSION['arquivo_agendamentos_validado']);
                        unset($_SESSION['arquivo_credito_debito_validado']);
                        
                        // Limpar arquivos temporários
                        if (file_exists($arquivo_agend_salvo['path'])) {
                            unlink($arquivo_agend_salvo['path']);
                        }
                        if (file_exists($arquivo_cred_salvo['path'])) {
                            unlink($arquivo_cred_salvo['path']);
                        }
                    } catch (Exception $e) {
                        $erro_geral = true;
                        $mensagem_erro_geral = 'erro ao processar cruzamento: ' . $e->getMessage();
                    }
                }
            }
        }
    }
}

// Limpar arquivo salvo
if (isset($_GET['clear']) && $_GET['clear'] === 'agendamentos') {
    unset($_SESSION['arquivo_agendamentos_validado']);
    header('Location: cruzar-sinal-xyz123.php');
    exit;
}
if (isset($_GET['clear']) && $_GET['clear'] === 'credito_debito') {
    unset($_SESSION['arquivo_credito_debito_validado']);
    header('Location: cruzar-sinal-xyz123.php');
    exit;
}
// Debug: antes de iniciar HTML
echo "<!-- DEBUG: Iniciando HTML -->\n";
flush();
if (ob_get_level()) {
    ob_flush();
}
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="generator" content="Mimo Site v<?php echo defined('APP_VERSION') ? APP_VERSION : '2.6.13'; ?>">
    <title>Cruzar Sinal - Mimo</title>
    
    <?php
    // SEO Meta Tags
    try {
        $pageTitle = 'Cruzar Sinal - Mimo';
        $pageDescription = 'Ferramenta para cruzar agendamentos com clientes que têm crédito ou débito';
        if (function_exists('generate_seo_meta_tags')) {
            echo generate_seo_meta_tags($pageTitle, $pageDescription, '');
        } else {
            echo '<meta name="description" content="' . htmlspecialchars($pageDescription) . '">';
        }
    } catch (Exception $e) {
        echo '<!-- Erro ao gerar meta tags: ' . htmlspecialchars($e->getMessage()) . ' -->';
    }
    ?>
    
    <!-- Bootstrap CSS -->
    <link rel="preload" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"></noscript>
    
    <!-- Fonts -->
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700&display=swap" rel="stylesheet"></noscript>
    
    <!-- CSS Principal -->
    <?php
    try {
        if (function_exists('get_css_asset')) {
            echo '<link rel="stylesheet" href="' . htmlspecialchars(get_css_asset('product.css')) . '">';
        } else {
            echo '<link rel="stylesheet" href="product.css">';
        }
    } catch (Exception $e) {
        echo '<!-- Erro ao carregar CSS: ' . htmlspecialchars($e->getMessage()) . ' -->';
        echo '<link rel="stylesheet" href="product.css">';
    }
    ?>
    
    <!-- CSS específico do cruzar-sinal -->
    <style>
        /* Estilos específicos para cruzar-sinal */
        .cruzar-sinal-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            min-height: 100vh;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        
        .cruzar-sinal-header {
            background: #2c2c2c;
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        
        .cruzar-sinal-logo {
            font-family: 'Nunito', sans-serif;
            font-size: 3em;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }
        
        .cruzar-sinal-subtitle {
            font-family: 'Nunito', sans-serif;
            font-size: 0.9em;
            font-weight: 300;
            letter-spacing: 3px;
            color: #b0b0b0;
            text-transform: uppercase;
        }
        
        .cruzar-sinal-title {
            font-family: 'Nunito', sans-serif;
            font-size: 2em;
            font-weight: 400;
            margin: 20px 0 10px 0;
        }
        
        .cruzar-sinal-description {
            font-size: 1em;
            font-weight: 300;
            opacity: 0.9;
            color: #e0e0e0;
        }
        
        .cruzar-sinal-main {
            padding: 40px;
            background: white;
        }
        
        .cruzar-sinal-form {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .cruzar-sinal-form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .cruzar-sinal-form-group.has-error {
            margin-bottom: 35px;
        }
        
        .cruzar-sinal-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c2c2c;
            font-size: 1em;
        }
        
        .cruzar-sinal-label-text {
            display: block;
            margin-bottom: 4px;
            font-size: 1.1em;
        }
        
        .cruzar-sinal-label-hint {
            display: block;
            font-size: 0.85em;
            color: #666;
            font-weight: 300;
            margin-top: 4px;
        }
        
        .cruzar-sinal-file-input {
            width: 100%;
            padding: 14px;
            border: 2px dashed #999;
            border-radius: 6px;
            background: white;
            cursor: pointer;
            font-size: 1em;
            font-family: 'Nunito', sans-serif;
            transition: all 0.3s;
        }
        
        .cruzar-sinal-file-input:hover {
            border-color: #ccb7bc;
            background: #fff5f8;
        }
        
        .cruzar-sinal-file-input.error,
        .cruzar-sinal-form-group.has-error .cruzar-sinal-file-input {
            border-color: #e74c3c;
            border-width: 3px;
            background: #fff5f5;
        }
        
        .cruzar-sinal-field-error {
            margin-top: 10px;
            padding: 12px;
            background: #fee;
            border-left: 4px solid #e74c3c;
            border-radius: 4px;
            color: #c0392b;
            font-size: 0.9em;
        }
        
        .cruzar-sinal-field-error strong {
            display: block;
            margin-bottom: 8px;
            color: #a93226;
            font-weight: 600;
        }
        
        .cruzar-sinal-field-error ul {
            margin: 8px 0 0 20px;
            padding: 0;
        }
        
        .cruzar-sinal-field-error li {
            margin-bottom: 4px;
        }
        
        .cruzar-sinal-btn {
            display: inline-block;
            padding: 14px 28px;
            border: none;
            border-radius: 6px;
            font-size: 1.1em;
            font-weight: 600;
            font-family: 'Nunito', sans-serif;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
            text-align: center;
            width: 100%;
        }
        
        .cruzar-sinal-btn-primary {
            background: #ccb7bc;
            color: white;
        }
        
        .cruzar-sinal-btn-primary:hover {
            background: #b8a3a8;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(204, 183, 188, 0.4);
        }
        
        .cruzar-sinal-btn-download {
            background: #ccb7bc;
            color: white;
            padding: 16px 32px;
            font-size: 1.2em;
        }
        
        .cruzar-sinal-btn-download:hover {
            background: #b8a3a8;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(204, 183, 188, 0.4);
        }
        
        .cruzar-sinal-alert {
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .cruzar-sinal-alert-success {
            background: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
            text-align: center;
        }
        
        .cruzar-sinal-alert-success h3 {
            margin-bottom: 20px;
            font-size: 1.5em;
            font-weight: 600;
            color: #155724;
        }
        
        .cruzar-sinal-alert-error {
            background: #fee;
            border-left: 4px solid #e74c3c;
            color: #c0392b;
        }
        
        .cruzar-sinal-alert-error h3 {
            margin-bottom: 15px;
            font-size: 1.2em;
            font-weight: 600;
        }
        
        .cruzar-sinal-file-saved {
            margin-bottom: 10px;
            padding: 12px;
            background: #d4edda;
            border-left: 4px solid #28a745;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9em;
        }
        
        .cruzar-sinal-saved-icon {
            color: #28a745;
            font-weight: bold;
            font-size: 1.2em;
        }
        
        .cruzar-sinal-saved-text {
            flex: 1;
            color: #155724;
        }
        
        .cruzar-sinal-btn-clear {
            padding: 6px 14px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.85em;
            font-family: 'Nunito', sans-serif;
            font-weight: 600;
        }
        
        .cruzar-sinal-btn-clear:hover {
            background: #c82333;
        }
        
        @media (max-width: 600px) {
            .cruzar-sinal-header {
                padding: 30px 20px;
            }
            
            .cruzar-sinal-logo {
                font-size: 2.5em;
            }
            
            .cruzar-sinal-title {
                font-size: 1.5em;
            }
            
            .cruzar-sinal-main {
                padding: 20px;
            }
            
            .cruzar-sinal-form {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <?php 
    // Header simplificado (não depender de include externo)
    if (file_exists(__DIR__ . '/inc/header-inner.php')) {
        try {
            include 'inc/header-inner.php';
        } catch (Throwable $e) {
            echo '<!-- Erro ao carregar header: ' . htmlspecialchars($e->getMessage()) . ' -->';
        }
    }
    ?>
    
    <main id="main-content" style="padding-top: 70px;">
        <div class="cruzar-sinal-container">
            <header class="cruzar-sinal-header">
                <h2 class="cruzar-sinal-title">Cruzar Sinal</h2>
                <p class="cruzar-sinal-description">Cruze agendamentos com clientes que têm crédito ou débito</p>
            </header>
            
            <div class="cruzar-sinal-main">
                <?php if (!$phpspreadsheet_loaded): ?>
                    <div class="cruzar-sinal-alert cruzar-sinal-alert-error">
                        <h3>⚠️ PhpSpreadsheet não está instalado</h3>
                        <p>A ferramenta precisa do PhpSpreadsheet para processar arquivos Excel. Por favor, instale seguindo uma das opções abaixo:</p>
                        <div style="margin-top: 20px; padding: 15px; background: #fff; border-radius: 6px;">
                            <h4 style="margin-top: 0; color: #2c2c2c;">Opção 1: Upload via FTP (Recomendado)</h4>
                            <ol style="margin-left: 20px; line-height: 1.8;">
                                <li>Instale o PhpSpreadsheet localmente executando: <code>composer require phpoffice/phpspreadsheet</code></li>
                                <li>Faça upload da pasta <code>vendor/</code> completa para o servidor via FTP</li>
                                <li>Coloque a pasta <code>vendor/</code> na raiz do site (mesmo diretório onde está este arquivo)</li>
                                <li>Certifique-se de que o arquivo <code>vendor/autoload.php</code> existe</li>
                            </ol>
                            <p style="margin-top: 15px;"><strong>Credenciais FTP:</strong></p>
                            <ul style="margin-left: 20px; line-height: 1.8;">
                                <li>Host: <code>ftp.esteticamimo.hospedagemdesites.ws</code></li>
                                <li>Usuário: <code>esteticamimo</code></li>
                                <li>Diretório: <code>/home/esteticamimo/public_html/</code></li>
                            </ul>
                        </div>
                        <div style="margin-top: 20px; padding: 15px; background: #fff; border-radius: 6px;">
                            <h4 style="margin-top: 0; color: #2c2c2c;">Opção 2: Instalação via SSH (se tiver acesso)</h4>
                            <ol style="margin-left: 20px; line-height: 1.8;">
                                <li>Conecte-se ao servidor via SSH</li>
                                <li>Navegue até o diretório: <code>cd /home/esteticamimo/public_html</code></li>
                                <li>Execute: <code>composer require phpoffice/phpspreadsheet --no-dev --optimize-autoloader</code></li>
                            </ol>
                        </div>
                        <p style="margin-top: 20px; font-size: 0.9em; color: #666;">
                            <strong>Nota:</strong> Após instalar, recarregue esta página. Se o problema persistir, acesse 
                            <a href="cruzar-sinal-debug.php" style="color: #ccb7bc;">cruzar-sinal-debug.php</a> para verificar o status da instalação.
                        </p>
                    </div>
                <?php endif; ?>
                
                <?php if ($erro_geral && !empty($mensagem_erro_geral)): ?>
                    <div class="cruzar-sinal-alert cruzar-sinal-alert-error">
                        <h3>❌ Erro</h3>
                        <p><?php echo htmlspecialchars($mensagem_erro_geral); ?></p>
                    </div>
                <?php endif; ?>
                
                <?php if ($sucesso): ?>
                    <div class="cruzar-sinal-alert cruzar-sinal-alert-success">
                        <h3>✅ Processamento concluído!</h3>
                        <?php if ($estatisticas): ?>
                            <div style="text-align: left; margin: 20px 0;">
                                <p><strong>Total de agendamentos:</strong> <?php echo $estatisticas['total_agendamentos']; ?></p>
                                <p><strong>Com crédito/débito:</strong> <?php echo $estatisticas['com_credito_debito']; ?></p>
                                <p><strong>Sem crédito/débito:</strong> <?php echo $estatisticas['sem_credito_debito']; ?></p>
                                <p><strong>Confiança alta:</strong> <?php echo $estatisticas['confianca_alta']; ?></p>
                                <p><strong>Confiança média:</strong> <?php echo $estatisticas['confianca_media']; ?></p>
                                <p><strong>Crédito total:</strong> R$ <?php echo number_format($estatisticas['credito_total'], 2, ',', '.'); ?></p>
                                <p><strong>Débito total:</strong> R$ <?php echo number_format($estatisticas['debito_total'], 2, ',', '.'); ?></p>
                                <p><strong>Saldo líquido:</strong> R$ <?php echo number_format($estatisticas['saldo_liquido'], 2, ',', '.'); ?></p>
                            </div>
                        <?php endif; ?>
                        <div style="margin-top: 20px;">
                            <a href="cruzar-sinal-download.php?file=<?php echo urlencode($filename_resultado); ?>" class="cruzar-sinal-btn cruzar-sinal-btn-download">
                                📥 Baixar Relatório Excel
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
                
                <form method="POST" enctype="multipart/form-data" class="cruzar-sinal-form" id="uploadForm">
                    <input type="hidden" name="processar" value="1">
                    
                    <div class="cruzar-sinal-form-group <?php echo $erro_agendamentos ? 'has-error' : ''; ?>">
                        <label class="cruzar-sinal-label">
                            <span class="cruzar-sinal-label-text">📅 Arquivo de Agendamentos</span>
                            <span class="cruzar-sinal-label-hint">(0155 Agendamentos por quem os agendou e data de cadastro.xlsx)</span>
                        </label>
                        <?php if ($arquivo_agend_salvo): ?>
                            <div class="cruzar-sinal-file-saved">
                                <span class="cruzar-sinal-saved-icon">✓</span>
                                <span class="cruzar-sinal-saved-text">Arquivo salvo: <?php echo htmlspecialchars($arquivo_agend_salvo['filename']); ?></span>
                                <a href="?clear=agendamentos" class="cruzar-sinal-btn-clear">Limpar</a>
                            </div>
                        <?php endif; ?>
                        <input 
                            type="file" 
                            name="arquivo_agendamentos" 
                            accept=".xlsx,.xls"
                            <?php echo !$arquivo_agend_salvo ? 'required' : ''; ?>
                            class="cruzar-sinal-file-input <?php echo $erro_agendamentos ? 'error' : ''; ?>"
                        >
                        <?php if ($erro_agendamentos && !empty($erros_agendamentos)): ?>
                            <div class="cruzar-sinal-field-error">
                                <strong>Erro no arquivo "<?php echo htmlspecialchars($filename_agendamentos); ?>":</strong>
                                <ul>
                                    <?php foreach ($erros_agendamentos as $erro): ?>
                                        <li><?php echo htmlspecialchars($erro); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="cruzar-sinal-form-group <?php echo $erro_credito_debito ? 'has-error' : ''; ?>">
                        <label class="cruzar-sinal-label">
                            <span class="cruzar-sinal-label-text">💰 Arquivo de Crédito/Débito</span>
                            <span class="cruzar-sinal-label-hint">(0006 Clientes com credito e ou debito.xlsx)</span>
                        </label>
                        <?php if ($arquivo_cred_salvo): ?>
                            <div class="cruzar-sinal-file-saved">
                                <span class="cruzar-sinal-saved-icon">✓</span>
                                <span class="cruzar-sinal-saved-text">Arquivo salvo: <?php echo htmlspecialchars($arquivo_cred_salvo['filename']); ?></span>
                                <a href="?clear=credito_debito" class="cruzar-sinal-btn-clear">Limpar</a>
                            </div>
                        <?php endif; ?>
                        <input 
                            type="file" 
                            name="arquivo_credito_debito" 
                            accept=".xlsx,.xls"
                            <?php echo !$arquivo_cred_salvo ? 'required' : ''; ?>
                            class="cruzar-sinal-file-input <?php echo $erro_credito_debito ? 'error' : ''; ?>"
                        >
                        <?php if ($erro_credito_debito && !empty($erros_credito_debito)): ?>
                            <div class="cruzar-sinal-field-error">
                                <strong>Erro no arquivo "<?php echo htmlspecialchars($filename_credito_debito); ?>":</strong>
                                <ul>
                                    <?php foreach ($erros_credito_debito as $erro): ?>
                                        <li><?php echo htmlspecialchars($erro); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <button type="submit" class="cruzar-sinal-btn cruzar-sinal-btn-primary" <?php echo !$phpspreadsheet_loaded ? 'disabled' : ''; ?>>
                        <?php echo $phpspreadsheet_loaded ? '🚀 Processar' : '⚠️ Instale PhpSpreadsheet para processar'; ?>
                    </button>
                    <?php if (!$phpspreadsheet_loaded): ?>
                        <p style="margin-top: 15px; text-align: center; color: #666; font-size: 0.9em;">
                            O formulário está desabilitado até que o PhpSpreadsheet seja instalado.
                        </p>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </main>
    
    <?php 
    // Footer simplificado (não depender de include externo)
    if (file_exists(__DIR__ . '/inc/footer.php')) {
        try {
            include 'inc/footer.php';
        } catch (Throwable $e) {
            echo '<!-- Erro ao carregar footer: ' . htmlspecialchars($e->getMessage()) . ' -->';
        }
    }
    ?>
    
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        // Scroll até o primeiro campo com erro quando a página carregar
        document.addEventListener('DOMContentLoaded', function() {
            const firstError = document.querySelector('.cruzar-sinal-form-group.has-error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                const input = firstError.querySelector('input[type="file"]');
                if (input) {
                    setTimeout(() => {
                        input.focus();
                    }, 300);
                }
            }
        });
    </script>
</body>
</html>
<?php
// Debug: fim do arquivo
foreach ($debug_logs as $log_path) {
    @file_put_contents($log_path, "[" . date('Y-m-d H:i:s') . "] FIM DO ARQUIVO\n", FILE_APPEND);
}
echo "<!-- DEBUG: Fim do arquivo -->\n";
if (ob_get_level()) {
    ob_end_flush();
}
?>

