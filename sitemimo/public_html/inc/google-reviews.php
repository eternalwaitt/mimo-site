<?php
/**
 * Google Reviews Helper
 * Busca reviews do Google My Business e gera Schema.org
 * 
 * Desenvolvido por: Victor Penter
 * Versão: 1.0.0
 */

/**
 * Busca reviews do Google Places API
 * 
 * @param string $placeId Google Place ID do negócio
 * @param string $apiKey Google Places API Key
 * @param int $minRating Rating mínimo (default: 4)
 * @param int $maxResults Número máximo de resultados (default: 10)
 * @return array|false Array de reviews ou false em caso de erro
 */
function get_google_reviews($placeId, $apiKey, $minRating = 4, $maxResults = 10) {
    // PRIORIDADE 1: Verificar se existe arquivo de reviews scraped (do script Python)
    // Este arquivo é gerado periodicamente pelo scraper e tem mais reviews
    $scrapedFile = __DIR__ . '/../cache/google_reviews_scraped.json';
    if (file_exists($scrapedFile)) {
        $scrapedData = json_decode(file_get_contents($scrapedFile), true);
        if ($scrapedData !== null && !empty($scrapedData)) {
            // Filtrar por rating mínimo (4 e 5 estrelas apenas)
            $filtered = array_filter($scrapedData, function($review) use ($minRating) {
                $rating = isset($review['rating']) ? (int)$review['rating'] : 0;
                return $rating >= $minRating && $rating <= 5;
            });
            
            // Reordenar os reviews scraped com a mesma lógica de prioridade
            // 1. Reviews com foto
            // 2. Rating (5 estrelas antes de 4)
            // 3. Comprimento do texto (mais longos primeiro)
            // 4. Mais antigos primeiro (para ter variedade temporal)
            usort($filtered, function($a, $b) {
                $aHasPhoto = isset($a['has_photo']) ? $a['has_photo'] : !empty($a['profile_photo']);
                $bHasPhoto = isset($b['has_photo']) ? $b['has_photo'] : !empty($b['profile_photo']);
                
                // Prioridade 1: Reviews com foto
                if ($aHasPhoto != $bHasPhoto) {
                    return $bHasPhoto ? 1 : -1;
                }
                
                // Prioridade 2: Rating (5 estrelas antes de 4)
                if ($a['rating'] != $b['rating']) {
                    return $b['rating'] - $a['rating'];
                }
                
                // Prioridade 3: Comprimento do texto (mais longos primeiro)
                $aLength = isset($a['text_length']) ? $a['text_length'] : mb_strlen($a['text'] ?? '');
                $bLength = isset($b['text_length']) ? $b['text_length'] : mb_strlen($b['text'] ?? '');
                if ($aLength != $bLength) {
                    return $bLength - $aLength;
                }
                
                // Prioridade 4: Mais antigos primeiro (para ter variedade temporal)
                $aTime = isset($a['time']) ? $a['time'] : 0;
                $bTime = isset($b['time']) ? $b['time'] : 0;
                return $aTime - $bTime;
            });
            
            $result = array_slice($filtered, 0, $maxResults);
            if (!empty($result)) {
                return array_values($result);
            }
        }
    }
    
    // PRIORIDADE 2: Usar cache da API (se existir e estiver válido)
    $cacheKey = 'google_reviews_' . md5($placeId . $minRating);
    $cacheFile = __DIR__ . '/../cache/' . $cacheKey . '.json';
    // Cache de 24 horas (reviews não mudam com frequência)
    // Isso reduz drasticamente o uso da API
    $cacheTime = 86400; // 24 horas
    
    // Verificar cache
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime) {
        $cached = json_decode(file_get_contents($cacheFile), true);
        if ($cached !== null && !empty($cached)) {
            // Se cache antigo não tem quality_score, recalcular e reordenar
            $needsReorder = false;
            foreach ($cached as &$review) {
                if (!isset($review['quality_score'])) {
                    $textLength = mb_strlen($review['text'] ?? '');
                    $words = preg_split('/\s+/u', trim($review['text'] ?? ''));
                    $wordCount = count(array_filter($words, function($word) {
                        return mb_strlen(trim($word)) > 0;
                    }));
                    $rating = $review['rating'] ?? 4;
                    $hasPhoto = !empty($review['profile_photo']);
                    $review['quality_score'] = ($textLength * 0.4) + ($wordCount * 2) + ($rating * 10);
                    if ($hasPhoto) {
                        $review['quality_score'] += 50;
                    }
                    $review['text_length'] = $textLength;
                    $review['word_count'] = $wordCount;
                    $review['has_photo'] = $hasPhoto;
                    $needsReorder = true;
                }
            }
            
            // Reordenar se necessário (mesma lógica: foto > rating > comprimento > mais antigos)
            if ($needsReorder) {
                usort($cached, function($a, $b) {
                    $aHasPhoto = isset($a['has_photo']) ? $a['has_photo'] : !empty($a['profile_photo']);
                    $bHasPhoto = isset($b['has_photo']) ? $b['has_photo'] : !empty($b['profile_photo']);
                    if ($aHasPhoto != $bHasPhoto) {
                        return $bHasPhoto ? 1 : -1;
                    }
                    if ($a['rating'] != $b['rating']) {
                        return $b['rating'] - $a['rating'];
                    }
                    $aLength = isset($a['text_length']) ? $a['text_length'] : 0;
                    $bLength = isset($b['text_length']) ? $b['text_length'] : 0;
                    if ($aLength != $bLength) {
                        return $bLength - $aLength;
                    }
                    // Mais antigos primeiro
                    $aTime = isset($a['time']) ? $a['time'] : 0;
                    $bTime = isset($b['time']) ? $b['time'] : 0;
                    return $aTime - $bTime;
                });
                // Salvar cache atualizado
                file_put_contents($cacheFile, json_encode($cached));
            }
            
            return $cached;
        }
    }
    
    // Usar Places API (New) - versão atual recomendada pelo Google
    // Mais barata e com melhor suporte
    // Endpoint: GET /v1/places/{placeId}
    $url = "https://places.googleapis.com/v1/places/" . urlencode($placeId) . "?languageCode=pt-BR";
    
    // Headers para Places API (New)
    $headers = [
        'Content-Type: application/json',
        'X-Goog-Api-Key: ' . $apiKey,
        'X-Goog-FieldMask: id,displayName,rating,userRatingCount,reviews'
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    // GET request (não POST)
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200 || !$response) {
        // Log do erro para debug
        error_log('Google Places API (New) Error - HTTP Code: ' . $httpCode);
        if ($response) {
            error_log('Response: ' . substr($response, 0, 500));
        }
        return false;
    }
    
    $data = json_decode($response, true);
    
    // Verificar se há erro na resposta
    if (isset($data['error'])) {
        error_log('Google Places API (New) Error: ' . json_encode($data['error']));
        return false;
    }
    
    if (!isset($data['reviews']) || empty($data['reviews'])) {
        // Pode não ter reviews ainda
        return [];
    }
    
    // Filtrar reviews por rating mínimo
    $reviews = [];
    foreach ($data['reviews'] as $review) {
        $rating = isset($review['rating']) ? (int)$review['rating'] : 0;
        
        // Filtrar APENAS 4 e 5 estrelas (nada além disso)
        if ($rating < 4 || $rating > 5) {
            continue;
        }
        
        if ($rating >= $minRating) {
            $authorName = 'Anônimo';
            if (isset($review['authorAttribution']['displayName'])) {
                $authorName = $review['authorAttribution']['displayName'];
            }
            
            $reviewText = '';
            if (isset($review['text']['text'])) {
                $reviewText = $review['text']['text'];
            }
            
            // Pular reviews sem texto ou muito curtos (menos de 20 caracteres)
            if (empty($reviewText) || mb_strlen($reviewText) < 20) {
                continue;
            }
            
            $publishTime = time();
            if (isset($review['publishTime'])) {
                $publishTime = strtotime($review['publishTime']);
            }
            
            $profilePhoto = null;
            if (isset($review['authorAttribution']['photoUri'])) {
                $profilePhoto = $review['authorAttribution']['photoUri'];
            }
            
            // Calcular "score de qualidade" do texto
            // Baseado em: comprimento, número de palavras, rating, e se tem foto
            $textLength = mb_strlen($reviewText);
            // Contar palavras considerando acentuação (mb_split funciona melhor que str_word_count)
            $words = preg_split('/\s+/u', trim($reviewText));
            $wordCount = count(array_filter($words, function($word) {
                return mb_strlen(trim($word)) > 0;
            }));
            
            // Verificar se tem foto de perfil
            $hasPhoto = !empty($profilePhoto);
            
            // Score base: comprimento (40%) + palavras (20%) + rating (40%)
            $qualityScore = ($textLength * 0.4) + ($wordCount * 2) + ($rating * 10);
            
            // Bonus significativo para reviews com foto
            if ($hasPhoto) {
                $qualityScore += 50;
            }
            
            $reviews[] = [
                'author' => $authorName,
                'rating' => $rating,
                'text' => $reviewText,
                'time' => $publishTime,
                'profile_photo' => $profilePhoto,
                'quality_score' => $qualityScore,
                'text_length' => $textLength,
                'word_count' => $wordCount,
                'has_photo' => $hasPhoto
            ];
        }
    }
    
    // Ordenar por qualidade com prioridades:
    // 1. Reviews com foto (tem foto antes de não tem)
    // 2. Rating (5 estrelas antes de 4)
    // 3. Comprimento do texto (mais longos primeiro)
    usort($reviews, function($a, $b) {
        // Prioridade 1: Reviews com foto
        $aHasPhoto = isset($a['has_photo']) ? $a['has_photo'] : !empty($a['profile_photo']);
        $bHasPhoto = isset($b['has_photo']) ? $b['has_photo'] : !empty($b['profile_photo']);
        if ($aHasPhoto != $bHasPhoto) {
            return $bHasPhoto ? 1 : -1; // True (tem foto) vem antes
        }
        
        // Prioridade 2: Rating (5 estrelas antes de 4)
        if ($a['rating'] != $b['rating']) {
            return $b['rating'] - $a['rating'];
        }
        
        // Prioridade 3: Comprimento do texto (mais longos primeiro)
        return $b['text_length'] - $a['text_length'];
    });
    
    // Limitar quantidade
    $reviews = array_slice($reviews, 0, $maxResults);
    
    // Salvar no cache
    if (!is_dir(__DIR__ . '/../cache')) {
        mkdir(__DIR__ . '/../cache', 0755, true);
    }
    file_put_contents($cacheFile, json_encode($reviews));
    
    return $reviews;
}

