/**
 * Animações on Scroll
 * Sistema leve de animações baseado em Intersection Observer
 * 
 * Desenvolvido por: Victor Penter
 * Versão: 2.3.7
 */

(function() {
    'use strict';

    // CRITICAL: Disable animations on mobile for better performance
    // Check if mobile (max-width: 768px) - use matchMedia for accurate detection
    const isMobile = window.matchMedia('(max-width: 768px)').matches || 
                     /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    
    if (isMobile) {
        // On mobile, immediately show all elements (no animations)
        document.querySelectorAll('.fade-in-up, .fade-in-left, .fade-in-right, .scale-in, .fade-in, .stagger-item').forEach(function(el) {
            el.classList.add('visible');
            // Force opacity and transform to prevent any animation
            el.style.opacity = '1';
            el.style.transform = 'none';
            el.style.transition = 'none';
        });
        return; // Exit early on mobile
    }
    
    // DESKTOP: Initialize animations when DOM is ready
    function initAnimationObserver() {
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
                    // Only add visible class if element is actually entering viewport
                    // Don't animate elements that are already fully visible on page load
                    if (entry.intersectionRatio > 0.1) {
                        entry.target.classList.add('visible');
                        // Unobserve after animation to improve performance
                        observer.unobserve(entry.target);
                    }
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
            // Check if element is already in viewport on page load
            const rect = el.getBoundingClientRect();
            const isInViewport = rect.top < window.innerHeight && rect.bottom > 0;
            
            if (isInViewport) {
                // Element is already visible on page load (above the fold)
                // Keep it visible immediately - no animation needed
                // Don't remove visible class, just ensure it's there
                el.classList.add('visible');
                // Reset any inline styles
                el.style.opacity = '';
                el.style.transform = '';
                el.style.transition = '';
                // Don't observe - it's already visible
            } else {
                // Element is below the fold - should animate when scrolled into view
                // Remove visible class if it was added too early
                el.classList.remove('visible');
                // Reset any inline styles
                el.style.opacity = '';
                el.style.transform = '';
                el.style.transition = '';
                // Observe for scroll-triggered animation
                observer.observe(el);
            }
        });

        // Stagger animation for lists (delay based on index)
        const staggerContainers = document.querySelectorAll('.stagger-container');
        staggerContainers.forEach(function(container) {
            const items = container.querySelectorAll('.stagger-item');
            items.forEach(function(item, index) {
                item.style.transitionDelay = (index * 0.1) + 's';
            });
        });
    }
    
    // Wait for DOM to be ready before initializing animations
    // This ensures elements are in their initial state (opacity: 0) before we observe them
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            // Small delay to ensure CSS has loaded and applied initial states
            setTimeout(initAnimationObserver, 100);
        });
    } else {
        // DOM already loaded, but wait a bit for CSS to apply
        setTimeout(initAnimationObserver, 100);
    }

})();
