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
    color: #333;
}

/* Google Fonts size-adjust para prevenir layout shift */
/* Otimizado: font-display: optional elimina FOIT completamente */
@font-face {
    font-family: 'Nunito Fallback';
    src: local('Arial');
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
.Akrobat {
    font-family: 'Akrobat Regular', 'Nunito', 'Nunito Fallback', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    /* Garantir que o tamanho seja consistente mesmo antes da fonte carregar */
    font-size: inherit;
    line-height: inherit;
}

/* Navbar - Primeiro elemento visível */
.navbar {
    padding: 20px 25px;
    background-color: #3d3d3d;
    margin: 0;
    transition: all .3s;
    z-index: 9999;
    position: relative;
}

.navbar.compressed {
    padding: 10px 25px;
    background-color: rgba(61, 61, 61, 0.95);
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

/* Hero Section - Above the fold */
.bg-header {
    /* Desktop: usar AVIF/WebP com fallback JPG */
    /* CRITICAL: LCP element - preload já configurado no <head> com fetchpriority="high" */
    background-image: url(/img/bgheader.avif);
    background-image: -webkit-image-set(
        url(/img/bgheader.avif) type("image/avif"),
        url(/img/bgheader.webp) type("image/webp"),
        url(/img/bgheader.jpg) type("image/jpeg")
    );
    background-image: image-set(
        url(/img/bgheader.avif) type("image/avif"),
        url(/img/bgheader.webp) type("image/webp"),
        url(/img/bgheader.jpg) type("image/jpeg")
    );
    background-size: cover;
    background-repeat: no-repeat;
    height: 50vh;
    min-height: 350px;
    max-height: 500px;
    background-position: center;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    /* Prevent layout shift - aspect ratio + min-height */
    aspect-ratio: 16/9;
    width: 100%;
    /* Garantir espaço reservado mesmo antes da imagem carregar */
    background-color: #3d3d3d;
    /* Forçar aceleração de hardware para renderização mais rápida */
    will-change: background-image;
    transform: translateZ(0);
    /* Otimizar renderização */
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
    /* CRITICAL: Não aplicar lazy loading - LCP element deve carregar imediatamente */
    content-visibility: auto;
}

/* Hero Content */
.hero-content {
    text-align: center;
    color: #fff;
    z-index: 1;
    position: relative;
}

.hero-content h1 {
    font-size: 3rem;
    font-weight: 300;
    margin-bottom: 1rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.hero-content p {
    font-size: 1.25rem;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
}

/* Navigation Menu */
.navbar-nav {
    display: flex;
    flex-direction: row;
    list-style: none;
    margin: 0;
    padding: 0;
}

.navbar-nav .nav-link {
    color: #fff;
    padding: 0.5rem 1rem;
    text-decoration: none;
    transition: color 0.3s;
}

.navbar-nav.changecolormenu .nav-link {
    color: #fff;
}

.navbar-nav .nav-link:hover {
    color: #ccb7bc;
}

/* Container básico */
.container {
    width: 100%;
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
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
        height: 40vh;
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
    
    /* Mobile categories grid - prevent layout shift */
    .mobile-categories-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        contain: layout;
    }
    
    .mobile-category-item {
        contain: layout style;
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
    .fade-in.visible {
        opacity: 1 !important;
        transform: none !important;
        transition: none !important;
    }
    
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
    color: #3a505a;
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
    color: #3a505a;
}

/* Cards básicos - above the fold */
.card {
    border: none;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
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

/* Main content - prevent layout shift */
#main-content {
    min-height: 100vh; /* Reserve space to prevent layout shift */
    position: relative;
    /* Garantir que conteúdo não cause shift */
    contain: layout style;
}

/* About section container - prevent layout shift */
#about .container.row.mx-auto {
    min-height: 600px; /* Reserve space for content */
    /* Prevenir layout shift durante carregamento */
    contain: layout;
}

/* CRITICAL: col-md-7 causing 93% of CLS (0.375) - prevent layout shift */
#about .col-md-7 {
    contain: layout style;
    min-height: 400px; /* Reserve space for text content */
    /* Prevent font reflow */
    font-size: inherit;
    line-height: inherit;
    /* Force layout stability */
    position: relative;
    overflow: hidden;
}

/* Prevent layout shift in about section text */
#about .col-md-7 h1,
#about .col-md-7 p {
    /* Reserve space to prevent shift when fonts load */
    min-height: 1.2em;
    contain: layout;
    /* Prevent text reflow */
    word-wrap: break-word;
    overflow-wrap: break-word;
}

/* Additional CLS prevention for about section */
#about .col-md-7 .lead {
    min-height: 1.5em;
    contain: layout;
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
    background-color: #3d3d3d;
    color: #fff;
    padding: 40px 0 20px;
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
    background-color: #ccb7bc;
    color: #fff;
}

.btn-primary:hover {
    background-color: #b8a3a8;
    transform: translateY(-2px);
}

/* btnSeeMore - Critical above the fold */
.btnSeeMore {
    background-color: rgba(58, 80, 90, 0.8);
    color: #fff;
    padding: 12px 30px;
    border-radius: 25px;
    font-weight: 400;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
    border: 2px solid #fff;
    cursor: pointer;
}

.btnSeeMore:hover {
    background-color: rgba(58, 80, 90, 1);
    transform: translateY(-2px);
}

/* Content details overlay - above the fold */
.content-details {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: #fff;
    z-index: 2;
}

.content-details h2 {
    font-size: 2.5rem;
    font-weight: 300;
    margin-bottom: 1rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}

.content-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4);
    z-index: 1;
    transition: background 0.3s ease;
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
    color: var(--color-pink, #ccb7bc); /* Rosa da marca via variável CSS */
}

.mobile-vagas-button {
    width: 100%;
    text-decoration: none;
    display: block;
}

.mobile-vagas-card {
    background: linear-gradient(135deg, var(--color-pink, #ccb7bc) 0%, var(--color-pink-dark, #b895a0) 100%);
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
    color: var(--color-pink, #ccb7bc); /* Rosa da marca via variável CSS */
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

