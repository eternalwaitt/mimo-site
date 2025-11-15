<?php
/**
 * Google Reviews Helper
 * Busca reviews do Google My Business e gera Schema.org
 * 
 * Desenvolvido por: Victor Penter
 * Versão: 1.1.0
 * 
 * FUNCIONALIDADES:
 * - Busca reviews via Google Places API
 * - Prioriza reviews com foto, 5 estrelas, textos longos, mais antigos
 * - Gera Schema.org JSON-LD para reviews
 * - Suporta cache de reviews scraped (do script Python)
 * - Combina reviews da API com reviews manuais
 * 
 * ONDE É USADO:
 * - index.php (seção de depoimentos/testimonials)
 * - Incluído via: require_once 'inc/google-reviews.php';
 * 
 * PRIORIDADE DE REVIEWS:
 * 1. Reviews com foto de perfil
 * 2. Rating (5 estrelas antes de 4)
 * 3. Comprimento do texto (mais longos primeiro)
 * 4. Data (mais antigos primeiro, para variedade)
 * 
 * CACHE:
 * - Verifica primeiro: cache/google_reviews_scraped.json (do scraper Python)
 * - Fallback: Google Places API
 * - Reviews manuais sempre incluídos
 * 
 * CONFIGURAÇÃO:
 * - GOOGLE_PLACES_API_KEY: em config.php ou .env
 * - GOOGLE_PLACE_ID: em config.php ou .env
 */

/**
 * Verifica se um review tem uma foto REAL (não apenas placeholder com inicial)
 * 
 * @param array $review Review para verificar
 * @return bool true se tem foto real, false se é apenas placeholder
 */
function review_has_real_photo($review) {
    // Verificar se tem URL de foto
    $photoUrl = !empty($review['profile_photo']) ? $review['profile_photo'] : (!empty($review['profile_picture']) ? $review['profile_picture'] : null);
    
    if (empty($photoUrl)) {
        return false;
    }
    
    // PROBLEMA: O Google retorna URLs válidas mesmo para placeholders (apenas inicial do nome)
    // Não há como distinguir apenas pela URL se é placeholder ou foto real
    // 
    // SOLUÇÃO ULTRA-CONSERVADORA: Como não podemos distinguir com certeza, vamos ser extremamente
    // conservadores e considerar como foto real APENAS se tiver um padrão muito específico
    // que geralmente indica foto real (não placeholder)
    
    // URLs muito curtas (< 80 chars) geralmente são placeholders
    if (strlen($photoUrl) < 80) {
        return false;
    }
    
    // PADRÃO ESPECÍFICO: Análise dos dados mostra que:
    // - ALV-Uj: identificadores entre 49-52 chars (fotos reais geralmente > 50)
    // - ACg8oc: identificadores entre 48-50 chars (placeholders geralmente = 48)
    // Vamos priorizar APENAS identificadores longos que indicam foto real
    
    // Padrão 1: ALV-Uj com identificador >= 50 chars (fotos reais geralmente têm 50+)
    if (preg_match('/\/a-\/ALV-Uj([A-Za-z0-9_-]+)[^=]*=/', $photoUrl, $matches)) {
        $idLength = strlen($matches[1]);
        // Identificador >= 50 chars = provavelmente foto real
        if ($idLength >= 50) {
            return true;
        }
    }
    
    // Padrão 2: ACg8oc com identificador >= 49 chars (placeholders geralmente têm 48)
    // Ser MUITO conservador com ACg8oc pois pode ser placeholder
    if (preg_match('/\/a\/ACg8oc([A-Za-z0-9_-]+)[^=]*=/', $photoUrl, $matches)) {
        $idLength = strlen($matches[1]);
        // Identificador >= 49 chars = provavelmente foto real (não placeholder de 48)
        if ($idLength >= 49) {
            return true;
        }
    }
    
    // Por padrão, considerar como placeholder (não priorizar)
    // Isso é conservador mas reduz falsos positivos
    return false;
}

/**
 * Verifica se um review deve ser excluído (conflito de interesse)
 * 
 * @param array $review Review para verificar
 * @return bool true se deve ser excluído, false caso contrário
 */