/**
 * Gera Schema.org AggregateRating
 * 
 * @param float $rating Rating médio (ex: 4.8)
 * @param int $reviewCount Número total de reviews
 * @return string JSON-LD schema
 */
function generate_aggregate_rating_schema($rating, $reviewCount) {
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'AggregateRating',
        'ratingValue' => (float)$rating,
        'reviewCount' => (int)$reviewCount,
        'bestRating' => 5,
        'worstRating' => 1
    ];
    
    return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
}

/**
 * Gera Schema.org Review individual
 * 
 * @param array $review Dados do review
 * @param string $businessName Nome do negócio
 * @return string JSON-LD schema
 */
function generate_review_schema($review, $businessName) {
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Review',
        'itemReviewed' => [
            '@type' => 'LocalBusiness',
            'name' => $businessName
        ],
        'author' => [
            '@type' => 'Person',
            'name' => $review['author']
        ],
        'reviewRating' => [
            '@type' => 'Rating',
            'ratingValue' => $review['rating'],
            'bestRating' => 5,
            'worstRating' => 1
        ],
        'reviewBody' => $review['text']
    ];
    
    if (isset($review['time'])) {
        $schema['datePublished'] = date('c', $review['time']);
    }
    
    return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
}

/**
 * Gera HTML para exibir reviews
 * 
 * @param array $reviews Array de reviews
 * @param bool $showSchema Se deve incluir Schema.org
 * @return string HTML
 */
