/**
 * Animações on Scroll
 * Sistema leve de animações baseado em Intersection Observer
 * 
 * Desenvolvido por: Victor Penter
 * Versão: 2.3.6
 */

(function() {
    'use strict';

    // Check if Intersection Observer is supported
    if (!('IntersectionObserver' in window)) {
        // Fallback: show all elements immediately
        document.querySelectorAll('.fade-in-up, .fade-in-left, .fade-in-right, .scale-in, .fade-in, .stagger-item').forEach(function(el) {
            el.classList.add('visible');
        });
        return;
    }

    // Create observer with options
    const observerOptions = {
        root: null,
        rootMargin: '0px 0px -50px 0px', // Trigger when element is 50px from viewport
        threshold: 0.1 // Trigger when 10% of element is visible
    };

    // Observer callback
    const observerCallback = function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                // Unobserve after animation to improve performance
                observer.unobserve(entry.target);
            }
        });
    };

    // Create observer
    const observer = new IntersectionObserver(observerCallback, observerOptions);

    // Observe all animated elements
    const animatedElements = document.querySelectorAll(
        '.fade-in-up, .fade-in-left, .fade-in-right, .scale-in, .fade-in, .stagger-item'
    );

    animatedElements.forEach(function(el) {
        observer.observe(el);
    });

    // Stagger animation for lists (delay based on index)
    const staggerContainers = document.querySelectorAll('.stagger-container');
    staggerContainers.forEach(function(container) {
        const items = container.querySelectorAll('.stagger-item');
        items.forEach(function(item, index) {
            item.style.transitionDelay = (index * 0.1) + 's';
        });
    });

})();

