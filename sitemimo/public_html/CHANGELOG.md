# Changelog

All notable changes to the Mimo Site project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.3.8] - 2025-01-24

### Fixed
- **Testimonials Text Overlap**: Corrigido problema de texto sobreposto no carousel de depoimentos
  - Cards inativos agora usam `visibility: hidden` além de `opacity: 0`
  - Transição suave mantida com `transition: opacity 0.6s ease-in-out, visibility 0.6s ease-in-out`
  - Garante que apenas o card ativo seja visível, eliminando sobreposição de texto
  - Aplicado tanto em `.testimonial-card` quanto em `.carousel-fade .testimonial-card`

### Changed
- **Service Worker**: Atualizado para v2.3.8
- **Version**: Atualizado para 2.3.8 em todos os arquivos
- **Asset Version**: Atualizado para 20250124

## [2.3.7] - 2025-01-23

### Added
- **Performance Audit**: Documento completo `PERFORMANCE-AUDIT.md` com análise do PageSpeed Insights
  - Métricas Core Web Vitals documentadas (FCP: 4.8s, LCP: 18.2s, TBT: 0ms, CLS: 0.001)
  - Plano de ação prioritário com estimativas de economia
  - Identificação de oportunidades de otimização (render blocking, image delivery, unused CSS/JS)
- **Build Scripts**: Scripts de otimização automatizados
  - `build/minify-assets.sh`: Minifica CSS e JavaScript usando terser e csso
  - `build/purge-css.sh`: Remove CSS não utilizado usando PurgeCSS
  - `build/README.md`: Documentação completa dos scripts de build
- **Render Blocking Optimization**: CSS não crítico agora é deferido
  - `dark-mode.css` e `animations.css` carregados via `loadCSS()` (não bloqueiam renderização)
  - Melhora FCP significativamente (economia estimada: 3.75s)

### Changed
- **Service Worker**: Atualizado para v2.3.7
- **Version**: Atualizado para 2.3.7 em todos os arquivos

### Performance Improvements
- **Render Blocking**: CSS não crítico deferido (dark-mode, animations)
- **Image Dimensions**: Todas as imagens principais já têm width/height explícitos
- **Minification Ready**: Scripts criados para minificar CSS (13KB economia) e JS (5KB economia)
- **PurgeCSS Ready**: Script criado para remover CSS não utilizado (76KB economia potencial)

## [2.3.6] - 2025-01-23

### Added
- **Animações on Scroll**: Sistema completo de animações baseado em Intersection Observer
  - CSS module `css/modules/animations.css` com animações fade-in, scale-in, hover effects
  - JavaScript `js/animations.js` para ativar animações quando elementos entram no viewport
  - Classes aplicadas: `.fade-in-up`, `.fade-in-left`, `.fade-in-right`, `.scale-in`, `.img-hover`, `.card-hover`
  - Animações aplicadas na homepage: hero section, categorias mobile, cards de serviços desktop
  - Suporte a `prefers-reduced-motion` para acessibilidade
  - Fallback para browsers sem Intersection Observer
- **AVIF Support**: Suporte completo para formato AVIF nas imagens principais
  - Script `build/generate-avif-main-images.sh` para gerar versões AVIF
  - 13 imagens principais convertidas para AVIF (bgheader, mimo5, categorias, serviços)
  - Função `picture_webp()` já suportava AVIF automaticamente
  - AVIF é ~30% menor que WebP, melhorando performance significativamente
- **Lazy Loading Nativo**: Verificação e otimização do lazy loading
  - Todas as imagens abaixo do fold já usam `loading="lazy"` via `picture_webp()`
  - Imagens above-the-fold (mimo5.png) corretamente sem lazy loading
  - 22 imagens com lazy loading, 2 sem (above-the-fold)

### Changed
- **Font Path Fix**: Corrigido caminho da fonte Akrobat no CSS
  - `product.css`: caminho atualizado de `url(Akrobat-Regular.woff)` para `url(/Akrobat-Regular.woff)`
  - `minified/product.min.css`: caminho corrigido para absoluto
  - Resolve erro 404 quando CSS minificado é carregado de subdiretórios

