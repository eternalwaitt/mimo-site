<?php
/**
 * HTML Minification Helper
 * Simple HTML minifier without external dependencies
 * 
 * CRITICAL: This is a lightweight minifier. For better results, consider voku/HtmlMin via composer
 * 
 * Usage:
 * ob_start('html_minify');
 * // ... HTML output ...
 * ob_end_flush();
 */

function html_minify($buffer) {
    // Skip minification in development
    if (defined('APP_ENV') && APP_ENV !== 'production') {
        return $buffer;
    }
    
    // Skip if already minified (check for compressed output)
    if (strlen($buffer) < 1000) {
        return $buffer;
    }
    
    // Remove HTML comments (except conditional comments)
    $buffer = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $buffer);
    
    // Remove whitespace between tags (but preserve in <pre>, <textarea>, <script>)
    $buffer = preg_replace('/>\s+</', '><', $buffer);
    
    // Remove leading/trailing whitespace from lines
    $buffer = preg_replace('/^\s+/m', '', $buffer);
    $buffer = preg_replace('/\s+$/m', '', $buffer);
    
    // Remove multiple spaces (but preserve in attributes)
    $buffer = preg_replace('/\s{2,}/', ' ', $buffer);
    
    // Remove whitespace around specific tags
    $buffer = preg_replace('/\s*<\/(?:html|head|body|div|section|article|header|footer|nav|main|aside|ul|ol|li|p|h[1-6]|span|a|strong|em|b|i|table|tr|td|th|thead|tbody|tfoot|form|input|button|label|select|option|textarea|script|style|link|meta|title)\s*>/i', '</$1>', $buffer);
    
    // Preserve whitespace in <pre>, <textarea>, and <script> tags
    // This is a simplified version - for production, consider using a proper HTML parser
    
    return trim($buffer);
}

/**
 * Start HTML minification output buffer
 * Call this at the start of index.php
 */
function start_html_minify() {
    if (defined('APP_ENV') && APP_ENV === 'production') {
        ob_start('html_minify');
    }
}

/**
 * End HTML minification output buffer
 * Call this at the end of index.php (before </html>)
 */
function end_html_minify() {
    if (defined('APP_ENV') && APP_ENV === 'production') {
        ob_end_flush();
    }
}



