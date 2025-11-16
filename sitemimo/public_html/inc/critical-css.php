<?php
/**
 * CSS Crítico Expandido
 * CSS acima da dobra para carregamento inicial mais rápido da página
 * Este CSS é inline no <head> para melhorar o First Contentful Paint (FCP)
 * 
 * Desenvolvido por: Victor Penter
 * Versão: 2.3.9
 */
?>
<style>
<?php
// Inline CSS Variables para evitar render blocking
readfile(__DIR__ . '/../css/modules/_variables.css');
?>
/* ============================================
   CRITICAL CSS - Above the Fold Content
   Otimizado para First Contentful Paint (FCP)
   ============================================ */

/* Reset básico para evitar FOUC */
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    line-height: 1.6;
    background-color: #f0e8e6; /* More visible pink/beige blend (80% white, 20% brand pink #d9c2bd) */
    color: #31265b; /* Brand dark for text */
}

/* Force light mode when not in dark mode */
:not([data-theme="dark"]) body,
body:not([data-theme="dark"]) {
    background-color: #f0e8e6 !important; /* More visible pink/beige blend (80% white, 20% brand pink #d9c2bd) */
    color: #31265b !important; /* Brand dark for text */
}

/* CRITICAL: Dark mode body - lighter dark tone for better contrast and readability */
[data-theme="dark"] body,
body[data-theme="dark"] {
    background-color: #2a2a2a !important; /* Lighter dark tone - better contrast, not too dark */
    color: #f5f5f5 !important; /* Light text for readability */
}

/* CRITICAL: Dark mode testimonials - darker tones based on brand dark */
/* FIX: Use html[data-theme="dark"] for higher specificity */
html[data-theme="dark"] .testimonial-content,
[data-theme="dark"] .testimonial-content {
    background: #252525 !important; /* Darker neutral tone for testimonials, not pure black */
    border: none !important; /* No border */
    border-radius: 20px !important;
    padding: 25px 30px !important; /* Same as light mode */
    color: #f5f5f5 !important; /* Light text for readability */
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1) !important; /* Lighter shadow */
}

/* CRITICAL: Dark mode testimonial text - must override light mode */
html[data-theme="dark"] .testimonial-text,
[data-theme="dark"] .testimonial-text {
    color: #f5f5f5 !important; /* MATCH PRODUCTION: rgb(245, 245, 245) */
}

html[data-theme="dark"] .testimonial-author strong,
[data-theme="dark"] .testimonial-author strong {
    color: #f5f5f5 !important; /* MATCH PRODUCTION: rgb(245, 245, 245) */
}

/* CRITICAL: Light mode testimonials - MATCH PRODUCTION */
/* FIX: Use html:not() not body:not() - data-theme is on html, not body */
[data-theme="light"] .testimonial-content,
html:not([data-theme="dark"]) .testimonial-content {
    background-color: #f5f5f5 !important; /* MATCH PRODUCTION: rgb(245, 245, 245) */
    border: none !important;
    padding: 25px 30px !important; /* MATCH PRODUCTION */
    border-radius: 20px !important;
    box-shadow: none !important; /* MATCH PRODUCTION: no shadow */
    color: #212529 !important; /* MATCH PRODUCTION: rgb(33, 37, 41) - body text color */
}

/* Google Fonts size-adjust para prevenir layout shift */
/* Otimizado: font-display: optional elimina FOIT completamente */
@font-face {
    font-family: 'Nunito Fallback';
    src: local('Arial');
    /* CRITICAL: size-adjust prevents layout shift during font swap */
    size-adjust: 100%;
    ascent-override: 90%;
    descent-override: 22%;
    line-gap-override: 0%;
    font-display: optional; /* Usa fallback se fonte não carregar em 100ms */
}