### Fixed
- **Font Loading Error**: Resolvido erro 404 ao carregar `minified/Akrobat-Regular.woff`
  - Caminho relativo causava erro quando CSS minificado era carregado
  - Caminho absoluto garante que fonte seja encontrada independente do contexto

## [2.3.5] - 2025-01-22

### Added
- **Dark Mode - Página de Contato**: Estilos dark mode completos para página de contato
  - Hero section (`.page-hero`) com fundo escuro adaptado
  - Info cards (`.info-card`) com cores consistentes e hover effects
  - Map container com filtro de brilho para melhor visualização no dark mode
  - Action buttons (`.action-btn-primary`, `.action-btn-secondary`) com cores da marca
  - Horário status (`.horario-status`) com cores semânticas (verde para aberto, vermelho para fechado)
  - Alerts do Bootstrap (success, danger, warning) com cores adaptadas para dark mode
  - Formulário de contato com inputs customizados (`.input100`, `.wrap-input100`) estilizados
- **Dark Mode - Página de Vagas**: Estilos dark mode para cards de vagas
  - `.vaga-card` com fundo escuro e bordas sutis
  - `.vaga-card.vaga-candidatar` com destaque especial usando rosa da marca
  - Hover effects consistentes com o resto do site
- **Dark Mode - Componentes Genéricos**: Estilos dark mode para componentes Bootstrap
  - Accordion (`.accordion-item`, `.accordion-button`, `.accordion-body`)
  - List groups (`.list-group-item`)
  - Badges (`.badge`)
  - Tables (`.table`, `.table thead`, `.table tbody`)
  - FAQ items (`.faq-item`, `.faq-question`, `.faq-answer`)
- **Ícones SVG no Footer**: Substituição de Font Awesome/Ionicons por SVGs inline
  - Ícones de localização, telefone e WhatsApp agora são SVGs inline
  - Resolve problemas de CSP (Content Security Policy) bloqueando scripts externos
  - Tamanho consistente (20px × 20px) para todos os ícones
  - Aplicado em todas as páginas: `index.php`, `contato.php`, `404.php`, `inc/service-template.php`
  - Removidos scripts do Ionicons que não eram necessários

### Changed
- **Dark Mode CSS**: Arquivo `css/modules/dark-mode.css` expandido significativamente
  - Adicionados ~200 linhas de estilos específicos para páginas de contato, vagas e componentes genéricos
  - Cores mantêm identidade da marca (rosa `#d4a5b0` adaptado para dark mode)
  - Fundos usando Material Design dark (`#121212`, `#1e1e1e`, `#2a2a2a`)
- **Footer Icons**: Migração completa de Font Awesome/Ionicons para SVGs inline
  - Ícones agora renderizam instantaneamente sem dependência de JavaScript externo
  - Melhor compatibilidade com CSP
  - Tamanho visual consistente entre todos os ícones

### Fixed
- **CSP Issues**: Resolvido bloqueio de scripts do Ionicons pelo Content Security Policy
  - SVGs inline não requerem scripts externos
  - Ícones funcionam mesmo com CSP restritivo
- **Icon Sizing**: Resolvido problema de ícones desproporcionais no footer
  - Todos os ícones agora têm tamanho fixo de 20px × 20px
  - Alinhamento vertical consistente
  - Cores uniformes usando variáveis CSS

## [2.3.4] - 2025-01-21

### Fixed
- **Content Security Policy**: Fixed Font Awesome CSP blocking by adding `cdnjs.cloudflare.com` to allowed sources
- **Tidio Chat**: Removed broken Tidio chat script (404 errors) from all pages
- **Font Loading**: Fixed Akrobat font loading - removed incorrect CSS loading, now uses @font-face properly
- **Mixed Content**: Fixed HTTP links in FAQ page (changed to HTTPS)
- **bcSwipe Plugin**: Added availability check and retry logic to prevent "bcSwipe is not a function" errors
- **Manifest.json**: Fixed manifest path from `favicon/site.webmanifest` to `/manifest.json` across all pages
- **Header Layout**: Fixed dark mode toggle button breaking navbar layout
  - Changed display from `flex` to `inline-flex`
  - Added proper Bootstrap classes for alignment
  - Added responsive styles for mobile
