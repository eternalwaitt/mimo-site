<?php
/**
 * Validação de Imagens - Performance
 * 
 * Valida se todas as imagens têm dimensões explícitas
 * e estão usando picture_webp() corretamente
 * 
 * USO:
 * php scripts/validate-images.php
 */

$rootPath = dirname(__DIR__);
$errors = [];
$warnings = [];

// Arquivos PHP para verificar
$phpFiles = [
    $rootPath . '/index.php',
    $rootPath . '/contato.php',
    $rootPath . '/vagas.php',
    $rootPath . '/404.php',
    $rootPath . '/inc/service-template.php',
];

// Verificar cada arquivo
foreach ($phpFiles as $file) {
    if (!file_exists($file)) {
        continue;
    }
    
    $content = file_get_contents($file);
    
    // Verificar se há tags <img> sem picture_webp()
    if (preg_match_all('/<img[^>]+>/i', $content, $matches)) {
        foreach ($matches[0] as $imgTag) {
            // Verificar se não está dentro de <picture>
            if (strpos($content, '<picture>') === false || 
                strpos($content, 'picture_webp') === false) {
                $warnings[] = sprintf(
                    "Arquivo %s: Tag <img> encontrada sem picture_webp()",
                    basename($file)
                );
            }
            
            // Verificar se tem width e height
            if (!preg_match('/width\s*=/i', $imgTag) || 
                !preg_match('/height\s*=/i', $imgTag)) {
                $errors[] = sprintf(
                    "Arquivo %s: Imagem sem width/height: %s",
                    basename($file),
                    substr($imgTag, 0, 50)
                );
            }
        }
    }
    
    // Verificar se picture_webp() está sendo usado
    if (strpos($content, 'picture_webp') !== false) {
        // Verificar se está passando width/height
        if (!preg_match('/picture_webp\([^)]+\[.*width.*height/i', $content)) {
            $warnings[] = sprintf(
                "Arquivo %s: picture_webp() pode não ter width/height explícitos",
                basename($file)
            );
        }
    }
}

// Exibir resultados
echo "=== Validação de Imagens ===\n\n";

if (empty($errors) && empty($warnings)) {
    echo "✅ Todas as imagens estão corretas!\n";
    exit(0);
}

if (!empty($errors)) {
    echo "❌ ERROS ENCONTRADOS:\n";
    foreach ($errors as $error) {
        echo "  - $error\n";
    }
    echo "\n";
}

if (!empty($warnings)) {
    echo "⚠️  AVISOS:\n";
    foreach ($warnings as $warning) {
        echo "  - $warning\n";
    }
    echo "\n";
}

exit(!empty($errors) ? 1 : 0);