/* Aplicar fallback com size-adjust ao body */
body {
    font-family: 'Nunito', 'Nunito Fallback', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* Font fallback para Akrobat - prevenir layout shift */
@font-face {
    font-family: 'Akrobat Fallback';
    src: local('Arial');
    /* CRITICAL: size-adjust prevents layout shift during font swap */
    size-adjust: 95%; /* Akrobat is slightly narrower than Arial */
    ascent-override: 92%;
    descent-override: 25%;
    line-gap-override: 0%;
    font-display: optional;
}

.Akrobat {
    font-family: 'Akrobat Regular', 'Akrobat Fallback', 'Nunito', 'Nunito Fallback', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    /* Garantir que o tamanho seja consistente mesmo antes da fonte carregar */
    font-size: inherit;
    line-height: inherit;
    /* FIX: Prevent layout shift during font loading */
    font-display: optional;
}

/* Font fallback para EB Garamond - prevenir layout shift */
@font-face {
    font-family: 'EB Garamond Fallback';
    src: local('Times New Roman');
    /* CRITICAL: size-adjust prevents layout shift during font swap */
    size-adjust: 98%; /* EB Garamond is similar to Times */
    ascent-override: 88%;
    descent-override: 24%;
    line-gap-override: 0%;
    font-display: optional;
}

/* Navbar - Primeiro elemento visível */
/* CRITICAL: Minimal styles to prevent FOUC - full styles in product.css */
.navbar {
    padding: 20px 25px;
    padding-top: 20px;
    padding-bottom: 2px;
    margin: 0;
    z-index: 9999;
    position: fixed; /* Fixed navbar - persistent at top */
    top: 0;
    left: 0;
    right: 0;
    width: 100%;
    contain: layout;
    min-height: 70px;
    /* CRITICAL: Fully transparent at top (invisible) - only dark when compressed */
    background-color: transparent !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
    box-shadow: none !important;
    transition: background-color 0.3s ease, backdrop-filter 0.3s ease, box-shadow 0.3s ease;
}

/* Navbar compressed (scrolled) - Lighter dark gray background for both modes */
.navbar.compressed {
    background-color: rgba(60, 60, 60, 0.9) !important; /* Lighter dark gray (was 42, 42, 42) */
    backdrop-filter: blur(10px) !important;
    -webkit-backdrop-filter: blur(10px) !important;
    box-shadow: rgba(0, 0, 0, 0.1) 2px 2px 2px 0px !important; /* Subtle shadow */
}

/* Nav links - Remove underline from all nav links */
.navbar-nav .nav-link {
    text-decoration: none !important; /* Remove underline */
}

.navbar-nav .nav-link:hover {
    text-decoration: none !important; /* Remove underline on hover */
}

/* Nav links - When navbar is transparent (top of page), use brand pink */
.navbar:not(.compressed) .navbar-nav .nav-link {
    color: var(--color-brand-pink, #d9c2bd) !important; /* Brand pink when transparent */
    letter-spacing: 1.5px;
    font-weight: 400; /* Normal weight for sophisticated look */
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1); /* Subtle depth, GPU-accelerated */
    text-decoration: none !important; /* Remove underline */
}

/* Light mode: ensure transparent header uses brand pink */
html:not([data-theme="dark"]) .navbar:not(.compressed) .navbar-nav .nav-link,
[data-theme="light"] .navbar:not(.compressed) .navbar-nav .nav-link {
    color: var(--color-brand-pink, #d9c2bd) !important; /* Brand pink in light mode */
}

/* Nav links - When navbar is compressed (scrolled), use brand pink in light mode */
.navbar.compressed .navbar-nav .nav-link {
    color: #fafafa !important; /* Off-white text on dark gray header (default) */
    letter-spacing: 1.5px;
    font-weight: 400; /* Normal weight for sophisticated look */
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1); /* Subtle depth, GPU-accelerated */
    text-decoration: none !important; /* Remove underline */
}

/* Light mode: compressed header uses brand pink text */
html:not([data-theme="dark"]) .navbar.compressed .navbar-nav .nav-link,
[data-theme="light"] .navbar.compressed .navbar-nav .nav-link {
    color: var(--color-brand-pink, #d9c2bd) !important; /* Brand pink text on compressed header in light mode */
}
    transition: background-color .3s ease, padding .3s ease;
    /* GPU acceleration */
    will-change: background-color, transform;
    transform: translateZ(0);
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
}

/* Compensate for fixed navbar - add padding to main-content, not body */
/* Removed body padding-top to prevent white rectangle above navbar */

/* Light mode: darker navbar background for better text readability - CRITICAL CSS */
[data-theme="light"] .navbar,
:not([data-theme]) .navbar,
body:not([data-theme="dark"]) .navbar {
    background-color: rgba(42, 42, 42, 0.85) !important; /* Dark semi-transparent background in light mode */
    backdrop-filter: blur(10px); /* Add blur for better contrast */
    -webkit-backdrop-filter: blur(10px);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Modern subtle shadow */
}

/* Dark mode navbar - MATCH PRODUCTION */
[data-theme="dark"] .navbar {
    background-color: rgba(42, 42, 42, 0.85) !important; /* MATCH PRODUCTION: same as light mode */
    backdrop-filter: blur(10px); /* MATCH PRODUCTION */
    -webkit-backdrop-filter: blur(10px);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Modern subtle shadow */
}

[data-theme="dark"] .navbar-nav .nav-link {
    color: #f5f5f5 !important; /* White nav links in dark mode */
}

/* CRITICAL: Hero title and subtitle colors - MATCH PRODUCTION */
.hero-title,
.display-4.hero-title {
    color: #1a1a1a; /* MATCH PRODUCTION: rgb(26, 26, 26) - hero title light mode */
}

[data-theme="dark"] .hero-title,
[data-theme="dark"] .display-4 {
    color: #f5f5f5 !important; /* MATCH PRODUCTION: rgb(245, 245, 245) */
}

.textDarkGrey,
.hero-subtitle {
    color: #31265b; /* Brand dark for hero subtitle */
}

[data-theme="dark"] .textDarkGrey,
[data-theme="dark"] .hero-subtitle {
    color: #f5f5f5 !important; /* MATCH PRODUCTION: rgb(245, 245, 245) */
}

.navbar-brand {
    display: inline-block;
    padding-top: 0.3125rem;
    padding-bottom: 0.3125rem;
    margin-right: 1rem;
    font-size: 1.25rem;
    line-height: inherit;
    white-space: nowrap;
}

.logonav {
    height: 40px;
    transition: .3s height;
    max-width: 100%;
}

/* Hero Section - Above the fold (CRITICAL CSS - LCP element) */
/* FIX: Hero section agora usa <img> tag para melhor LCP e fetchpriority */
.hero-section {
    position: relative;
    width: 100%;
    height: 250px; /* Mobile default - explicit height to prevent conflicts */
    contain: layout;
    background-color: #3d3d3d;
    overflow: hidden;
    display: block;
}

/* Desktop: taller hero section */
@media (min-width: 751px) {
    .hero-section {
        height: 400px; /* Desktop height */
    }
}

/* Light mode: transparent background to allow vivid image */
[data-theme="light"] .hero-section,
:not([data-theme]) .hero-section {
    background-color: transparent; /* FIX: Transparent background to allow vivid image to show through */
}

.hero-section picture {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
}

.hero-section picture img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(61, 61, 61, 0.5); /* Increased opacity for better text readability */
    z-index: 1;
    pointer-events: none;
}