function review_should_be_excluded($review) {
    // Remover reviews da Simone Costa (mãe da Lais - conflito de interesse)
    $author = '';
    if (isset($review['author'])) {
        $author = mb_strtolower(trim($review['author']));
    }
    
    // Verificar se é Simone Costa (várias variações possíveis)
    $excludedAuthors = [
        'simone costa',
        'simone c costa',
        'simone c. costa',
        'simone da costa',
        'simone de costa'
    ];
    
    foreach ($excludedAuthors as $excluded) {
        if (mb_strpos($author, $excluded) !== false) {
            return true;
        }
    }
    
    return false;
}

/**
 * Verifica se um review menciona COVID/pandemia
 * 
 * @param array $review Review para verificar
 * @return bool true se menciona COVID, false caso contrário
 */
function review_mentions_covid($review) {
    $text = '';
    
    // Verificar campo 'text' (formato padrão)
    if (isset($review['text'])) {
        $text = mb_strtolower($review['text']);
    }
    
    // Verificar campo 'comment' (formato alternativo)
    if (isset($review['comment'])) {
        $text .= ' ' . mb_strtolower($review['comment']);
    }
    
    // Verificar campo 'description' (formato do scraper Python)
    if (isset($review['description'])) {
        if (is_string($review['description'])) {
            $text .= ' ' . mb_strtolower($review['description']);
        } elseif (is_array($review['description'])) {
            // Pode ter múltiplos idiomas, verificar todos
            foreach ($review['description'] as $lang => $desc) {
                if (!empty($desc)) {
                    $text .= ' ' . mb_strtolower($desc);
                }
            }
        }
    }
    
    // Palavras-chave relacionadas a COVID
    $covidKeywords = [
        'covid', 'coronavírus', 'coronavirus', 'pandemia', 'pandêmico', 'pandemico',
        'quarentena', 'isolamento', 'lockdown', 'máscara', 'mascara', 'mascaras',
        'álcool em gel', 'alcool em gel', 'distanciamento social', 'vacina',
        'variante', 'delta', 'ômicron', 'omicron', 'sars-cov', 'sars cov'
    ];
    
    foreach ($covidKeywords as $keyword) {
        if (mb_strpos($text, $keyword) !== false) {
            return true;
        }
    }
    
    return false;
}

/**
 * Extrai o texto de um review (suporta múltiplos formatos)
 * 
 * @param array $review Review para extrair texto
 * @return string Texto do review
 */
function get_review_text($review) {
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
    
    return $text;
}

/**
 * Verifica se um review é dos últimos 2 anos
 * 
 * @param array $review Review para verificar
 * @return bool true se é dos últimos 2 anos, false caso contrário
 */
