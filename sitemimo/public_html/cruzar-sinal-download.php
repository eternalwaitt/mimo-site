<?php
/**
 * Download de arquivo gerado pelo cruzar-sinal
 */

$outputs_dir = __DIR__ . '/cruzar-sinal-outputs';

// Criar diretório se não existir
if (!is_dir($outputs_dir)) {
    if (!@mkdir($outputs_dir, 0755, true)) {
        http_response_code(500);
        die('erro: não foi possível criar diretório de outputs');
    }
}

if (!isset($_GET['file']) || empty($_GET['file'])) {
    http_response_code(400);
    die('arquivo não especificado');
}

$filename = basename($_GET['file']);

// Validação de segurança: prevenir path traversal
if (strpos($filename, '..') !== false || strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
    http_response_code(400);
    die('nome de arquivo inválido');
}

$filepath = $outputs_dir . '/' . $filename;

// Validar que o arquivo existe e está no diretório correto
if (!file_exists($filepath) || !is_file($filepath)) {
    http_response_code(404);
    die('arquivo não encontrado: ' . htmlspecialchars($filename));
}

// Validar que o arquivo está dentro do diretório de outputs (prevenir path traversal)
$real_filepath = realpath($filepath);
$real_outputs_dir = realpath($outputs_dir);

// Se realpath falhar, pode ser que o diretório não exista ou não tenha permissão
if ($real_filepath === false) {
    http_response_code(404);
    die('arquivo não encontrado ou sem permissão de acesso');
}

if ($real_outputs_dir === false) {
    http_response_code(500);
    die('erro: diretório de outputs não acessível');
}

// Validar que o caminho real do arquivo está dentro do diretório de outputs
if (strpos($real_filepath, $real_outputs_dir) !== 0) {
    http_response_code(403);
    die('acesso negado: arquivo fora do diretório permitido');
}

// Headers para download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($filepath));
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');

// Enviar arquivo
readfile($filepath);

// Limpar arquivo após download (opcional - pode manter por um tempo)
// unlink($filepath);

exit;