/* Light mode: remove overlay completely for vivid image - use text-shadow on text instead */
[data-theme="light"] .hero-section .hero-overlay,
:not([data-theme]) .hero-section .hero-overlay,
body:not([data-theme="dark"]) .hero-section .hero-overlay {
    background-color: transparent !important; /* FIX: Remove overlay completely for vivid image */
    display: none !important; /* FIX: Hide overlay completely in light mode */
}

/* Mobile hero section - height already set above (250px) */

/* Hero Content - CRITICAL for FCP */
.hero-content {
    text-align: center;
    color: #fff;
    z-index: 1;
    position: relative;
    /* CRITICAL: Reserve space to prevent layout shift */
    min-height: 100px;
    contain: layout;
}

.hero-content h1 {
    font-size: 3rem;
    font-weight: 300;
    margin-bottom: 1rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    /* CRITICAL: Reserve space for title */
    min-height: 1.2em;
    contain: layout;
}

.hero-content p {
    font-size: 1.25rem;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
    /* CRITICAL: Reserve space for text */
    min-height: 1.2em;
    contain: layout;
}

/* Navigation Menu - Minimal styles only */
.navbar-nav {
    display: flex;
    flex-direction: row;
    list-style: none;
    margin: 0;
    padding: 0;
}

/* Don't set nav-link colors here - let product.css handle it */
/* This prevents conflicts when product.css loads */

/* Container básico */
.container {
    width: 100%;
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
    contain: layout style paint; /* Prevent CLS */
}

/* Loading state - evitar layout shift */
img {
    max-width: 100%;
    height: auto;
    display: block;
}

/* Aspect ratio containers for images to prevent layout shift */
picture {
    display: block;
}

picture img {
    width: 100%;
    height: auto;
}

/* About section - Above the fold on mobile, critical for FCP */
#about {
    contain: layout style paint; /* Prevent CLS */
    min-height: 500px; /* Reserve space */
    position: relative;
    margin-top: 0; /* Remove any default margin that might cause overlap */
    padding-top: 3rem; /* Add padding for spacing from hero */
}

#about .container .row.mx-auto {
    contain: layout; /* Reduced from layout style paint - Bootstrap row needs to flow naturally */
    min-height: 600px; /* Reserve space */
    position: relative;
    /* Removed display: flex and flex-wrap - Bootstrap's .row already uses flexbox */
}

