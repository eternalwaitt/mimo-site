/**
 * Bootstrap Carousel Swipe Plugin
 * 
 * Permite swipe em dispositivos touch para navegar o carousel
 * Plugin bcSwipe - versão desminificada para manutenção
 * 
 * Desenvolvido por: Victor Penter
 * Versão: 1.0.0
 * 
 * USO:
 * - Aplicado automaticamente a todos os elementos com classe .carousel
 * - THRESHOLD: 50px - distância mínima de swipe para ativar
 * 
 * EXEMPLO:
 * $('.carousel').bcSwipe({ threshold: 50 });
 */

(function($) {
    'use strict';
    
    /**
     * Plugin jQuery para adicionar suporte a swipe em carousels Bootstrap
     * 
     * @param {Object} options - Opções de configuração
     * @param {number} options.threshold - Distância mínima em pixels para ativar swipe (padrão: 50)
     * @returns {jQuery} Retorna o objeto jQuery para chaining
     */
    $.fn.bcSwipe = function(options) {
        var settings = {
            threshold: 50
        };
        
        // Mesclar opções fornecidas com padrões
        if (options) {
            $.extend(settings, options);
        }
        
        return this.each(function() {
            var $carousel = $(this);
            var startX = null;
            var isTracking = false;
            
            /**
             * Handler para início do toque
             * @param {TouchEvent} event - Evento de toque
             */
            function handleTouchStart(event) {
                // Só processar se houver exatamente um toque
                if (event.touches.length === 1) {
                    startX = event.touches[0].pageX;
                    isTracking = true;
                    this.addEventListener('touchmove', handleTouchMove, false);
                }
            }
            
            /**
             * Handler para movimento do toque
             * @param {TouchEvent} event - Evento de toque
             */
            function handleTouchMove(event) {
                if (!isTracking || !startX) {
                    return;
                }
                
                var currentX = event.touches[0].pageX;
                var deltaX = startX - currentX;
                var absDeltaX = Math.abs(deltaX);
                
                // Se o movimento foi suficiente, ativar carousel
                if (absDeltaX >= settings.threshold) {
                    cleanup();
                    // Se deltaX > 0, swipe para esquerda = próximo slide
                    // Se deltaX < 0, swipe para direita = slide anterior
                    $carousel.carousel(deltaX > 0 ? 'next' : 'prev');
                }
            }
            
            /**
             * Limpa event listeners e reseta estado
             */
            function cleanup() {
                this.removeEventListener('touchmove', handleTouchMove);
                startX = null;
                isTracking = false;
            }
            
            // Só adicionar listener se o dispositivo suporta touch
            if ('ontouchstart' in document.documentElement) {
                this.addEventListener('touchstart', handleTouchStart, false);
            }
        });
    };
})(jQuery);