function render_google_reviews($reviews, $showSchema = true) {
    if (empty($reviews)) {
        return '';
    }
    
    $html = '<div class="google-reviews">';
    
    foreach ($reviews as $index => $review) {
        $html .= '<div class="review-item" itemscope itemtype="https://schema.org/Review">';
        
        // Rating stars
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $review['rating']) {
                $stars .= '<i class="fas fa-star text-warning"></i>';
            } else {
                $stars .= '<i class="far fa-star text-muted"></i>';
            }
        }
        
        $html .= '<div class="review-rating" itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">';
        $html .= '<meta itemprop="ratingValue" content="' . $review['rating'] . '">';
        $html .= '<meta itemprop="bestRating" content="5">';
        $html .= '<meta itemprop="worstRating" content="1">';
        $html .= $stars;
        $html .= '</div>';
        
        // Author
        $html .= '<div class="review-author" itemprop="author" itemscope itemtype="https://schema.org/Person">';
        $html .= '<strong itemprop="name">' . htmlspecialchars($review['author']) . '</strong>';
        $html .= '</div>';
        
        // Text
        $html .= '<div class="review-text" itemprop="reviewBody">';
        $html .= '<p>' . nl2br(htmlspecialchars($review['text'])) . '</p>';
        $html .= '</div>';
        
        // Date
        if (isset($review['time'])) {
            $html .= '<div class="review-date">';
            $html .= '<small class="text-muted">' . date('d/m/Y', $review['time']) . '</small>';
            $html .= '</div>';
        }
        
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    return $html;
}