/* Mobile optimizations - CRITICAL for LCP */
@media only screen and (max-width: 750px) {
    .bg-header {
        /* Usar AVIF/WebP com fallback PNG para melhor performance (LCP element mobile) */
        /* Preload já configurado no <head> - garantir que CSS crítico tenha estilos */
        background-image: url(/img/header_dezembro_mobile.avif);
        background-image: -webkit-image-set(
            url(/img/header_dezembro_mobile.avif) type("image/avif"),
            url(/img/header_dezembro_mobile.webp) type("image/webp"),
            url(/img/header_dezembro_mobile.png) type("image/png")
        );
        background-image: image-set(
            url(/img/header_dezembro_mobile.avif) type("image/avif"),
            url(/img/header_dezembro_mobile.webp) type("image/webp"),
            url(/img/header_dezembro_mobile.png) type("image/png")
        );
        background-position: center;
        /* CRITICAL: Remover height quando aspect-ratio está presente para evitar conflito CSS */
        /* height: 40vh; - REMOVIDO: conflito com aspect-ratio causa layout shift */
        min-height: 250px;
        max-height: 350px;
        background-size: cover;
        background-repeat: no-repeat;
        /* Garantir espaço reservado para prevenir layout shift */
        aspect-ratio: 16/9;
        background-color: #3d3d3d;
        /* Forçar aceleração de hardware para renderização mais rápida */
        will-change: background-image;
        transform: translateZ(0);
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
    }
    
    /* Mobile: Optimize about section for faster FCP */
    #about {
        min-height: 400px; /* Smaller on mobile */
    }
    
    #about .container .row.mx-auto {
        min-height: 400px; /* Smaller on mobile */
    }
    
    /* CRITICAL: Mobile hero section - above the fold, critical for FCP */
    .hero-section {
        contain: layout; /* Reduced from layout style paint - hero needs to flow */
        height: 250px !important; /* Use explicit height instead of min-height */
        position: relative;
        overflow: hidden;
        background-color: #3d3d3d;
    }
    
    .hero-section picture,
    .hero-section img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
    }
    
    /* CRITICAL: Mobile hero content - ensure text is visible immediately */
    .hero-content {
        position: relative;
        z-index: 2;
        color: #fff;
        text-align: center;
        padding: 20px;
    }
    
    .hero-content h1 {
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 1rem;
        line-height: 1.3;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    }
    
    .hero-content p {
        font-size: 1rem;
        margin-bottom: 1rem;
        line-height: 1.6;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
    }
    
    /* CRITICAL: Mobile main-content padding for fixed navbar */
    #main-content {
        padding-top: 70px; /* Compensate for fixed navbar */
    }
    
    /* Mobile categories grid - prevent layout shift */
    .mobile-categories-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        contain: layout;
    }
    
    .mobile-category-item {
        contain: layout; /* Removido 'style' - pode estar causando reflow */
        min-height: 200px; /* Reserve space */
    }
    
    .mobile-category-item .img-cat {
        aspect-ratio: 1 / 1;
        width: 100%;
        max-width: 150px;
        height: auto;
    }
    
    .mobile-vagas-button {
        contain: layout;
        min-height: 160px; /* Reserve space */
    }
    
    .navbar {
        padding: 15px 20px;
        /* Don't set background-color here - let product.css handle it */
    }
    
    .hero-content h1 {
        font-size: 2rem;
    }
    
    .hero-content p {
        font-size: 1rem;
    }
    
    .navbar-nav {
        flex-direction: column;
    }
    
    /* CRITICAL: Animation rules moved to @media (max-width: 768px) block below */
    /* These rules should only apply on mobile, not desktop */
    
    /* Disable hover effects on mobile */
    .img-hover:hover {
        transform: none !important;
        filter: none !important;
    }
    
    /* Disable all transitions globally on mobile */
    * {
        transition-duration: 0.01ms !important;
        animation-duration: 0.01ms !important;
    }
}

/* Prevenir FOUC (Flash of Unstyled Content) */
.hidden {
    visibility: hidden;
}

/* Smooth transitions */
* {
    transition: background-color 0.3s ease, color 0.3s ease;
}

/* ============================================
   CONTRASTE CRÍTICO - Above the fold
   ============================================ */

/* Garantir contraste mínimo acima da dobra */
body {
    color: #1a1a1a;
    background-color: #ffffff;
}

h1, h2, h3, h4, h5, h6 {
    color: #1a1a1a;
}

p, span, li {
    color: #2a2a2a;
}

a:not(.btn):not(.action-btn) {
    color: #31265b; /* Brand dark */
}

/* Section containers - prevent layout shift */
section {
    padding: 60px 0;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 300;
    margin-bottom: 2rem;
    text-align: center;
    color: #31265b; /* Brand dark */
}

/* Cards básicos - above the fold - CRITICAL for FCP */
.card {
    border: none;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    /* CRITICAL: Reserve space to prevent layout shift */
    contain: layout;
    min-height: 200px;
}

.card:hover {
    transform: translateY(-5px) translateZ(0); /* GPU acceleration */
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}

/* Category images - prevent layout shift */
.img-cat {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 50%;
}

/* Content overlay effects - initial state */
.content {
    position: relative;
    overflow: hidden;
}

.content-image {
    width: 100%;
    height: auto;
    display: block;
    transition: transform 0.3s ease;
}

/* Prevent layout shift for main hero image */
#florzinha picture,
#florzinha img {
    width: 100%;
    height: auto;
    aspect-ratio: 1 / 1;
    max-width: 100%;
    display: block;
}

/* Reserve space for logo to prevent layout shift */
.logonav {
    width: auto;
    height: 40px;
    max-width: 120px;
    aspect-ratio: 1961 / 360; /* Logo aspect ratio */
    object-fit: contain;
    /* Garantir espaço reservado mesmo antes da imagem carregar */
    background-color: transparent;
    display: block;
}

/* Main content - prevent layout shift (CRÍTICO - 93% do CLS) */
/* CRITICAL: Desktop CLS fix - main-content causing 0.501 shift */
#main-content {
    min-height: 100vh; /* Reserve space to prevent layout shift */
    position: relative;
    /* Garantir que conteúdo não cause shift */
    contain: layout style paint; /* More aggressive containment to prevent CLS */
    /* FIX: Prevenir que conteúdo dinâmico cause shift */
    overflow-x: hidden;
    /* FIX: Garantir que seções principais tenham altura mínima desde o início */
    /* Prevent font reflow causing layout shift */
    font-size: inherit;
    line-height: inherit;
}

/* FIX: Garantir altura mínima para seções principais dentro do main */
#main-content > .hero-section {
    min-height: 250px; /* Mobile */
}

@media (min-width: 751px) {
    #main-content > .hero-section {
        min-height: 400px; /* Desktop */
    }
}

/* FIX: Garantir altura mínima para seção about */
#about {
    min-height: 500px; /* Reservar espaço desde o início */
    contain: layout;
}

/* FIX: Garantir altura mínima para seção services */
#services {
    min-height: 800px; /* Reservar espaço desde o início */
    contain: layout;
}

