# Changelog

All notable changes to the Mimo Site project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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

