<?php
/**
 * Manual Reviews Helper
 * Sistema de reviews manuais com Schema.org (sem necessidade de API)
 * 
 * Desenvolvido por: Victor Penter
 * Versão: 1.0.0
 */

/**
 * Retorna reviews manuais (você pode editar esta lista)
 * Filtra apenas 4 e 5 estrelas
 * 
 * @param int $minRating Rating mínimo (default: 4)
 * @param int $maxResults Número máximo de resultados
 * @return array Array de reviews
 */
function get_manual_reviews($minRating = 4, $maxResults = 10) {
    // Edite esta lista com reviews reais do Google My Business
    // Você pode copiar reviews do Google Maps e adicionar aqui
    $allReviews = [
        [
            'author' => 'Maria Silva',
            'rating' => 5,
            'text' => 'Atendimento excelente! Profissionais muito atenciosas e competentes. Ambiente acolhedor e limpo.',
            'date' => '2025-01-15',
            'source' => 'Google'
        ],
        [
            'author' => 'Ana Paula',
            'rating' => 5,
            'text' => 'Melhor centro de beleza da região! Faço vários procedimentos e sempre saio satisfeita.',
            'date' => '2025-01-10',
            'source' => 'Google'
        ],
        [
            'author' => 'Juliana Costa',
            'rating' => 5,
            'text' => 'Profissionais incríveis, ambiente super agradável. Recomendo muito!',
            'date' => '2025-01-05',
            'source' => 'Google'
        ],
        [
            'author' => 'Fernanda Oliveira',
            'rating' => 4,
            'text' => 'Ótimo atendimento e qualidade dos serviços. Voltarei com certeza!',
            'date' => '2024-12-28',
            'source' => 'Google'
        ],
        [
            'author' => 'Patricia Santos',
            'rating' => 5,
            'text' => 'Super recomendo! Equipe profissional e ambiente muito acolhedor.',
            'date' => '2024-12-20',
            'source' => 'Google'
        ],
        // Adicione mais reviews aqui conforme necessário
        // Você pode copiar reviews reais do Google Maps
    ];
    
    // Filtrar por rating mínimo
    $filtered = array_filter($allReviews, function($review) use ($minRating) {
        return $review['rating'] >= $minRating;
    });
    
    // Limitar quantidade
    return array_slice($filtered, 0, $maxResults);
}

/**
 * Gera Schema.org AggregateRating
 * 
 * @param float $rating Rating médio
 * @param int $reviewCount Número total de reviews
 * @return string JSON-LD schema
 */
function generate_manual_aggregate_rating_schema($rating, $reviewCount) {
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
function generate_manual_review_schema($review, $businessName) {
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
    
    if (isset($review['date'])) {
        $schema['datePublished'] = date('c', strtotime($review['date']));
    }
    
    return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
}

/**
 * Gera HTML para exibir reviews manuais
 * 
 * @param array $reviews Array de reviews
 * @return string HTML
 */
function render_manual_reviews($reviews) {
    if (empty($reviews)) {
        return '';
    }
    
    $html = '<div class="google-reviews">';
    
    foreach ($reviews as $review) {
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
        if (isset($review['source'])) {
            $html .= ' <small class="text-muted">(' . htmlspecialchars($review['source']) . ')</small>';
        }
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
        if (isset($review['date'])) {
            $html .= '<div class="review-date">';
            $html .= '<small class="text-muted">' . date('d/m/Y', strtotime($review['date'])) . '</small>';
            $html .= '</div>';
        }
        
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    return $html;
}

