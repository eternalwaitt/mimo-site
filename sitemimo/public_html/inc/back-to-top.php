<?php
/**
 * Botão "Voltar ao Topo"
 * Componente reutilizável para scroll suave ao topo da página
 * 
 * Desenvolvido por: Victor Penter
 * Versão: 1.0.0
 */
?>
<!-- Botão Voltar ao Topo -->
<button id="backToTop" class="back-to-top" aria-label="Voltar ao topo" title="Voltar ao topo">
    <i class="fas fa-chevron-up"></i>
</button>

<style>
.back-to-top {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 50px;
    height: 50px;
    background-color: #ccb7bc;
    color: #fff;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
    opacity: 0;
    transform: translateY(20px);
}

.back-to-top.show {
    display: flex;
    opacity: 1;
    transform: translateY(0);
}

.back-to-top:hover {
    background-color: #b8a3a8;
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
}

.back-to-top:active {
    transform: translateY(-1px);
}

.back-to-top i {
    font-size: 20px;
}

@media (max-width: 768px) {
    .back-to-top {
        bottom: 20px;
        right: 20px;
        width: 45px;
        height: 45px;
    }
    
    .back-to-top i {
        font-size: 18px;
    }
}
</style>

<script>
(function() {
    'use strict';
    
    var backToTopBtn = document.getElementById('backToTop');
    
    if (!backToTopBtn) return;
    
    // Mostrar/esconder botão baseado no scroll
    function toggleBackToTop() {
        if (window.pageYOffset > 300) {
            backToTopBtn.classList.add('show');
        } else {
            backToTopBtn.classList.remove('show');
        }
    }
    
    // Scroll suave ao topo
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
    
    // Event listeners
    window.addEventListener('scroll', toggleBackToTop);
    backToTopBtn.addEventListener('click', scrollToTop);
    
    // Inicializar estado
    toggleBackToTop();
})();
</script>