/* FIX: Garantir altura mínima para testimonials section */
.testimonials-section {
    min-height: 600px; /* Reservar espaço desde o início */
    contain: layout;
}

/* About section container - prevent layout shift */
#about .container .row.mx-auto {
    min-height: 600px; /* Reserve space for content */
    /* Prevenir layout shift durante carregamento */
    contain: layout; /* Reduced from layout style paint - Bootstrap row needs to flow naturally */
    position: relative; /* Force layout stability */
    /* Removed display: flex and flex-wrap - Bootstrap's .row already uses flexbox */
    /* Removed overflow: hidden - may hide content */
}

/* CRITICAL: col-md-7 causing 93% of CLS (0.375) - prevent layout shift */
/* FIX: Make text column wider horizontally to reduce vertical length */
#about .col-md-7 {
    contain: layout; /* Reduced from layout style paint - column needs to flow */
    min-height: 400px; /* Reserve space for text content */
    /* Prevent font reflow */
    font-size: inherit;
    line-height: inherit;
    /* Force layout stability */
    position: relative;
    overflow: hidden;
    /* Removed display: flex and flex-direction - let Bootstrap handle layout */
    /* Removed flex and max-width overrides - let Bootstrap handle responsive widths */
}

/* FIX: Make image column narrower to give more space to text */
#about .col-md-5 {
    /* Removed flex and max-width overrides - let Bootstrap handle responsive widths */
}

/* CRITICAL: Desktop CLS fix - h1 in about section causing 0.501 shift */
/* FIX: Better proportions - reduce title size, increase text size */
#about .col-md-7 h1 {
    /* Reserve space for title */
    min-height: 2.5em;
    height: auto; /* Allow natural height but reserve minimum */
    contain: layout; /* Reduced from layout style paint - text needs to flow naturally */
    /* Prevent font reflow - reserve space even before font loads */
    font-size: 2.8rem !important; /* Reduced from 3.5rem for better proportion */
    line-height: 1.3 !important; /* Better line height */
    margin-bottom: 1.5rem !important; /* Add spacing below title */
    /* Force layout stability */
    position: relative;
    overflow: hidden;
    /* Prevent text reflow */
    word-wrap: break-word;
    overflow-wrap: break-word;
}

#about .col-md-7 p {
    /* Reserve space to prevent shift when fonts load */
    min-height: 1.2em;
    contain: layout;
    /* Prevent text reflow */
    word-wrap: break-word;
    overflow-wrap: break-word;
}

/* FIX: Better proportions - increase intro text size significantly */
#about .col-md-7 .lead {
    min-height: 1.5em;
    contain: layout;
    font-size: 1.35rem !important; /* Increased for better readability and proportion */
    line-height: 1.8 !important; /* Comfortable line height for reading */
    margin-bottom: 1.5rem !important; /* Add spacing between paragraphs */
}

/* FIX: Better proportions for tagline */
#about .col-md-7 .hero-tagline {
    font-size: 1.4rem !important; /* Slightly larger than intro text */
    margin-top: 1rem !important; /* Add spacing above */
}

/* ============================================
   CRITICAL: Mobile CLS Fixes
   Mobile-specific containment and min-heights
   ============================================ */

@media (max-width: 750px) {
    /* CRITICAL: Mobile CLS fix - main-content causing layout shifts */
    #main-content {
        contain: layout style paint; /* More aggressive containment on mobile */
        min-height: 100vh; /* Reserve space */
        position: relative;
        overflow-x: hidden;
        /* Prevent font reflow */
        font-size: inherit;
        line-height: inherit;
    }

    /* Mobile: Hero section - prevent layout shift */
    .hero-section {
        contain: layout style paint;
        min-height: 250px !important;
        max-height: 400px !important;
        position: relative;
        overflow: hidden;
    }

    /* Mobile: About section - prevent layout shift */
    #about {
        contain: layout; /* Reduced from layout style paint - section needs to flow */
        min-height: 600px; /* Reserve space for mobile layout */
        position: relative;
        overflow: hidden;
    }

    #about .container .row.mx-auto {
        contain: layout; /* Reduced from layout style paint - Bootstrap row needs to flow */
        min-height: 600px;
        position: relative;
        /* Removed display: flex and flex-wrap - Bootstrap's .row already uses flexbox */
        /* Removed overflow: hidden - may hide content */
    }

    /* Mobile: About section columns - prevent font reflow */
    #about .col-md-7,
    #about .col-md-5 {
        contain: layout; /* Reduced from layout style paint - columns need to flow */
        min-height: 300px; /* Reserve space for mobile stacked layout */
        position: relative;
        overflow: hidden;
        /* Prevent font reflow */
        font-size: inherit;
        line-height: inherit;
    }

    /* Mobile: About section h1 - prevent font reflow */
    #about .col-md-7 h1 {
        min-height: 2em; /* Reserve space for title on mobile */
        contain: layout; /* Reduced from layout style paint - text needs to flow */
        font-size: 2rem !important; /* Smaller on mobile */
        line-height: 1.3 !important;
        margin-bottom: 1rem !important;
        position: relative;
        overflow: hidden;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    /* Mobile: About section text - prevent font reflow */
    #about .col-md-7 p,
    #about .col-md-7 .lead {
        min-height: 1.2em;
        contain: layout;
        font-size: 1rem !important; /* Smaller on mobile */
        line-height: 1.6 !important;
        margin-bottom: 1rem !important;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    /* Mobile: Services section - prevent layout shift */
    #services {
        contain: layout style paint;
        min-height: 800px; /* Reserve space for mobile layout */
        position: relative;
        overflow: hidden;
    }