- **Logo Display**: Fixed logo distortion in navbar
  - Removed fixed width/height attributes causing distortion
  - Added `width: auto` and `object-fit: contain` to maintain aspect ratio
  - Added CSS to prevent transforms that distort the logo
- **Hero Section**: Fixed hero section to occupy full width
  - Removed padding that was reducing available space
  - Added `width: 100%` to ensure full coverage
- **About Section Image**: Fixed `mimo5.png` image to occupy full column width
  - Removed `mx-auto` that was limiting space
  - Added `width: 100%` styles to image and picture elements
  - Improved flexbox alignment for better display

### Changed
- **Script Loading**: Added `defer` attribute to `main.js` in all pages for better performance
- **Dark Mode Toggle**: Improved toggle button styling and positioning in navbar

## [2.3.1] - 2025-01-15

### Added
- **Sprint 1 - Performance & Core Web Vitals**: Major performance optimizations
  - Image optimization pipeline: Scripts for generating responsive sizes (1x, 2x, 3x), AVIF conversion, and complete optimization
  - Enhanced image helper: `picture_webp()` now supports AVIF → WebP → fallback with responsive srcset
  - Critical CSS expanded: More comprehensive above-the-fold CSS for faster FCP
  - Service Worker & PWA: Offline support, cache management, Web App Manifest
  - Offline page: User-friendly offline experience
- **Build Scripts**: New image optimization scripts
  - `build/generate-responsive-images.sh`: Generates 1x, 2x, 3x image sizes
  - `build/convert-avif.sh`: Converts images to AVIF format (30% smaller than WebP)
  - `build/optimize-all-images.sh`: Complete optimization pipeline

### Changed
- **Image Helper**: Enhanced `picture_webp()` function
  - Now supports AVIF format (highest priority)
  - Automatic srcset generation for responsive images
  - Better path resolution for subdirectories
- **Critical CSS**: Expanded to include navbar, hero, container, and mobile optimizations
- **Service Worker**: Implemented cache-first strategy for static assets, network-first for pages
- **PWA Support**: Added manifest.json, service worker registration, offline page

### Fixed
- **Review Display**: Fixed "Undefined array key 'text'" warning by using `get_review_text()` consistently
- **Star Ratings**: Replaced Font Awesome stars with Unicode stars (★/☆) for guaranteed visibility
  - Stars now always appear, even if Font Awesome doesn't load
  - Added CSS styling for Unicode stars with proper colors

## [2.3.0] - 2025-01-15

### Added
- **Google Reviews System Enhancement**: Major improvements to review display and filtering
  - Smart photo detection: Distinguishes real photos from placeholder initials
  - Review randomization: Randomizes top 50 reviews on each page load for variety
  - Advanced filtering: Removes COVID-related reviews, excluded authors, low ratings
  - Photo prioritization: Reviews with real photos prioritized over placeholders
  - Text length optimization: Prioritizes medium-length reviews (100-500 chars) for credibility
  - Recent reviews priority: Prioritizes reviews from last 2 years while maintaining quality
- **Review Management Scripts**: New PHP scripts for review management
  - `scripts/limpar-reviews.php`: Clean reviews database (removes COVID, low ratings, excluded authors)
  - `scripts/FREQUENCIA-ATUALIZACAO.md`: Documentation on review update frequency
- **Scraper Documentation**: Comprehensive scraper usage guides
  - `scripts/COMO-USAR-SCRAPER.md`: Complete scraper usage instructions
  - `scripts/TROUBLESHOOTING-SCRAPER.md`: Troubleshooting guide for scraper issues
  - `scripts/COMO-LIMPAR-REVIEWS.md`: Guide for cleaning review database
  - `scripts/temp-scraper/config-mimo.yaml`: Pre-configured scraper settings for Mimo

### Changed
- **Review Display Logic**: Enhanced review selection and ordering
  - Increased review pool from 20 to 50 for better randomization
  - New sorting algorithm: Photo real > Medium text > Rating > Recency > Newest
  - Randomization applied after quality sorting (always shows best reviews, but varies selection)
- **Review Filtering**: Improved filtering system
  - Excludes reviews mentioning COVID-related keywords
  - Excludes reviews from specific authors (conflict of interest)
  - Filters out reviews with rating < 4 stars
  - Removes reviews with no text or very short text (< 10 chars)
