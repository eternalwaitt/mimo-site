/**
 * PurgeCSS Configuration
 * Remove unused CSS from stylesheets
 */

module.exports = {
  content: [
    './**/*.php',
    './**/*.html',
    './inc/**/*.php',
    './css/**/*.css', // Include CSS files to check for class usage
  ],
  css: [
    './product.css',
    './css/modules/dark-mode.css',
    './css/modules/animations.css',
    './css/modules/mobile-ui-improvements.css',
    './css/modules/accessibility-fixes.css',
  ],
  safelist: [
    // Bootstrap classes that might be added dynamically
    /^carousel/,
    /^fade/,
    /^show/,
    /^active/,
    /^disabled/,
    /^collapsed/,
    /^collapse/,
    /^dropdown/,
    /^modal/,
    /^tooltip/,
    /^popover/,
    /^tab/,
    /^nav/,
    /^navbar/,
    /^btn/,
    /^badge/,
    /^alert/,
    /^card/,
    /^form/,
    /^input/,
    /^select/,
    /^textarea/,
    /^label/,
    /^row/,
    /^col/,
    /^container/,
    /^d-/,
    /^m-/,
    /^p-/,
    /^text-/,
    /^bg-/,
    /^border/,
    /^rounded/,
    /^shadow/,
    /^position/,
    /^w-/,
    /^h-/,
    /^overflow/,
    /^visible/,
    /^hidden/,
    /^invisible/,
    /^opacity/,
    /^z-/,
    // Dark mode classes
    /^dark-mode/,
    /^light-mode/,
    // Animation classes
    /^fade-in/,
    /^fade-out/,
    /^slide/,
    /^animate/,
    // Custom classes that might be added via JavaScript
    /^testimonial/,
    /^review/,
    /^service/,
    /^vaga/,
    /^contact/,
    /^form/,
    /^error/,
    /^success/,
    /^warning/,
    /^info/,
    // Lucide Icons (substituiu Font Awesome)
    /^lucide/,
    /data-lucide/,
    // Responsive classes
    /^sm-/,
    /^md-/,
    /^lg-/,
    /^xl-/,
  ],
  fontFace: true,
  keyframes: true,
  variables: true,
  rejected: false,
  rejectedCss: false,
  output: './css/purged/',
  defaultExtractor: (content) => {
    // Extract classes from PHP/HTML
    const broadMatches = content.match(/[^<>"'`\s]*[^<>"'`\s:]/g) || [];
    const innerMatches = content.match(/[^<>"'`\s.()]*[^<>"'`\s.():]/g) || [];
    
    return broadMatches.concat(innerMatches);
  },
};

