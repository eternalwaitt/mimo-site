<?php
/**
 * CSS Crítico Expandido
 * CSS acima da dobra para carregamento inicial mais rápido da página
 * Este CSS é inline no <head> para melhorar o First Contentful Paint (FCP)
 * 
 * Desenvolvido por: Victor Penter
 * Versão: 2.3.0
 */
?>
<style>
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
    /* Prevent layout shift */
    aspect-ratio: 16/9;
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
</style>

