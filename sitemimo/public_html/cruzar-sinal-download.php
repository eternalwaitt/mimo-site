<?php
/**
 * Download de arquivo gerado pelo cruzar-sinal
 */

$outputs_dir = __DIR__ . '/cruzar-sinal-outputs';

if (!isset($_GET['file']) || empty($_GET['file'])) {
    http_response_code(400);
    die('arquivo não especificado');
}

$filename = basename($_GET['file']);
$filepath = $outputs_dir . '/' . $filename;

// Validar que o arquivo existe e está no diretório correto
if (!file_exists($filepath) || !is_file($filepath)) {
    http_response_code(404);
    die('arquivo não encontrado');
}

// Validar que o arquivo está dentro do diretório de outputs (prevenir path traversal)
$real_filepath = realpath($filepath);
$real_outputs_dir = realpath($outputs_dir);
if (strpos($real_filepath, $real_outputs_dir) !== 0) {
    http_response_code(403);
    die('acesso negado');
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

