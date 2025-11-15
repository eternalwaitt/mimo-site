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

/* Font fallback para Akrobat - prevenir layout shift */
.Akrobat {
    font-family: 'Akrobat Regular', 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
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
    background-image: url(/img/bgheader.jpg);
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

/* Mobile optimizations */
@media only screen and (max-width: 750px) {
    .bg-header {
        background-image: url(/img/header_dezembro_mobile.png);
        background-position: center;
        height: 40vh;
        min-height: 250px;
        max-height: 350px;
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
}

/* Prevenir FOUC (Flash of Unstyled Content) */
.hidden {
    visibility: hidden;
}

/* Smooth transitions */
* {
    transition: background-color 0.3s ease, color 0.3s ease;
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
}

/* Footer - prevent layout shift */
.site-footer {
    background-color: #3d3d3d;
    color: #fff;
    padding: 40px 0 20px;
    margin-top: 60px;
}

/* Button styles - above the fold */
.btn {
    padding: 12px 30px;
    border-radius: 25px;
    font-weight: 400;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background-color: #ccb7bc;
    color: #fff;
}

.btn-primary:hover {
    background-color: #b8a3a8;
    transform: translateY(-2px);
}

/* Text utilities */
.textPink {
    color: #ccb7bc;
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