- **Photo Detection**: Smart photo vs placeholder detection
  - Analyzes URL patterns to identify real photos vs placeholder initials
  - ALV-Uj format: Requires 50+ char identifier for real photos
  - ACg8oc format: Requires 49+ char identifier (placeholders usually 48 chars)
  - Only prioritizes reviews with confirmed real photos

### Fixed
- **Review Text Extraction**: Unified text extraction across different review formats
  - New `get_review_text()` function handles multiple formats (text, comment, description)
  - Supports multi-language description arrays from scraper
  - Consistent text length calculation for sorting
- **Photo URL Mapping**: Fixed photo display issues
  - Maps `profile_picture` (scraper format) to `profile_photo` (display format)
  - Handles both field names consistently across codebase
  - Added fallback for missing photos with proper placeholder display

## [2.2.9] - 2025-01-14

### Added
- **Code Documentation**: Comprehensive code comments added throughout the codebase
  - CSS files now include usage comments explaining where styles are applied
  - PHP helpers include detailed PHPDoc comments
  - JavaScript functions include inline documentation
  - Asset helper functions documented with usage examples
- **Code Audit Document**: New `CODE-AUDIT.md` file with comprehensive code quality analysis
  - Identified code quality issues and recommendations
  - Metrics and best practices documentation
  - Improvement roadmap

### Changed
- **Contact Form**: Improved form field labels and select dropdown styling
  - Added descriptive labels above all form fields
  - Centered "Selecione o assunto" placeholder text
  - Improved form layout and spacing
- **Contact Page Buttons**: Enhanced phone and WhatsApp action buttons
  - WhatsApp button now uses inline SVG icon for better reliability
  - Both buttons are properly clickable with correct href attributes
  - Improved visual consistency

### Fixed
- **Form Layout**: Fixed form field alignment issues
  - Labels now properly positioned outside input containers
  - Input fields maintain consistent styling
  - Improved spacing between form elements

## [2.2.8] - 2025-11-14

### Changed
- **Footer Redesign**: Complete footer redesign with modern 3-column layout
  - Navigation links now displayed vertically for better readability
  - Contact information clearly labeled (Telefone vs WhatsApp)
  - Social media icons section with improved alignment
  - Reduced spacing between contact items (8px gap)
  - Physical address added to footer
- **Social Media Icons**: Replaced Font Awesome icons with inline SVG for better reliability
  - Instagram, Facebook, and WhatsApp icons now use SVG inline
  - Icons are always visible regardless of Font Awesome loading
  - Consistent 20x20px size across all icons
  - Applied to all pages: index.php, contato.php, vagas.php, 404.php, service-template.php

### Fixed
- **Layout Spacing**: Removed white space between testimonials section and footer
  - Removed extra closing `</div>` tag causing large gap
  - Footer now directly follows testimonials section
  - Adjusted margins and padding for seamless transition
- **Contact Section Spacing**: Reduced gap between phone and WhatsApp in footer
  - Changed from 15px to 8px gap for more compact layout
  - Applied to both desktop and mobile breakpoints

## [2.2.7] - 2025-01-20

### Changed
- **Carousel Optimization**: Optimized testimonials carousel for better performance and UX
  - Reduced carousel height from 650px to 550px for more compact design
  - Reduced content padding from 50px to 30px vertical
  - Optimized avatar size from 100px to 80px
  - Reduced spacing between elements (margins and paddings)
  - Smaller font sizes for better space utilization
  - Mobile responsive adjustments (500px height on mobile)
- **Layout Shift Fix**: Fixed carousel layout shift issue during transitions
  - All testimonial cards now use `position: absolute` consistently (never changes to `relative`)
  - Only `z-index` changes between active and inactive cards
  - Changed container from `display: flex` to `display: block` to prevent absolute positioning issues
  - Eliminated visual "jump" when transitioning between reviews

### Fixed
- **Carousel Transition**: Improved transition smoothness
  - Fixed position change causing height jumps during carousel transitions
  - All cards maintain consistent positioning throughout transitions
  - Smooth fade transitions without layout shifts

## [2.2.6] - 2025-01-19