/* Mobile: Testimonials section - prevent layout shift */
.testimonials-section {
    contain: layout style paint;
    min-height: 600px; /* Reserve space for mobile layout */
    position: relative;
    overflow: hidden;
}

/* CRITICAL: CLS Fix - Testimonials carousel container */
.testimonials-container {
    contain: layout style paint;
    min-height: 500px; /* Reserve space for carousel */
    position: relative;
}

.testimonials-carousel {
    contain: layout style paint;
    min-height: 500px; /* Reserve space for carousel */
    position: relative;
}

.testimonials-carousel .carousel-inner,
.testimonials-inner {
    contain: layout style paint;
    min-height: 500px; /* Reserve space for carousel items */
    position: relative;
}

/* CRITICAL: CLS Fix - Testimonial cards */
.testimonial-card {
    contain: layout style paint;
    min-height: 400px; /* Reserve space for testimonial content */
    position: relative;
    /* FIX: Reserve space for content to prevent shift when loading */
    display: flex;
    flex-direction: column;
    justify-content: center;
}

/* CRITICAL: CLS Fix - Testimonial content */
.testimonial-content {
    contain: layout style paint;
    min-height: 350px; /* Reserve space for testimonial text and avatar */
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

/* CRITICAL: CLS Fix - Testimonial text */
.testimonial-text {
    contain: layout;
    min-height: 100px; /* Reserve space for text */
    margin: 1rem 0;
}

/* CRITICAL: CLS Fix - Testimonial author */
.testimonial-author {
    contain: layout;
    min-height: 1.5em; /* Reserve space for author name */
    margin: 0.5rem 0;
}

/* CRITICAL: CLS Fix - Testimonial rating */
.testimonial-rating {
    contain: layout;
    min-height: 1.2em; /* Reserve space for stars */
    margin: 0.5rem 0;
}

/* CRITICAL: CLS Fix - Testimonial avatar */
.testimonial-avatar {
    contain: layout style paint;
    width: 80px !important;
    height: 80px !important;
    min-width: 80px;
    min-height: 80px;
    aspect-ratio: 1 / 1;
    /* FIX: Reserve space even when image is loading */
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(0, 0, 0, 0.05); /* Skeleton background */
    border-radius: 50%;
    overflow: hidden;
}

/* CRITICAL: CLS Fix - Testimonial avatar image */
.testimonial-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    /* FIX: Prevent layout shift when image loads */
    aspect-ratio: 1 / 1;
}

/* CRITICAL: CLS Fix - Testimonial avatar placeholder */
.testimonial-avatar-placeholder {
    width: 80px !important;
    height: 80px !important;
    min-width: 80px;
    min-height: 80px;
    aspect-ratio: 1 / 1;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(0, 0, 0, 0.1);
    border-radius: 50%;
    font-size: 2rem;
    font-weight: bold;
    color: rgba(0, 0, 0, 0.5);
}

    /* Mobile: Category grid - prevent layout shift */
    .mobile-categories-container {
        contain: layout style paint;
        min-height: 400px; /* Reserve space for grid */
        position: relative;
        overflow: hidden;
    }

    .mobile-categories-grid {
        contain: layout style paint;
        min-height: 400px; /* Reserve space for grid */
        position: relative;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .mobile-category-item {
        contain: layout style paint;
        min-height: 200px; /* Reserve space for item */
        position: relative;
        overflow: hidden;
    }

    .mobile-category-item .img-cat {
        aspect-ratio: 1 / 1;
        object-fit: cover;
        width: 100%;
        max-width: 150px;
        height: auto;
    }

    /* Mobile: Florzinha (logo image) - prevent layout shift */
    #florzinha {
        contain: layout style paint;
        min-height: 300px; /* Reserve space for image */
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    #florzinha img,
    #florzinha picture,
    #florzinha picture img {
        max-width: 90% !important;
        max-height: 300px !important; /* Smaller on mobile */
        object-fit: contain;
    }
}

/* Services section - prevent layout shift */
#services {
    min-height: 400px;
    contain: layout;
}

/* Testimonials section - prevent layout shift */
.testimonials-carousel {
    min-height: 500px;
    contain: layout style;
    /* Reserve space for carousel content */
    padding-bottom: 80px; /* Space for indicators and button */
    /* Force layout stability */
    position: relative;
    overflow: hidden;
}

/* Testimonial cards - prevent layout shift */
.testimonial-card {
    min-height: 300px;
    contain: layout style;
    /* Reserve space for content */
    padding: 20px;
    margin-bottom: 20px;
    /* Force layout stability */
    position: relative;
}

