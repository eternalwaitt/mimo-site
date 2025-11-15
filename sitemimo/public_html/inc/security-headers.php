<?php
/**
 * Cabeçalhos de Segurança
 * Adiciona cabeçalhos de segurança em todas as páginas para melhor proteção
 * 
 * Desenvolvido por: Victor Penter
 */

// Prevent clickjacking
header('X-Frame-Options: SAMEORIGIN');

// Prevent MIME type sniffing
header('X-Content-Type-Options: nosniff');

// XSS Protection (legacy, but still useful)
header('X-XSS-Protection: 1; mode=block');

// Referrer Policy
header('Referrer-Policy: strict-origin-when-cross-origin');

// Content Security Policy (CSP) - adjust as needed for your site
// Allow inline styles and scripts from same origin, Google Fonts, Font Awesome, Bootstrap CDN, etc.
$csp = "default-src 'self'; " .
       "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://code.jquery.com https://stackpath.bootstrapcdn.com https://cdnjs.cloudflare.com https://code.tidio.co https://www.googletagmanager.com https://www.google-analytics.com https://cluster-piwik.locaweb.com.br; " .
       "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://use.fontawesome.com https://stackpath.bootstrapcdn.com; " .
       "font-src 'self' data: https://fonts.gstatic.com https://use.fontawesome.com; " .
       "img-src 'self' data: https:; " .
       "connect-src 'self' https://api.whatsapp.com https://www.google-analytics.com; " .
       "frame-src 'self' https://www.googletagmanager.com https://www.google.com; " .
       "object-src 'none'; " .
       "base-uri 'self'; " .
       "form-action 'self';";

header("Content-Security-Policy: $csp");

// Permissions Policy (formerly Feature Policy)
header("Permissions-Policy: geolocation=(), microphone=(), camera=()");