### Added
- **404 Page Improvements**: Enhanced 404 error page with service cards and icons
  - Added visual service cards with Font Awesome icons for better conversion
  - Services: Esmalteria, Cílios e Design, Estética Corporal, Estética Facial, Micropigmentação, Salão
  - Cards with hover effects and animations
  - Responsive layout for mobile devices
- **Blog Post Page**: Created/updated blog post template (`blog/posts/post1.html`)
  - Full blog post structure with hero section
  - Navbar scroll behavior matching main site (transparent → dark on scroll)
  - Improved CSS with card-based content layout
  - Better typography and spacing
  - Responsive design

### Changed
- **Branding Consistency**: Replaced "MIMO Estética" with "Mimo" or "Minha Mimo" across entire site
  - Updated page titles and descriptions in `index.php`, `vagas.php`, `404.php`
  - Updated SEO meta tags in `inc/seo-helper.php`
  - Updated service template in `inc/service-template.php`
  - Updated footer text in blog post
- **404 Page**: Improved user experience and conversion potential
  - Replaced text-only service links with visual cards
  - Added gradient icon backgrounds matching site color scheme
  - Better call-to-action layout

### Fixed
- **Blog Navbar**: Fixed navbar scroll behavior on blog pages
  - Added JavaScript to detect scroll and apply dark background
  - Added CSS for `.compressed` navbar state
  - Navbar now matches main site behavior (transparent at top, dark when scrolled)
- **Blog CSS**: Fixed CSS loading and styling issues
  - Corrected relative paths for assets
  - Added proper Bootstrap CDN links
  - Improved layout and spacing

## [2.2.5] - 2025-01-19

### Changed
- **Performance Improvements**: Implemented PageSpeed Insights optimizations
  - Added `font-display: swap` to Google Fonts (estimated savings: 730ms)
  - Added `defer` attribute to non-critical scripts (estimated savings: 690ms)
  - Fixed HTTPS mixed content issue (piwik.js now uses HTTPS)
  - Updated CSP to allow cluster-piwik.locaweb.com.br
- **Accessibility**: Added label and aria-label to form select element
- **Removed**: All references to agendamento.salaovip.com.br and agendamento.avec.beauty
  - Removed booking iframe and buttons
  - Removed booking modal
  - Removed booking URLs from CSP headers
- **Vagas Page**: Updated text from "Faça parte da equipe MIMO Estética" to "Faça parte da equipe Mimo"
- **Vagas Page**: Fixed back-to-top button positioning (moved to end of body, same as index.php)

## [2.2.4] - 2025-01-19

### Added
- **Vagas Page**: Nova página para exibir vagas disponíveis na MIMO
  - Página `/vagas.php` com design consistente com o resto do site
  - Hero section com título "TRABALHE CONOSCO"
  - Cards de vagas com informações detalhadas (tipo, localização, salário)
  - Seções: Sobre a Mimo, Sobre a Vaga, Atividades Exercidas, Experiência e Formação, Competências, Nosso Jeito de Trabalhar
  - Botão de candidatura que abre email pré-preenchido
  - Link no menu principal (header)
  - Card na homepage na seção de serviços
  - Adicionado ao sitemap.xml para SEO

### Changed
- Documentação atualizada automaticamente (versionamento agora é automático)

## [2.2.3] - 2025-01-19

### Fixed
- **Form Submission**: Removido redirecionamento para WhatsApp, formulário agora envia para email
  - Email de destino atualizado para `atendimento@minhamimo.com.br`
  - Reply-To configurado com email do remetente
  - JavaScript simplificado para apenas validação e loading state
  - Formulário faz submit normal via POST para processamento PHP

## [2.2.2] - 2025-01-19

### Added
- **Font Display Optimization**: Adicionado `font-display: swap` no @font-face do Akrobat
  - Melhora First Contentful Paint (FCP)
  - Texto visível imediatamente, fonte carrega depois
- **Print Styles**: Estilos otimizados para impressão
  - Esconde navegação, botões e elementos não essenciais
  - Remove backgrounds e sombras
  - Mostra URLs dos links
  - Quebras de página inteligentes
- **Form Loading States**: Feedback visual durante envio de formulário
  - Spinner animado no botão "ENVIAR"
  - Botão desabilitado durante envio
  - Funciona tanto para formulário PHP quanto WhatsApp redirect