function review_is_recent($review) {
    $twoYearsAgo = time() - (2 * 365 * 24 * 60 * 60); // 2 anos em segundos
    
    // Verificar campo 'time' (timestamp)
    if (isset($review['time']) && $review['time'] > 0) {
        return $review['time'] >= $twoYearsAgo;
    }
    
    // Verificar campo 'time' como string (ISO date)
    if (isset($review['time']) && is_string($review['time'])) {
        $timestamp = strtotime($review['time']);
        if ($timestamp !== false) {
            return $timestamp >= $twoYearsAgo;
        }
    }
    
    // Se não tem data, considerar como antigo (não recente)
    return false;
}

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
    
    // Também verificar arquivos de cache da API que podem ter mais reviews
    $cachePattern = __DIR__ . '/../cache/google_reviews_*.json';
    $cacheFiles = glob($cachePattern);
    
    // Procurar arquivo scraped primeiro, depois outros caches
    $allReviews = [];
    
    if (file_exists($scrapedFile)) {
        $scrapedData = json_decode(file_get_contents($scrapedFile), true);
        if ($scrapedData !== null && !empty($scrapedData)) {
            // Normalizar campos de foto (scraper usa profile_picture, código usa profile_photo)
            foreach ($scrapedData as &$review) {
                // Mapear profile_picture para profile_photo se não existir
                if (isset($review['profile_picture']) && !isset($review['profile_photo'])) {
                    $review['profile_photo'] = $review['profile_picture'];
                }
                // Garantir que has_photo está definido
                if (!isset($review['has_photo'])) {
                    $review['has_photo'] = !empty($review['profile_photo']) || !empty($review['profile_picture']);
                }
            }
            unset($review); // Limpar referência
            
            // Filtrar por rating mínimo (4 e 5 estrelas apenas) e remover reviews indesejados
            $filtered = array_filter($scrapedData, function($review) use ($minRating) {
                $rating = isset($review['rating']) ? (int)$review['rating'] : 0;
                // Filtrar por rating
                if ($rating < $minRating || $rating > 5) {
                    return false;
                }
                // Remover reviews excluídos (conflito de interesse)
                if (review_should_be_excluded($review)) {
                    return false;
                }
                // Remover reviews que mencionam COVID
                if (review_mentions_covid($review)) {
                    return false;
                }
                return true;
            });
            
            // Reordenar os reviews scraped com prioridade para reviews melhores
            // 1. Reviews com foto de perfil (foto dá credibilidade) - MAIS IMPORTANTE
            // 2. Tamanho médio de texto (100-500 chars - nem muito curto, nem muito longo)
            // 3. Rating (5 estrelas antes de 4)
            // 4. Reviews dos últimos 2 anos primeiro (mas não limitar apenas a isso)
            // 5. Mais recentes primeiro (para ter conteúdo atualizado)
            usort($filtered, function($a, $b) {
                // Prioridade 1: Reviews com foto REAL de perfil (foto dá credibilidade) - MAIS IMPORTANTE
                // Não priorizar placeholders (apenas inicial do nome)
                $aHasRealPhoto = review_has_real_photo($a);
                $bHasRealPhoto = review_has_real_photo($b);
                if ($aHasRealPhoto != $bHasRealPhoto) {
                    return $bHasRealPhoto ? 1 : -1; // Com foto REAL primeiro
                }
                
                // Prioridade 2: Tamanho médio de texto (100-500 chars é ideal - credibilidade)
                $aLength = isset($a['text_length']) ? $a['text_length'] : mb_strlen(get_review_text($a));
                $bLength = isset($b['text_length']) ? $b['text_length'] : mb_strlen(get_review_text($b));
                
                // Função para calcular score de tamanho (tamanho médio = melhor)
                $getSizeScore = function($length) {
                    if ($length >= 100 && $length <= 500) {
                        return 3; // Tamanho médio ideal - melhor score
                    } elseif ($length >= 50 && $length < 100) {
                        return 2; // Curto mas aceitável
                    } elseif ($length > 500 && $length <= 1000) {
                        return 2; // Longo mas ainda bom
                    } elseif ($length > 1000) {
                        return 1; // Muito longo
                    } else {
                        return 0; // Muito curto
                    }
                };
                
                $aSizeScore = $getSizeScore($aLength);
                $bSizeScore = $getSizeScore($bLength);
                if ($aSizeScore != $bSizeScore) {
                    return $bSizeScore - $aSizeScore; // Melhor score primeiro
                }
                
                // Se mesmo score de tamanho, preferir tamanho médio dentro do range
                if ($aSizeScore == 3 && $bSizeScore == 3) {
                    // Dentro do range ideal, preferir mais próximo de 300 chars (meio do range)
                    $aDistance = abs($aLength - 300);
                    $bDistance = abs($bLength - 300);
                    if ($aDistance != $bDistance) {
                        return $aDistance - $bDistance; // Mais próximo de 300 primeiro
                    }
                }
                
                // Prioridade 3: Rating (5 estrelas antes de 4)
                $aRating = isset($a['rating']) ? (int)$a['rating'] : 0;
                $bRating = isset($b['rating']) ? (int)$b['rating'] : 0;
                if ($aRating != $bRating) {
                    return $bRating - $aRating; // 5 estrelas primeiro
                }
                
                // Prioridade 4: Reviews dos últimos 2 anos primeiro (mas não limitar)
                $aRecent = review_is_recent($a);
                $bRecent = review_is_recent($b);
                if ($aRecent != $bRecent) {
                    return $bRecent ? 1 : -1; // Recentes primeiro
                }
                
                // Prioridade 5: Mais recentes primeiro (para ter conteúdo atualizado)
                $aTime = isset($a['time']) ? $a['time'] : 0;
                $bTime = isset($b['time']) ? $b['time'] : 0;
                return $bTime - $aTime; // Mais recentes primeiro
            });
            
            // Adicionar aos reviews coletados (não limitar ainda)
            $allReviews = array_merge($allReviews, array_values($filtered));
        }
    }
    
    // Se não encontrou reviews scraped suficientes, verificar outros arquivos de cache
    if (count($allReviews) < $maxResults && !empty($cacheFiles)) {
        foreach ($cacheFiles as $cacheFile) {
            // Pular o arquivo scraped que já processamos
            if (basename($cacheFile) === 'google_reviews_scraped.json') {
                continue;
            }
            
            $cachedData = json_decode(file_get_contents($cacheFile), true);
            if ($cachedData !== null && !empty($cachedData) && is_array($cachedData)) {
                // Normalizar campos de foto (scraper usa profile_picture)
                foreach ($cachedData as &$review) {
                    if (isset($review['profile_picture']) && !isset($review['profile_photo'])) {
                        $review['profile_photo'] = $review['profile_picture'];
                    }
                    if (!isset($review['has_photo'])) {
                        $review['has_photo'] = !empty($review['profile_photo']) || !empty($review['profile_picture']);
                    }
                }
                unset($review); // Limpar referência
                
                // Filtrar por rating e remover reviews indesejados
                $filtered = array_filter($cachedData, function($review) use ($minRating) {
                    $rating = isset($review['rating']) ? (int)$review['rating'] : 0;
                    // Filtrar por rating
                    if ($rating < $minRating || $rating > 5) {
                        return false;
                    }
                    // Remover reviews excluídos (conflito de interesse)
                    if (review_should_be_excluded($review)) {
                        return false;
                    }
                    // Remover reviews que mencionam COVID
                    if (review_mentions_covid($review)) {
                        return false;
                    }
                    return true;
                });
                
                // Adicionar aos reviews coletados (evitar duplicatas)
                foreach ($filtered as $review) {
                    // Evitar duplicatas por author
                    $exists = false;
                    foreach ($allReviews as $existing) {
                        if (isset($existing['author']) && isset($review['author']) && 
                            mb_strtolower(trim($existing['author'])) === mb_strtolower(trim($review['author']))) {
                            $exists = true;
                            break;
                        }
                    }
                    if (!$exists) {
                        $allReviews[] = $review;
                    }
                }
            }
        }
    }
    
    // Se coletou reviews de múltiplas fontes, ordenar todos juntos
    if (!empty($allReviews)) {
        // Reordenar todos os reviews coletados com a mesma lógica
        usort($allReviews, function($a, $b) {
            // Prioridade 1: Reviews com foto REAL de perfil (foto dá credibilidade) - MAIS IMPORTANTE
            // Não priorizar placeholders (apenas inicial do nome)
            $aHasRealPhoto = review_has_real_photo($a);
            $bHasRealPhoto = review_has_real_photo($b);
            if ($aHasRealPhoto != $bHasRealPhoto) {
                return $bHasRealPhoto ? 1 : -1; // Com foto REAL primeiro
            }
            
            // Prioridade 2: Tamanho médio de texto (100-500 chars é ideal - credibilidade)
            $aLength = isset($a['text_length']) ? $a['text_length'] : mb_strlen(get_review_text($a));
            $bLength = isset($b['text_length']) ? $b['text_length'] : mb_strlen(get_review_text($b));
            
            // Função para calcular score de tamanho (tamanho médio = melhor)
            $getSizeScore = function($length) {
                if ($length >= 100 && $length <= 500) {
                    return 3; // Tamanho médio ideal - melhor score
                } elseif ($length >= 50 && $length < 100) {
                    return 2; // Curto mas aceitável
                } elseif ($length > 500 && $length <= 1000) {
                    return 2; // Longo mas ainda bom
                } elseif ($length > 1000) {
                    return 1; // Muito longo
                } else {
                    return 0; // Muito curto
                }
            };
            
            $aSizeScore = $getSizeScore($aLength);
            $bSizeScore = $getSizeScore($bLength);
            if ($aSizeScore != $bSizeScore) {
                return $bSizeScore - $aSizeScore; // Melhor score primeiro
            }
            
            // Se mesmo score de tamanho, preferir tamanho médio dentro do range
            if ($aSizeScore == 3 && $bSizeScore == 3) {
                // Dentro do range ideal, preferir mais próximo de 300 chars (meio do range)
                $aDistance = abs($aLength - 300);
                $bDistance = abs($bLength - 300);
                if ($aDistance != $bDistance) {
                    return $aDistance - $bDistance; // Mais próximo de 300 primeiro
                }
            }
            
            // Prioridade 3: Rating (5 estrelas antes de 4)
            $aRating = isset($a['rating']) ? (int)$a['rating'] : 0;
            $bRating = isset($b['rating']) ? (int)$b['rating'] : 0;
            if ($aRating != $bRating) {
                return $bRating - $aRating;
            }
            
            // Prioridade 4: Reviews dos últimos 2 anos primeiro (mas não limitar)
            $aRecent = review_is_recent($a);
            $bRecent = review_is_recent($b);
            if ($aRecent != $bRecent) {
                return $bRecent ? 1 : -1; // Recentes primeiro
            }
            
            // Prioridade 5: Mais recentes primeiro
            $aTime = isset($a['time']) ? $a['time'] : 0;
            $bTime = isset($b['time']) ? $b['time'] : 0;
            return $bTime - $aTime;
        });
        
        // Retornar os melhores
        $result = array_slice($allReviews, 0, $maxResults);
        if (!empty($result)) {
            return array_values($result);
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
                // Normalizar campos de foto (scraper usa profile_picture)
                if (isset($review['profile_picture']) && !isset($review['profile_photo'])) {
                    $review['profile_photo'] = $review['profile_picture'];
                }
                
                if (!isset($review['quality_score'])) {
                    $reviewText = get_review_text($review);
                    $textLength = mb_strlen($reviewText);
                    $words = preg_split('/\s+/u', trim($reviewText));
                    $wordCount = count(array_filter($words, function($word) {
                        return mb_strlen(trim($word)) > 0;
                    }));
                    $rating = $review['rating'] ?? 4;
                    $hasPhoto = !empty($review['profile_photo']) || !empty($review['profile_picture']);
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
            
            // Filtrar reviews indesejados antes de retornar
            $cached = array_filter($cached, function($review) {
                // Remover reviews excluídos (conflito de interesse)
                if (review_should_be_excluded($review)) {
                    return false;
                }
                // Remover reviews que mencionam COVID
                if (review_mentions_covid($review)) {
                    return false;
                }
                return true;
            });
            
            // Reordenar se necessário (nova lógica: foto > tamanho médio > rating > últimos 2 anos > mais recentes)
            if ($needsReorder || true) { // Sempre reordenar para aplicar nova lógica
                usort($cached, function($a, $b) {
                    // Prioridade 1: Reviews com foto REAL de perfil (foto dá credibilidade) - MAIS IMPORTANTE
                    // Não priorizar placeholders (apenas inicial do nome)
                    $aHasRealPhoto = review_has_real_photo($a);
                    $bHasRealPhoto = review_has_real_photo($b);
                    if ($aHasRealPhoto != $bHasRealPhoto) {
                        return $bHasRealPhoto ? 1 : -1; // Com foto REAL primeiro
                    }
                    
                    // Prioridade 2: Tamanho médio de texto (100-500 chars é ideal - credibilidade)
                    $aLength = isset($a['text_length']) ? $a['text_length'] : mb_strlen(get_review_text($a));
                    $bLength = isset($b['text_length']) ? $b['text_length'] : mb_strlen(get_review_text($b));
                    
                    // Função para calcular score de tamanho (tamanho médio = melhor)
                    $getSizeScore = function($length) {
                        if ($length >= 100 && $length <= 500) {
                            return 3; // Tamanho médio ideal - melhor score
                        } elseif ($length >= 50 && $length < 100) {
                            return 2; // Curto mas aceitável
                        } elseif ($length > 500 && $length <= 1000) {
                            return 2; // Longo mas ainda bom
                        } elseif ($length > 1000) {
                            return 1; // Muito longo
                        } else {
                            return 0; // Muito curto
                        }
                    };
                    
                    $aSizeScore = $getSizeScore($aLength);
                    $bSizeScore = $getSizeScore($bLength);
                    if ($aSizeScore != $bSizeScore) {
                        return $bSizeScore - $aSizeScore; // Melhor score primeiro
                    }
                    
                    // Se mesmo score de tamanho, preferir tamanho médio dentro do range
                    if ($aSizeScore == 3 && $bSizeScore == 3) {
                        // Dentro do range ideal, preferir mais próximo de 300 chars (meio do range)
                        $aDistance = abs($aLength - 300);
                        $bDistance = abs($bLength - 300);
                        if ($aDistance != $bDistance) {
                            return $aDistance - $bDistance; // Mais próximo de 300 primeiro
                        }
                    }
                    
                    // Prioridade 3: Rating (5 estrelas antes de 4)
                    $aRating = isset($a['rating']) ? (int)$a['rating'] : 0;
                    $bRating = isset($b['rating']) ? (int)$b['rating'] : 0;
                    if ($aRating != $bRating) {
                        return $bRating - $aRating;
                    }
                    
                    // Prioridade 4: Reviews dos últimos 2 anos primeiro (mas não limitar)
                    $aRecent = review_is_recent($a);
                    $bRecent = review_is_recent($b);
                    if ($aRecent != $bRecent) {
                        return $bRecent ? 1 : -1; // Recentes primeiro
                    }
                    
                    // Prioridade 5: Mais recentes primeiro
                    $aTime = isset($a['time']) ? $a['time'] : 0;
                    $bTime = isset($b['time']) ? $b['time'] : 0;
                    return $bTime - $aTime;
                });
                // Salvar cache atualizado
                file_put_contents($cacheFile, json_encode($cached));
            }
            
            return array_values($cached);
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
    
    // Filtrar reviews indesejados
    $reviews = array_filter($reviews, function($review) {
        // Remover reviews excluídos (conflito de interesse)
        if (review_should_be_excluded($review)) {
            return false;
        }
        // Remover reviews que mencionam COVID
        if (review_mentions_covid($review)) {
            return false;
        }
        return true;
    });
    
    // Ordenar por qualidade com prioridades:
    // 1. Reviews com foto de perfil (foto dá credibilidade) - MAIS IMPORTANTE
    // 2. Tamanho médio de texto (100-500 chars - nem muito curto, nem muito longo)
    // 3. Rating (5 estrelas antes de 4)
    // 4. Reviews dos últimos 2 anos primeiro (mas não limitar apenas a isso)
    // 5. Mais recentes primeiro
    usort($reviews, function($a, $b) {
                    // Prioridade 1: Reviews com foto REAL de perfil (foto dá credibilidade) - MAIS IMPORTANTE
                    // Não priorizar placeholders (apenas inicial do nome)
                    $aHasRealPhoto = review_has_real_photo($a);
                    $bHasRealPhoto = review_has_real_photo($b);
                    if ($aHasRealPhoto != $bHasRealPhoto) {
                        return $bHasRealPhoto ? 1 : -1; // Com foto REAL primeiro
                    }
        
        // Prioridade 2: Tamanho médio de texto (100-500 chars é ideal - credibilidade)
        $aLength = isset($a['text_length']) ? $a['text_length'] : mb_strlen(get_review_text($a));
        $bLength = isset($b['text_length']) ? $b['text_length'] : mb_strlen(get_review_text($b));
        
        // Função para calcular score de tamanho (tamanho médio = melhor)
        $getSizeScore = function($length) {
            if ($length >= 100 && $length <= 500) {
                return 3; // Tamanho médio ideal - melhor score
            } elseif ($length >= 50 && $length < 100) {
                return 2; // Curto mas aceitável
            } elseif ($length > 500 && $length <= 1000) {
                return 2; // Longo mas ainda bom
            } elseif ($length > 1000) {
                return 1; // Muito longo
            } else {
                return 0; // Muito curto
            }
        };
        
        $aSizeScore = $getSizeScore($aLength);
        $bSizeScore = $getSizeScore($bLength);
        if ($aSizeScore != $bSizeScore) {
            return $bSizeScore - $aSizeScore; // Melhor score primeiro
        }
        
        // Se mesmo score de tamanho, preferir tamanho médio dentro do range
        if ($aSizeScore == 3 && $bSizeScore == 3) {
            // Dentro do range ideal, preferir mais próximo de 300 chars (meio do range)
            $aDistance = abs($aLength - 300);
            $bDistance = abs($bLength - 300);
            if ($aDistance != $bDistance) {
                return $aDistance - $bDistance; // Mais próximo de 300 primeiro
            }
        }
        
        // Prioridade 3: Rating (5 estrelas antes de 4)
        $aRating = isset($a['rating']) ? (int)$a['rating'] : 0;
        $bRating = isset($b['rating']) ? (int)$b['rating'] : 0;
        if ($aRating != $bRating) {
            return $bRating - $aRating; // 5 estrelas primeiro
        }
        
        // Prioridade 4: Reviews dos últimos 2 anos primeiro (mas não limitar)
        $aRecent = review_is_recent($a);
        $bRecent = review_is_recent($b);
        if ($aRecent != $bRecent) {
            return $bRecent ? 1 : -1; // Recentes primeiro
        }
        
        // Prioridade 5: Mais recentes primeiro
        $aTime = isset($a['time']) ? $a['time'] : 0;
        $bTime = isset($b['time']) ? $b['time'] : 0;
        return $bTime - $aTime; // Mais recentes primeiro
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
        'reviewBody' => get_review_text($review)
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
        $reviewText = get_review_text($review);
        $html .= '<p>' . nl2br(htmlspecialchars($reviewText)) . '</p>';
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

