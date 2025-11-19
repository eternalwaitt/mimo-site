<?php
/**
 * Helper Functions para Ícones Lucide
 * Substitui Font Awesome por Lucide Icons para melhor performance
 * 
 * Desenvolvido por: Victor Penter
 * Versão: <?php echo APP_VERSION; ?>
 */

/**
 * Mapeamento Font Awesome → Lucide
 * Baseado em LUCIDE-MIGRATION-MAP.md
 */
function get_lucide_icon_name($fa_class) {
    // Remover prefixos fa-, fas, far, fab, fal
    $icon_name = preg_replace('/^(fa-|fas |far |fab |fal )/', '', $fa_class);
    
    // Mapeamento direto
    $mapping = [
        'arrow-left' => 'arrow-left',
        'briefcase' => 'briefcase',
        'building' => 'building',
        'calendar' => 'calendar',
        'chevron-up' => 'chevron-up',
        'circle' => 'circle',
        'clock' => 'clock',
        'cut' => 'scissors',
        'envelope' => 'mail',
        'eye' => 'eye',
        'facebook-f' => 'facebook',
        'google' => 'chrome', // ou 'globe' dependendo do contexto
        'graduation-cap' => 'graduation-cap',
        'hand-sparkles' => 'sparkles',
        'heart' => 'heart',
        'info-circle' => 'info',
        'instagram' => 'instagram',
        'map-marker-alt' => 'map-pin',
        'money-bill-wave' => 'dollar-sign', // ou 'coins'
        'palette' => 'palette',
        'paper-plane' => 'send',
        'phone' => 'phone',
        'route' => 'route',
        'share-alt' => 'share-2',
        'smile' => 'smile',
        'spa' => 'sparkles', // ou 'flower'
        'star' => 'star',
        'tasks' => 'list-checks', // ou 'check-square'
        'user' => 'user',
        'whatsapp' => 'message-circle',
    ];
    
    return isset($mapping[$icon_name]) ? $mapping[$icon_name] : $icon_name;
}

/**
 * Gera HTML para um ícone Lucide
 * 
 * @param string $name Nome do ícone Lucide (ex: 'instagram', 'facebook')
 * @param string $class Classes CSS adicionais
 * @param int $size Tamanho do ícone em pixels (padrão: 24)
 * @param string $stroke_width Largura do traço (padrão: '2')
 * @param string $color Cor do ícone (padrão: 'currentColor')
 * @return string HTML do ícone
 */
function lucide_icon($name, $class = '', $size = 24, $stroke_width = '2', $color = 'currentColor') {
    $class_attr = $class ? ' class="' . htmlspecialchars($class) . '"' : '';
    
    return sprintf(
        '<i data-lucide="%s"%s style="width: %dpx; height: %dpx; stroke-width: %s; color: %s;"></i>',
        htmlspecialchars($name),
        $class_attr,
        $size,
        $size,
        htmlspecialchars($stroke_width),
        htmlspecialchars($color)
    );
}

/**
 * Gera HTML para um ícone Lucide a partir de uma classe Font Awesome
 * Útil para migração gradual
 * 
 * @param string $fa_class Classe Font Awesome (ex: 'fa-instagram', 'fab fa-facebook-f')
 * @param string $class Classes CSS adicionais
 * @param int $size Tamanho do ícone em pixels (padrão: 24)
 * @return string HTML do ícone
 */
function lucide_icon_from_fa($fa_class, $class = '', $size = 24) {
    $lucide_name = get_lucide_icon_name($fa_class);
    return lucide_icon($lucide_name, $class, $size);
}

/**
 * Inicializa Lucide Icons no JavaScript
 * Deve ser chamado após o DOM estar pronto
 * 
 * @return string Script tag para inicializar Lucide
 */
function lucide_init_script() {
    return '<script>
        // Inicializar Lucide Icons após DOM ready
        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", function() {
                if (typeof lucide !== "undefined") {
                    lucide.createIcons();
                }
            });
        } else {
            if (typeof lucide !== "undefined") {
                lucide.createIcons();
            }
        }
    </script>';
}

