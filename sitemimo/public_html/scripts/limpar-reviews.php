<?php
/**
 * Script para limpar a base de reviews scraped
 * Remove: reviews de COVID, notas baixas, e reviews sem qualidade
 * 
 * Uso: php scripts/limpar-reviews.php
 */

require_once __DIR__ . '/../inc/google-reviews.php';

$scrapedFile = __DIR__ . '/../cache/google_reviews_scraped.json';

if (!file_exists($scrapedFile)) {
    echo "❌ Arquivo não encontrado: $scrapedFile\n";
    exit(1);
}

echo "📖 Lendo arquivo de reviews...\n";
$reviews = json_decode(file_get_contents($scrapedFile), true);

if ($reviews === null || !is_array($reviews)) {
    echo "❌ Erro ao ler arquivo JSON\n";
    exit(1);
}

$totalInicial = count($reviews);
echo "📊 Total de reviews inicial: $totalInicial\n\n";

// Contadores
$removidosCovid = 0;
$removidosNotaBaixa = 0;
$removidosSemTexto = 0;
$removidosTextoCurto = 0;

// Contadores adicionais
$removidosExcluidos = 0;

// Filtrar reviews
$reviewsLimpos = array_filter($reviews, function($review) use (&$removidosCovid, &$removidosNotaBaixa, &$removidosSemTexto, &$removidosTextoCurto, &$removidosExcluidos) {
    // 1. Remover reviews excluídos (conflito de interesse)
    if (review_should_be_excluded($review)) {
        $removidosExcluidos++;
        return false;
    }
    
    // 2. Remover reviews que mencionam COVID
    if (review_mentions_covid($review)) {
        $removidosCovid++;
        return false;
    }
    
    // 3. Remover reviews com nota baixa (menos de 4 estrelas)
    $rating = isset($review['rating']) ? (int)$review['rating'] : 0;
    if ($rating < 4) {
        $removidosNotaBaixa++;
        return false;
    }
    
    // 4. Remover reviews sem texto
    $text = '';
    
    // Verificar campo 'text' (formato padrão)
    if (isset($review['text'])) {
        $text = trim($review['text']);
    }
    
    // Verificar campo 'comment' (formato alternativo)
    if (isset($review['comment'])) {
        $text .= ' ' . trim($review['comment']);
        $text = trim($text);
    }
    
    // Verificar campo 'description' (formato do scraper Python)
    if (isset($review['description'])) {
        if (is_string($review['description'])) {
            $text .= ' ' . trim($review['description']);
        } elseif (is_array($review['description'])) {
            // Pode ter múltiplos idiomas, pegar o primeiro disponível
            foreach ($review['description'] as $lang => $desc) {
                if (!empty($desc)) {
                    $text .= ' ' . trim($desc);
                    break; // Pegar apenas o primeiro idioma disponível
                }
            }
        }
        $text = trim($text);
    }
    
    if (empty($text)) {
        $removidosSemTexto++;
        return false;
    }
    
    // 5. Remover reviews com texto muito curto (menos de 10 caracteres)
    if (mb_strlen($text) < 10) {
        $removidosTextoCurto++;
        return false;
    }
    
    return true;
});

$totalFinal = count($reviewsLimpos);
$totalRemovidos = $totalInicial - $totalFinal;

echo "🧹 Limpeza concluída!\n\n";
echo "📊 Estatísticas:\n";
echo "   - Reviews removidos (conflito de interesse): $removidosExcluidos\n";
echo "   - Reviews removidos por COVID: $removidosCovid\n";
echo "   - Reviews removidos por nota baixa (< 4): $removidosNotaBaixa\n";
echo "   - Reviews removidos sem texto: $removidosSemTexto\n";
echo "   - Reviews removidos texto muito curto (< 10 chars): $removidosTextoCurto\n";
echo "   - Total removido: $totalRemovidos\n";
echo "   - Total restante: $totalFinal\n\n";

// Fazer backup do arquivo original
$backupFile = $scrapedFile . '.backup.' . date('Y-m-d_His');
echo "💾 Criando backup: " . basename($backupFile) . "\n";
copy($scrapedFile, $backupFile);

// Salvar arquivo limpo
echo "💾 Salvando arquivo limpo...\n";
file_put_contents($scrapedFile, json_encode(array_values($reviewsLimpos), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

echo "✅ Concluído! Arquivo limpo salvo.\n";
echo "📁 Backup salvo em: " . basename($backupFile) . "\n";