### Changed
- CSS e JS re-minificados com as novas melhorias

## [2.2.1] - 2025-01-19

### Fixed
- **Image Paths**: Corrigidos caminhos de imagens em CSS para usar caminhos absolutos
  - Imagens do hero (bgheader.jpg) agora carregam corretamente
  - Imagens de categorias (salao, corporal, esmalteria, facial) corrigidas
  - Imagens de páginas de serviço (servicos.css) corrigidas com caminhos absolutos
- **Header Transparency**: Header agora é transparente inicialmente, escurecendo apenas ao rolar
  - Removido `background-color: rgba(0, 0, 0, 0.3)` fixo do `.navbar`
  - Header transparente no topo, escurece com classe `.compressed` via JavaScript
- **Breadcrumb Positioning**: Breadcrumb posicionado sobre imagem de fundo sem quebrar layout
  - Posicionamento absoluto sobre hero image
  - Estilo branco com sombra para legibilidade
  - Não empurra mais o conteúdo para baixo

### Changed
- Todos os caminhos de imagens em `product.css` e `servicos.css` convertidos para absolutos (`/img/...`)
- CSS re-minificado com caminhos corrigidos

## [2.2.0] - 2025-01-19

### Added
- **Cache Headers System**: Implementação completa de cache headers para assets estáticos
  - Novo arquivo `inc/cache-headers.php` com funções para gerenciar cache por tipo de arquivo
  - Cache longo (1 ano) para imagens, fontes e assets versionados
  - Cache curto (1 hora) para HTML/PHP com must-revalidate
  - Suporte a ETags e Last-Modified para validação 304
  - Regras de cache no `.htaccess` para assets estáticos
- **Asset Helper System**: Sistema de gerenciamento de assets com suporte a minificação
  - Novo arquivo `inc/asset-helper.php` com funções `css_tag()` e `js_tag()`
  - Detecção automática de arquivos minificados quando `USE_MINIFIED = true`
  - Fallback automático para arquivos originais se minificados não existirem
- **UI Improvements**:
  - Botão "Voltar ao Topo" (`inc/back-to-top.php`) com scroll suave
  - Breadcrumbs com Schema.org structured data (`inc/breadcrumbs.php`)
  - Estilos de breadcrumbs adicionados ao `product.css`
- **Build Scripts Improvements**:
  - Scripts de minificação atualizados para usar `--yes` flag do npx
  - Instalação automática de dependências (csso-cli, terser)

### Changed
- **Minification**: Ativada minificação CSS/JS em produção
  - `USE_MINIFIED = true` no `config.php`
  - Arquivos minificados criados na pasta `minified/`
  - `index.php` e `service-template.php` agora usam asset helper
- **Cache Strategy**: Implementada estratégia de cache completa
  - Headers de cache aplicados via PHP e `.htaccess`
  - Diferentes tempos de cache baseados no tipo de arquivo
- **Build Process**: Scripts de build melhorados para instalação automática

### Fixed
- Corrigido uso de `APP_VERSION` no comentário do `index.php` antes do carregamento do config
- Scripts de minificação agora funcionam sem instalação prévia de pacotes

## [2.1.0] - 2025-01-19

### Added
- **SEO Optimization**: Implementação completa de otimizações de SEO
  - Helper de SEO (`inc/seo-helper.php`) com funções para meta tags, Open Graph, Twitter Cards e Schema.org
  - Meta tags otimizadas por página (title, description, keywords)
  - Open Graph tags para compartilhamento em redes sociais
  - Twitter Cards para compartilhamento no Twitter
  - Schema.org structured data:
    - LocalBusiness (BeautySalon) na homepage
    - Service schema em todas as páginas de serviço
    - BreadcrumbList para navegação hierárquica
  - Sitemap.xml completo
  - Robots.txt otimizado
  - Canonical URLs em todas as páginas
  - Documentação completa em `SEO-OPTIMIZATION.md`

### Changed
- Meta tags agora são geradas dinamicamente com palavras-chave otimizadas
- Títulos e descrições específicas por página de serviço
- Coordenadas geográficas adicionadas ao Schema.org para SEO local