/* Testimonial avatar - prevent layout shift */
.testimonial-avatar {
    width: 80px;
    height: 80px;
    min-width: 80px;
    min-height: 80px;
    aspect-ratio: 1 / 1;
    border-radius: 50%;
    overflow: hidden;
    margin: 0 auto;
    /* Reserve space even if image fails to load */
    background-color: #f0f0f0;
    /* Force layout stability */
    contain: layout style;
    flex-shrink: 0;
}

/* Carousel indicators - prevent layout shift */
.carousel-indicators {
    contain: layout;
    min-height: 30px;
    position: absolute;
    bottom: 20px;
}

/* Carousel controls - prevent layout shift */
.carousel-control-prev,
.carousel-control-next {
    contain: layout;
    width: 50px;
    height: 50px;
    min-width: 50px;
    min-height: 50px;
}

/* Category images - prevent layout shift */
.img-cat {
    aspect-ratio: 1 / 1;
    width: 100%;
    max-width: 150px;
    height: auto;
    object-fit: cover;
    display: block;
}

/* Content images - prevent layout shift */
.content-image {
    aspect-ratio: 5 / 4;
    width: 100%;
    max-width: 600px;
    height: auto;
    object-fit: cover;
    display: block;
}

/* Sessoes container - prevent layout shift */
.sessoes.container {
    contain: layout;
    min-height: 300px; /* Reserve space */
}

.sessoes.container .content {
    contain: layout;
    min-height: 300px; /* Reserve space */
}

.sessoes.container .content-image {
    aspect-ratio: 5 / 4;
    width: 100%;
    height: auto;
    object-fit: cover;
}

/* Cards e containers - prevent layout shift */
.card,
.vaga-card,
.info-card {
    contain: layout style;
    min-height: 200px; /* Reserve space */
}

/* Images in cards - prevent layout shift */
.card img,
.vaga-card img,
.info-card img {
    aspect-ratio: 16 / 9;
    width: 100%;
    height: auto;
    object-fit: cover;
}

/* Footer - prevent layout shift */
.site-footer {
    background: linear-gradient(135deg, rgb(37, 37, 39) 0%, rgb(26, 26, 28) 100%) !important; /* Match production: dark gray gradient */
    color: #fff;
    padding: 60px 0 25px;
    margin-top: 60px;
    min-height: 300px; /* Reserve space */
    contain: layout;
}

/* Button styles - above the fold */
.btn {
    padding: 12px 30px;
    border-radius: 25px;
    font-weight: 400;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    display: inline-block;
    text-align: center;
    text-decoration: none;
}

.btn-primary {
    background-color: #d9c2bd; /* Brand pink */
    color: #fff;
}

.btn-primary:hover {
    background-color: #c4a8a1; /* Darker brand pink */
    transform: translateY(-2px);
}

/* btnSeeMore - Critical above the fold */
.btnSeeMore {
    background-color: transparent !important; /* CRITICAL: White button with border like production */
    color: #fff !important;
    border: 2px solid #fff !important;
    padding: 10px 20px;
    border-radius: 0;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btnSeeMore:hover {
    background-color: rgba(255, 255, 255, 0.2) !important; /* Slight white background on hover */
    color: #fff !important; /* Keep white text on hover */
    border: 2px solid #fff !important; /* Keep white border */
}

/* Service cards - CRITICAL: Override Bootstrap .container for .sessoes.container */
.sessoes.container,
#services .sessoes.container,
#services .d-none.d-sm-block .sessoes.container {
    /* Override Bootstrap .container styles - CRITICAL: Use !important to override Bootstrap */
    padding: 0 !important; /* Remove Bootstrap's padding */
    margin: 0 !important; /* Remove Bootstrap's margin auto */
    max-width: 960px !important; /* Match production: Bootstrap's .container max-width */
    float: left !important; /* CRITICAL: Override Bootstrap's block display */
    width: 50% !important; /* CRITICAL: Override Bootstrap's container width */
    display: flex !important; /* CRITICAL: Override Bootstrap's block display */
    align-items: center;
    justify-content: center;
    contain: layout style paint;
    min-height: 300px;
    aspect-ratio: 5 / 4;
}

/* Clear floats for service cards container */
#services .d-none.d-sm-block {
    overflow: hidden !important; /* Clear floats - matches production behavior */
    width: 100% !important;
    display: block !important;
}

/* Override inline min-width on service card images */
.sessoes.container .content-image {
    min-width: 0 !important; /* Override inline min-width: 500px/600px that breaks layout */
    max-width: 100%; /* Ensure images don't overflow container */
}

@media screen and (min-width: 900px) {
    .sessoes.container,
    #services .sessoes.container,
    #services .d-none.d-sm-block .sessoes.container {
        width: 33.33333% !important; /* CRITICAL: Override Bootstrap - 3 per row on desktop */
    }
}

@media screen and (max-width: 640px) {
    .sessoes.container {
        display: block !important; /* CRITICAL: Override Bootstrap */
        width: 100% !important; /* CRITICAL: Override Bootstrap */
    }
}

/* Content details overlay - above the fold */
.content-details {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    opacity: 0 !important; /* CRITICAL: Hide text by default, only show on hover */
    color: #fff;
    z-index: 2;
}

