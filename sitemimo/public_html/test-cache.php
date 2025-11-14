<?php
/**
 * Test Cache Headers
 * Script para verificar se os headers de cache estão sendo aplicados corretamente
 * 
 * Acesse: https://minhamimo.com.br/test-cache.php
 */

// Desabilitar output buffering e garantir que não há output antes dos headers
while (ob_get_level()) {
    ob_end_clean();
}

// Limpar qualquer output que possa ter sido enviado
if (ob_get_contents()) {
    ob_clean();
}

// Verificar se headers já foram enviados ANTES de carregar qualquer coisa
$headersSentBefore = headers_sent($file, $line);

// Carregar configuração
require_once 'config.php';

// Verificar novamente após config.php
$headersSentAfterConfig = headers_sent($file2, $line2);

// Cache headers (mesma ordem que index.php)
require_once 'inc/cache-headers.php';

// Verificar antes de chamar set_html_cache_headers
$headersSentBeforeSet = headers_sent($file3, $line3);

// Tentar definir headers de cache
set_html_cache_headers();

// Verificar após chamar set_html_cache_headers
$headersSentAfterSet = headers_sent($file4, $line4);

// Security headers
require_once 'inc/security-headers.php';

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test Cache Headers - MIMO</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #d4a5a5;
            padding-bottom: 10px;
        }
        .status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
        }
        .status.ok {
            background: #4caf50;
            color: white;
        }
        .status.warning {
            background: #ff9800;
            color: white;
        }
        .status.error {
            background: #f44336;
            color: white;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f8f8f8;
            font-weight: bold;
            color: #555;
        }
        tr:hover {
            background: #f9f9f9;
        }
        .header-name {
            font-family: monospace;
            color: #2196F3;
            font-weight: bold;
        }
        .header-value {
            font-family: monospace;
            color: #666;
            word-break: break-all;
        }
        .info {
            background: #e3f2fd;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
        }
        .info strong {
            color: #1976D2;
        }
        .timestamp {
            color: #999;
            font-size: 12px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Test Cache Headers</h1>
        
        <div class="info">
            <strong>Objetivo:</strong> Verificar se os headers de cache estão sendo aplicados corretamente para bypassar o cache Varnish da Locaweb.<br><br>
            <strong>Nota:</strong> Se "Headers já enviados?" mostrar "SIM (NORMAL)", isso significa que os headers foram enviados quando o output HTML começou, o que é esperado. O importante é verificar se os headers de cache aparecem na tabela abaixo com status "OK".
        </div>

        <h2>Headers HTTP Enviados</h2>
        <table>
            <thead>
                <tr>
                    <th>Header</th>
                    <th>Valor</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $headers = headers_list();
                $importantHeaders = [
                    'Cache-Control' => ['private', 'no-cache', 'no-store', 'must-revalidate'],
                    'Pragma' => ['no-cache'],
                    'Expires' => ['Thu, 01 Jan 1970'],
                    'X-Accel-Expires' => ['0'],
                    'X-Cache-Status' => ['BYPASS'],
                    'ETag' => null, // Não deve existir
                    'Last-Modified' => null, // Não deve existir
                ];
                
                $foundHeaders = [];
                
                foreach ($headers as $header) {
                    if (strpos($header, ':') !== false) {
                        list($name, $value) = explode(':', $header, 2);
                        $name = trim($name);
                        $value = trim($value);
                        $foundHeaders[$name] = $value;
                        
                        $status = 'ok';
                        $statusText = 'OK';
                        
                        // Verificar se é um header importante
                        if (isset($importantHeaders[$name])) {
                            if ($importantHeaders[$name] === null) {
                                // Header não deve existir (ETag, Last-Modified)
                                $status = 'error';
                                $statusText = 'ERRO: Não deveria existir';
                            } else {
                                // Verificar se contém valores esperados
                                $found = false;
                                foreach ($importantHeaders[$name] as $expected) {
                                    if (stripos($value, $expected) !== false) {
                                        $found = true;
                                        break;
                                    }
                                }
                                if (!$found) {
                                    $status = 'warning';
                                    $statusText = 'AVISO: Valor não esperado';
                                }
                            }
                        }
                        
                        echo '<tr>';
                        echo '<td><span class="header-name">' . htmlspecialchars($name) . '</span></td>';
                        echo '<td><span class="header-value">' . htmlspecialchars($value) . '</span></td>';
                        echo '<td><span class="status ' . $status . '">' . $statusText . '</span></td>';
                        echo '</tr>';
                    }
                }
                
                // Verificar headers que deveriam existir mas não foram encontrados
                foreach ($importantHeaders as $name => $expected) {
                    if ($expected !== null && !isset($foundHeaders[$name])) {
                        echo '<tr>';
                        echo '<td><span class="header-name">' . htmlspecialchars($name) . '</span></td>';
                        echo '<td><span class="header-value"><em>não encontrado</em></span></td>';
                        echo '<td><span class="status error">ERRO: Header ausente</span></td>';
                        echo '</tr>';
                    }
                }
                ?>
            </tbody>
        </table>

        <h2>Informações do Servidor</h2>
        <table>
            <tr>
                <th>Item</th>
                <th>Valor</th>
            </tr>
            <tr>
                <td>ASSET_VERSION</td>
                <td><?php echo defined('ASSET_VERSION') ? ASSET_VERSION : '<em>não definido</em>'; ?></td>
            </tr>
            <tr>
                <td>APP_VERSION</td>
                <td><?php echo defined('APP_VERSION') ? APP_VERSION : '<em>não definido</em>'; ?></td>
            </tr>
            <tr>
                <td>Headers já enviados?</td>
                <td>
                    <?php 
                    if (headers_sent($debugFile, $debugLine)) {
                        // Se foi enviado no início do HTML, isso é NORMAL e esperado
                        $isNormal = (strpos($debugFile, 'test-cache.php') !== false && $debugLine >= 43);
                        if ($isNormal) {
                            echo '<span class="status ok">SIM (NORMAL)</span><br>';
                            echo '<small style="color: #666;">Headers enviados quando output HTML começou (linha ' . $debugLine . ')<br>';
                            echo 'Isso é esperado - os headers de cache foram definidos ANTES do output começar.</small>';
                        } else {
                            echo '<span class="status error">SIM (ERRO)</span><br>';
                            echo '<small style="color: #999;">Enviados em: ' . htmlspecialchars($debugFile) . ':' . $debugLine . '</small>';
                        }
                    } else {
                        echo '<span class="status ok">NÃO (OK)</span>';
                    }
                    ?>
                </td>
            </tr>
            <?php if (isset($headersSentBefore) || isset($headersSentAfterConfig) || isset($headersSentBeforeSet) || isset($headersSentAfterSet)): ?>
            <tr>
                <td>Debug: Headers enviados em</td>
                <td>
                    <small>
                        Antes de config.php: <?php echo $headersSentBefore ? 'SIM (' . htmlspecialchars($file ?? '') . ':' . ($line ?? '') . ')' : 'NÃO'; ?><br>
                        Após config.php: <?php echo $headersSentAfterConfig ? 'SIM (' . htmlspecialchars($file2 ?? '') . ':' . ($line2 ?? '') . ')' : 'NÃO'; ?><br>
                        Antes de set_html_cache_headers(): <?php echo $headersSentBeforeSet ? 'SIM (' . htmlspecialchars($file3 ?? '') . ':' . ($line3 ?? '') . ')' : 'NÃO'; ?><br>
                        Após set_html_cache_headers(): <?php echo $headersSentAfterSet ? 'SIM (' . htmlspecialchars($file4 ?? '') . ':' . ($line4 ?? '') . ')' : 'NÃO'; ?>
                    </small>
                </td>
            </tr>
            <?php endif; ?>
            <tr>
                <td>PHP Version</td>
                <td><?php echo PHP_VERSION; ?></td>
            </tr>
            <tr>
                <td>Server Software</td>
                <td><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'desconhecido'; ?></td>
            </tr>
            <tr>
                <td>Request URI</td>
                <td><?php echo htmlspecialchars($_SERVER['REQUEST_URI'] ?? ''); ?></td>
            </tr>
        </table>

        <div class="info">
            <strong>Como usar:</strong><br>
            1. Acesse esta página e verifique se todos os headers estão com status "OK"<br>
            2. Faça um hard refresh (Ctrl+Shift+F5) e verifique se a página atualiza<br>
            3. Se algum header estiver ausente ou com valor incorreto, verifique os arquivos:<br>
            &nbsp;&nbsp;- <code>inc/cache-headers.php</code><br>
            &nbsp;&nbsp;- <code>.htaccess</code><br>
            &nbsp;&nbsp;- <code>index.php</code> (ordem de carregamento)
        </div>

        <div class="timestamp">
            Última atualização: <?php echo date('Y-m-d H:i:s'); ?>
        </div>
    </div>
</body>
</html>