## [2.0.0] - 2025-01-19

### Added
- **Template System**: Implemented `inc/service-template.php` for all service pages, reducing code duplication by ~70%
- **WebP Image Optimization**: 
  - Created `inc/image-helper.php` with `picture_webp()` function for automatic WebP with fallbacks
  - WebP conversion script (`build/convert-webp.sh`)
  - Applied WebP optimization to homepage and all 6 service pages
  - Automatic lazy loading for images
- **Performance Optimizations**:
  - Critical CSS inlining (`inc/critical-css.php`)
  - Resource hints (DNS prefetch, preconnect, preload)
  - Lazy loading for below-the-fold images
- **Build Tools**:
  - WebP conversion script
  - CSS minification script (`build/minify-css.sh`)
  - JS minification script (`build/minify-js.sh`)
  - Build documentation (`build/README.md`)
- **Documentation**:
  - Comprehensive `README.md` with architecture, workflow, and maintenance guides
  - `IMPROVEMENTS.md` with future roadmap
  - `CHANGELOG.md` (this file)
- **Configuration System**:
  - Environment variable support via `.env` file
  - `ASSET_VERSION` constant for cache busting
  - `USE_MINIFIED` flag for production builds

### Changed
- **Service Pages**: All 6 service pages migrated to use template system
  - `cilios/index.php`
  - `esmalteria/index.php`
  - `estetica/index.php`
  - `esteticafacial/index.php`
  - `micropigmentacao/index.php`
  - `salao/index.php`
- **Image Handling**: All images now use `<picture>` elements with WebP support
- **CSS Architecture**: 
  - Added `.service-content` responsive class
  - Standardized header heights across all service pages
  - Responsive hero image sizing (50vh with min/max constraints)
- **Security Headers**: Centralized in `inc/security-headers.php`
- **Error Handling**: Suppressed deprecation warnings for PHP 8.4 compatibility

### Fixed
- Fixed typo in `salao/index.php` (`container-my-5` → `container my-5`)
- Fixed white columns issue in service image containers
- Fixed tab switching on service pages (ID mismatch with extra 's' suffix)
- Fixed responsive image display with `object-fit: cover`
- Fixed hero image sizing consistency across all pages
- Removed incorrect `srcset` attributes from category images

### Removed
- Removed `phpinfo()` test file (`form.php`)
- Removed unused files (`CURSOR_product.css`, `CURSOR_main.js`)
- Removed "showliana" testimonial from homepage
- Removed hardcoded URLs (replaced with relative paths)
- Removed commented-out promotional carousel code

### Security
- Moved Mailgun credentials to environment variables
- Implemented security headers (X-Frame-Options, CSP, etc.)
- Updated input sanitization to use `FILTER_SANITIZE_FULL_SPECIAL_CHARS` (PHP 8.1+ compatible)
- Removed deprecated `E_STRICT` constant usage

## [1.0.0] - 2018-01-01

### Added
- Initial website launch
- Homepage with contact form
- Service pages for all treatment categories:
  - Cílios e Design
  - Esmalteria
  - Estética Corporal
  - Estética Facial
  - Micropigmentação
  - Salão
- Bootstrap 4.5.2 integration
- Responsive design
- Contact form with Mailgun SMTP
- Google Tag Manager integration
- Testimonials carousel
- FAQ page

---

## Version History

- **2.0.0** (2025-01-19): Major refactoring, template system, WebP optimization
- **1.0.0** (2018-01-01): Initial release

## Versioning Scheme

This project uses [Semantic Versioning](https://semver.org/):
- **MAJOR** version for incompatible API changes or major architectural changes
- **MINOR** version for new features in a backwards-compatible manner
- **PATCH** version for backwards-compatible bug fixes

### Version Format
`MAJOR.MINOR.PATCH` (e.g., `2.0.0`)

### Release Types
- **Major Release**: Significant changes, breaking changes, or major new features
- **Minor Release**: New features, enhancements, backwards-compatible
- **Patch Release**: Bug fixes, security patches, small improvements

---

[2.0.0]: https://github.com/yourusername/mimo-site/compare/v1.0.0...v2.0.0
[1.0.0]: https://github.com/yourusername/mimo-site/releases/tag/v1.0.0

