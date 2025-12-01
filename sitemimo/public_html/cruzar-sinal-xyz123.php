<?php
/**
 * Cruzar Sinal - Página Oculta
 * Cruza agendamentos com clientes que têm crédito ou débito
 * 
 * URL secreta: /cruzar-sinal-xyz123.php
 * Desenvolvido por: Victor Penter
 * Versão: 1.0.0
 */

// Carregar configuração
require_once __DIR__ . '/config.php';

// Autoloader do Composer para PhpSpreadsheet
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Carregar helpers
require_once __DIR__ . '/inc/asset-helper.php';
require_once __DIR__ . '/inc/seo-helper.php';
require_once __DIR__ . '/inc/cruzar-sinal/validacao.php';
require_once __DIR__ . '/inc/cruzar-sinal/cruzar-dados.php';

// Iniciar sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Diretórios
$uploads_dir = __DIR__ . '/cruzar-sinal-uploads';
$outputs_dir = __DIR__ . '/cruzar-sinal-outputs';

// Criar diretórios se não existirem
if (!is_dir($uploads_dir)) {
    mkdir($uploads_dir, 0755, true);
}
if (!is_dir($outputs_dir)) {
    mkdir($outputs_dir, 0755, true);
}

// Variáveis de estado
$erro_agendamentos = false;
$erro_credito_debito = false;
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
    // Processar arquivo de agendamentos
    if (isset($_FILES['arquivo_agendamentos']) && $_FILES['arquivo_agendamentos']['error'] === UPLOAD_ERR_OK) {
        $arquivo_agend = $_FILES['arquivo_agendamentos'];
        $filename_agendamentos = $arquivo_agend['name'];
        $arquivo_temp = $arquivo_agend['tmp_name'];
        
        // Ler arquivo
        $arquivo_bytes = file_get_contents($arquivo_temp);
        
        // Validar
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
        
        // Validar
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
    } elseif ($arquivo_cred_salvo && file_exists($arquivo_cred_salvo['path'])) {
        // Usar arquivo salvo
        $filename_credito_debito = $arquivo_cred_salvo['filename'];
    } else {
        $erro_credito_debito = true;
        $erros_credito_debito[] = 'arquivo de crédito/débito é obrigatório';
    }
    
    // Se ambos arquivos estão válidos, processar
    if (!$erro_agendamentos && !$erro_credito_debito && $arquivo_agend_salvo && $arquivo_cred_salvo) {
        try {
            $resultado = cruzar_dados($arquivo_agend_salvo['path'], $arquivo_cred_salvo['path']);
            
            // Salvar resultado
            $timestamp = date('Ymd_His');
            $filename_resultado = "cruzamento_{$timestamp}.xlsx";
            $arquivo_saida = $outputs_dir . '/' . $filename_resultado;
            
            salvar_resultado_excel($resultado['df_resultado'], $arquivo_saida);
            
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
            $erro_agendamentos = true;
            $erros_agendamentos[] = 'erro ao processar cruzamento: ' . $e->getMessage();
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
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="generator" content="Mimo Site v<?php echo APP_VERSION; ?>">
    <title>Cruzar Sinal - Mimo</title>
    
    <?php
    // SEO Meta Tags
    $pageTitle = 'Cruzar Sinal - Mimo';
    $pageDescription = 'Ferramenta para cruzar agendamentos com clientes que têm crédito ou débito';
    echo generate_seo_meta_tags($pageTitle, $pageDescription, '');
    ?>
    
    <!-- Bootstrap CSS -->
    <link rel="preload" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"></noscript>
    
    <!-- Fonts -->
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700&display=swap" rel="stylesheet"></noscript>
    
    <!-- CSS Principal -->
    <link rel="stylesheet" href="<?php echo get_css_asset('product.css'); ?>">
    
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
    <?php include 'inc/header-inner.php'; ?>
    
    <main id="main-content" style="padding-top: 70px;">
        <div class="cruzar-sinal-container">
            <header class="cruzar-sinal-header">
                <div>
                    <h1 class="cruzar-sinal-logo">MIMO</h1>
                    <p class="cruzar-sinal-subtitle">CENTRO DE BELEZA</p>
                </div>
                <h2 class="cruzar-sinal-title">Cruzar Sinal</h2>
                <p class="cruzar-sinal-description">Cruze agendamentos com clientes que têm crédito ou débito</p>
            </header>
            
            <div class="cruzar-sinal-main">
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
                    
                    <button type="submit" class="cruzar-sinal-btn cruzar-sinal-btn-primary">🚀 Processar</button>
                </form>
            </div>
        </div>
    </main>
    
    <?php include 'inc/footer.php'; ?>
    
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

