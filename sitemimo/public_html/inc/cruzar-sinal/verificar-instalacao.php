<?php
/**
 * Script de Verificação - Cruzar Sinal
 * 
 * Acesse: https://minhamimo.com.br/inc/cruzar-sinal/verificar-instalacao.php
 * 
 * IMPORTANTE: Remover este arquivo após verificação (segurança)
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Verificação - Cruzar Sinal</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .ok { color: green; }
        .erro { color: red; }
        .aviso { color: orange; }
        h1 { color: #333; }
        .check { margin: 10px 0; padding: 10px; background: white; border-left: 4px solid #ccc; }
        .check.ok { border-left-color: green; }
        .check.erro { border-left-color: red; }
        .check.aviso { border-left-color: orange; }
    </style>
</head>
<body>
    <h1>🔍 Verificação de Instalação - Cruzar Sinal</h1>
    
    <?php
    $erros = [];
    $avisos = [];
    $sucessos = [];
    
    // 1. Verificar PHP
    $php_version = phpversion();
    if (version_compare($php_version, '7.1.0', '>=')) {
        $sucessos[] = "PHP $php_version (OK)";
    } else {
        $erros[] = "PHP $php_version (requer 7.1+)";
    }
    
    // 2. Verificar extensões
    $extensoes_necessarias = ['zip', 'xml', 'gd'];
    foreach ($extensoes_necessarias as $ext) {
        if (extension_loaded($ext)) {
            $sucessos[] = "Extensão $ext instalada";
        } else {
            $avisos[] = "Extensão $ext não encontrada (pode ser necessária)";
        }
    }
    
    // 3. Verificar arquivos principais
    $arquivos_necessarios = [
        __DIR__ . '/../../cruzar-sinal-xyz123.php' => 'Página principal',
        __DIR__ . '/../../cruzar-sinal-download.php' => 'Endpoint de download',
        __DIR__ . '/validacao.php' => 'Validação',
        __DIR__ . '/cruzar-dados.php' => 'Cruzamento de dados',
    ];
    
    foreach ($arquivos_necessarios as $arquivo => $descricao) {
        if (file_exists($arquivo)) {
            $sucessos[] = "$descricao existe";
        } else {
            $erros[] = "$descricao não encontrado: " . basename($arquivo);
        }
    }
    
    // 4. Verificar Composer/vendor
    $vendor_autoload = __DIR__ . '/../../vendor/autoload.php';
    if (file_exists($vendor_autoload)) {
        $sucessos[] = "Composer autoloader encontrado";
        
        // Tentar carregar e verificar PhpSpreadsheet
        require_once $vendor_autoload;
        if (class_exists('PhpOffice\PhpSpreadsheet\IOFactory')) {
            $sucessos[] = "PhpSpreadsheet instalado e carregável";
        } else {
            $erros[] = "PhpSpreadsheet não encontrado (executar: composer install)";
        }
    } else {
        $erros[] = "vendor/autoload.php não encontrado (executar: composer install)";
    }
    
    // 5. Verificar diretórios
    $diretorios = [
        __DIR__ . '/../../cruzar-sinal-uploads' => 'Diretório de uploads',
        __DIR__ . '/../../cruzar-sinal-outputs' => 'Diretório de outputs',
    ];
    
    foreach ($diretorios as $dir => $descricao) {
        if (is_dir($dir)) {
            $perm = substr(sprintf('%o', fileperms($dir)), -4);
            if (is_writable($dir)) {
                $sucessos[] = "$descricao existe e é gravável (permissão: $perm)";
            } else {
                $erros[] = "$descricao existe mas não é gravável (permissão: $perm, requer 755)";
            }
        } else {
            $avisos[] = "$descricao não existe (será criado automaticamente)";
        }
    }
    
    // 6. Verificar permissões de escrita
    $test_dir = __DIR__ . '/../../cruzar-sinal-uploads';
    if (is_dir($test_dir)) {
        $test_file = $test_dir . '/.test';
        if (@file_put_contents($test_file, 'test')) {
            @unlink($test_file);
            $sucessos[] = "Permissão de escrita OK";
        } else {
            $erros[] = "Sem permissão de escrita nos diretórios";
        }
    }
    
    // Exibir resultados
    echo '<div class="check ok"><strong>✅ Sucessos:</strong><ul>';
    foreach ($sucessos as $msg) {
        echo "<li class='ok'>$msg</li>";
    }
    echo '</ul></div>';
    
    if (!empty($avisos)) {
        echo '<div class="check aviso"><strong>⚠️ Avisos:</strong><ul>';
        foreach ($avisos as $msg) {
            echo "<li class='aviso'>$msg</li>";
        }
        echo '</ul></div>';
    }
    
    if (!empty($erros)) {
        echo '<div class="check erro"><strong>❌ Erros:</strong><ul>';
        foreach ($erros as $msg) {
            echo "<li class='erro'>$msg</li>";
        }
        echo '</ul></div>';
    }
    
    // Resumo final
    echo '<hr>';
    if (empty($erros)) {
        echo '<h2 class="ok">✅ Instalação OK! A ferramenta deve funcionar.</h2>';
        echo '<p><a href="/cruzar-sinal-xyz123.php">→ Acessar ferramenta</a></p>';
    } else {
        echo '<h2 class="erro">❌ Instalação incompleta. Corrija os erros acima.</h2>';
    }
    ?>
    
    <hr>
    <p><small><strong>⚠️ IMPORTANTE:</strong> Remover este arquivo após verificação por segurança.</small></p>
</body>
</html>