.content-details h2 {
    font-size: 2.5rem;
    font-weight: 300;
    margin-bottom: 1rem;
    color: #1a1a1a !important; /* CRITICAL: Dark gray for better readability like production */
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}

/* Dark mode: white text for titles */
[data-theme="dark"] .content-details h2 {
    color: #ffffff !important; /* White text in dark mode */
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8); /* Stronger shadow in dark mode */
}

/* Show content-details and overlay on hover */
.sessoes.container:hover .content-details {
    opacity: 1 !important;
}

.sessoes.container:hover .content-overlay {
    opacity: 1 !important;
}

.content-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(49, 38, 91, 0.6); /* Brand dark overlay - only darken a bit on hover */
    z-index: 1;
    transition: opacity 0.4s ease;
    opacity: 0; /* Hidden by default */
}

/* Mobile category items - above the fold */
.mobile-category-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    text-decoration: none;
    color: inherit;
}

.mobile-category-label {
    margin-top: 10px;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--color-pink, #d9c2bd); /* Brand pink via CSS variable */
}

.mobile-vagas-button {
    width: 100%;
    text-decoration: none;
    display: block;
}

.mobile-vagas-card {
    background: linear-gradient(135deg, var(--color-pink, #d9c2bd) 0%, var(--color-pink-dark, #c4a8a1) 100%);
    color: #fff;
    padding: 30px;
    border-radius: 12px;
    text-align: center;
    min-height: 160px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.mobile-vagas-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}

.mobile-vagas-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 10px 0 5px;
}

.mobile-vagas-subtitle {
    font-size: 0.9rem;
    opacity: 0.9;
}

/* Text utilities */
.textPink {
    color: var(--color-pink, #d9c2bd); /* Brand pink via CSS variable */
}

/* Row/Column layout - prevent shift */
.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
}

.col-md-5, .col-md-7 {
    position: relative;
    width: 100%;
    padding-right: 15px;
    padding-left: 15px;
}

@media (min-width: 768px) {
    .col-md-5 {
        flex: 0 0 41.666667%;
        max-width: 41.666667%;
    }
    
    .col-md-7 {
        flex: 0 0 58.333333%;
        max-width: 58.333333%;
    }
}

/* Prevent FOUC for dynamic content */
.testimonial-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    overflow: hidden;
    margin: 0 auto;
}

.testimonial-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Breadcrumbs - Hidden visually but kept for SEO and accessibility */
/* Sobrescrever Bootstrap e qualquer outro CSS */
.breadcrumb-nav,
nav.breadcrumb-nav,
nav[aria-label="breadcrumb"],
nav[aria-label="breadcrumb"].breadcrumb-nav {
    /* Completamente escondido visualmente mas mantido no DOM para SEO */
    display: none !important;
    position: absolute !important;
    width: 1px !important;
    height: 1px !important;
    padding: 0 !important;
    margin: -1px !important;
    overflow: hidden !important;
    clip: rect(0, 0, 0, 0) !important;
    clip-path: inset(50%) !important;
    white-space: nowrap !important;
    border: 0 !important;
    opacity: 0 !important;
    visibility: hidden !important;
    pointer-events: none !important;
}

/* Esconder todos os elementos filhos do breadcrumb */
.breadcrumb-nav .breadcrumb,
.breadcrumb-nav ol,
.breadcrumb-nav .breadcrumb-item,
.breadcrumb-nav li {
    display: none !important;
    opacity: 0 !important;
    visibility: hidden !important;
    pointer-events: none !important;
}

.breadcrumb {
    background-color: transparent;
    padding: 0;
    margin: 0;
    font-size: 14px;
    color: #fff;
    display: flex;
    flex-wrap: wrap;
    list-style: none;
    align-items: center;
}

.breadcrumb-item {
    display: flex;
    align-items: center;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: #fff;
    padding: 0 8px;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    display: inline-block;
    font-weight: normal;
}

.breadcrumb-item a {
    color: #fff;
    text-decoration: none;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
}

.breadcrumb-item.active {
    color: #fff;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
}

@media (max-width: 768px) {
    /* CRITICAL: Disable ALL animations on mobile for better performance */
    /* Force all animated elements to be visible immediately */
    .fade-in-up,
    .fade-in-left,
    .fade-in-right,
    .scale-in,
    .fade-in,
    .stagger-item {
        opacity: 1 !important;
        transform: none !important;
        transition: none !important;
        animation: none !important;
        will-change: auto !important;
        visibility: visible !important;
    }
    
    /* Make sure visible class doesn't trigger animations */
    .fade-in-up.visible,
    .fade-in-left.visible,
    .fade-in-right.visible,
    .scale-in.visible,
    .fade-in.visible,
    .stagger-item.visible {
        opacity: 1 !important;
        transform: none !important;
        transition: none !important;
        animation: none !important;
        will-change: auto !important;
    }
    
    .breadcrumb-nav {
        /* Keep hidden on mobile too */
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
        margin: -1px !important;
        padding: 0 !important;
        overflow: hidden !important;
        clip: rect(0, 0, 0, 0) !important;
        clip-path: inset(50%) !important;
        opacity: 0 !important;
        visibility: hidden !important;
    }
}
</style>

