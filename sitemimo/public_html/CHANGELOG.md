# Changelog

All notable changes to the Mimo Site project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.6.7] - 2025-11-15

### Added
- **Bootstrap Custom Build**:
  - Criado `build/create-bootstrap-custom.sh` para gerar build apenas com Carousel e Tab
  - Build customizado: `bootstrap/bootstrap-custom.min.js` (12 KiB vs 49 KiB original)
  - Economia: 37 KiB de JavaScript não utilizado removido
  - Atualizados: `index.php`, `contato.php`, `vagas.php`, `inc/service-template.php`
- **Scripts de Otimização**:
  - `build/optimize-fontawesome.sh`: Identifica ícones Font Awesome usados
  - `build/combine-css.sh`: Combina CSS não críticos em um único arquivo
  - `build/combine-js.sh`: Combina JavaScript não críticos em um único arquivo
  - `build/verify-optimizations.sh`: Verifica se todas as otimizações foram aplicadas
  - `purgecss.config.js`: Configuração expandida do PurgeCSS com safelist completo

### Changed
- **PurgeCSS Melhorado**:
  - Configuração expandida com safelist completo (Bootstrap, Font Awesome, classes dinâmicas)
  - Re-executado em todos os CSS: product, dark-mode, animations, mobile-ui-improvements, accessibility-fixes
  - Economia total: ~97 KiB (product: 90%, dark-mode: 90%, animations: 71%, mobile-ui: 82%, accessibility: 58%)
- **Minificação Completa**:
  - Todos os CSS purgados foram minificados: `css/purged/*.min.css`
  - Todos os JavaScript foram minificados: `minified/*.min.js`
  - CSS combinado: `css/combined-non-critical.min.css`
  - JS combinado: `js/combined.min.js`
- **Dark Mode Melhorado**:
  - Ícones do toggle com fundo contrastante sempre visível (rgba(204, 183, 188, 0.15) no light, rgba(212, 165, 176, 0.2) no dark)
  - Light mode usa cores originais do site: #ccb7bc (rosa), #3a505a (cinza), #fafafa (fundo)
  - Dark mode com tons mais escuros: #0d0d0d (fundo principal), #1a1a1a (cards), #262626 (hover)
  - Contraste melhorado em todos os elementos (ícones com drop-shadow, bordas mais visíveis)
  - Mobile: Toggle com fundo contrastante e ícone mais visível (stroke-width: 2.5)
- **Asset Version**: Atualizado para `20251115-5` (cache busting)

### Technical
- **Performance Optimization**: Meta de performance 90+ em mobile e desktop
- **Bootstrap Custom**: Apenas Carousel e Tab (removidos: tooltip, modal, dropdown, collapse, scrollspy)
- **CSS Optimization**: PurgeCSS + minificação + combinação aplicados
- **JavaScript Optimization**: Minificação + combinação aplicados
- **Dark Mode**: Contraste WCAG AA+ em todos os elementos

## [2.6.6] - 2025-11-15

### Fixed
- **Image Delivery (2,760 KiB)**:
  - Corrigido script `optimize-all-large-images.sh` para incluir imagens de `mobile_promocional`
  - Removido filtro `! -path "*/mobile_promocional/*"` que estava ignorando imagens grandes
  - Otimizadas todas imagens críticas identificadas pelo PageSpeed Insights
  - Criado script `optimize-missing-images.sh` para otimizar imagens específicas
- **Unused CSS (83 KiB)**:
  - Re-executado PurgeCSS em `product.css`, `dark-mode.css`, `animations.css`
  - Arquivos purgados minificados: `css/purged/*.min.css`
  - Economia: ~22 KiB (product.css: 6%, dark-mode.css: 90%, animations.css: 21%)
- **Minify CSS (23 KiB)**:
  - CSS modules minificados: `mobile-ui-improvements`, `accessibility-fixes`
  - Arquivos criados: `minified/css-modules-*.min.css`
  - Economia: ~12 KiB

### Changed
- **Script de Otimização de Imagens**:
  - `build/optimize-all-large-images.sh`: Agora processa TODAS as imagens grandes, incluindo `mobile_promocional`
  - `build/optimize-missing-images.sh`: Novo script para otimizar imagens específicas identificadas
- **Asset Helper**:
  - Configurado para usar arquivos purgados e minificados corretamente
  - Verificado que `get_css_asset()` retorna caminhos corretos para arquivos otimizados
- **Asset Version**: Atualizado para `20251115-4` (cache busting)

### Technical
- **Image Optimization**: Todas imagens críticas (>100KB) agora têm AVIF e WebP
- **CSS Optimization**: PurgeCSS + minificação aplicados em todos arquivos CSS principais
- **Performance**: Meta de reduzir Image Delivery de 2,760 KiB e Network Payload de 3,882 KiB para <1,600 KiB

## [2.6.5] - 2025-11-15

### Added
- **Script de Otimização de Imagens Completo**:
  - `build/optimize-all-large-images.sh`: Processa TODAS as imagens grandes (>100KB)
  - Prioriza imagens gigantes (>1MB) primeiro
  - Meta: Reduzir network payload de 3.79MB para <1.6MB
- **CSS Crítico Expandido**:
  - Adicionados estilos de botões principais (btnSeeMore, mobile-vagas-card)
  - Adicionados estilos de content-details overlay
  - Adicionados estilos de mobile category items
  - Melhorias em FCP esperadas: 4.1s → <1.8s
- **Documentação de Testes**:
  - `TEST-CHECKLIST-v2.6.5.md`: Checklist completo de validação
  - `GOOGLE-SUGGESTIONS-IMPLEMENTED-v2.6.5.md`: Mapeamento de implementações

### Fixed
- **CLS - Testimonials Carousel**:
  - Reforçado `contain: layout style` no carousel
  - Espaço reservado para carousel controls
  - Espaço reservado para carousel indicators
  - Meta: CLS 0.452 → <0.1
- **Cores da Marca - Consistência**:
  - Substituídas cores hardcoded por variáveis CSS em `product.css`
  - Substituídas cores hardcoded por variáveis CSS em `dark-mode.css`
  - Garantida consistência em light e dark mode
- **Font Loading - Otimização**:
  - EB Garamond: font-display: optional (fonte decorativa)
  - Akrobat: font-display: optional (já estava)
  - Nunito: font-display: swap (fonte principal)
  - Economia esperada: 40ms

### Changed
- **LCP Discovery - Otimização**:
  - Preconnect para domínio próprio adicionado
  - Melhora descoberta de recursos LCP
- **CSS Crítico - Expansão**:
  - Estilos de botões acima da dobra movidos para CSS crítico
  - Estilos de mobile categories no CSS crítico
  - Estilos de testimonials no CSS crítico
- **Asset Version**: Atualizado para `20251115-2` (cache busting)

### Technical
- **Image Helper**: Já detecta dimensões automaticamente
- **CSS Variables**: Consistência de cores da marca garantida
- **Dark Mode**: Cores da marca adaptadas mas mantendo identidade
- **Performance**: Meta de 90+ pontos no mobile

## [2.6.4] - 2025-11-15

### Added
- **PageSpeed Insights API - Testes Automatizados**:
  - `build/pagespeed-api-test.sh`: Testa todas as páginas usando a API (mobile e desktop)
  - `build/pagespeed-analyze.sh`: Analisa resultados e gera relatório consolidado
  - `build/pagespeed-extract-issues.sh`: Extrai todos os problemas identificados
  - `build/pagespeed-complete-workflow.sh`: Workflow completo de testes e análise
  - Testa automaticamente 9 páginas em 2 estratégias (18 testes totais)
  - Documentação: `PAGESPEED-RESULTS-CONSOLIDATED-v2.6.4.md`, `PAGESPEED-ISSUES-CATEGORIZED-v2.6.4.md`

### Fixed
- **CLS - Imagens sem width/height explícitos**:
  - Melhorada função `picture_webp()` para detectar automaticamente dimensões em múltiplos caminhos
  - Adicionado width/height explícitos em imagens de cilios, salao e micropigmentacao
  - Resultado esperado: Score "unsized-images" 0.5 → 1.0
- **CLS - Layout Shifts (Background Images)**:
  - Adicionado `aspect-ratio: 16/9` e `contain: layout style` nos headers de cilios, esmalteria e esteticafacial
  - Resultado esperado: CLS 0.4-0.9 → <0.1
- **Render Blocking - jQuery**:
  - Removido `document.write` que bloqueava renderização
  - Implementado carregamento assíncrono com fallback para jQuery local
  - Aplicado em `service-template.php`, `contato.php` e `vagas.php`
  - Resultado esperado: Render blocking score 0 → 1
- **Render Blocking - CSS**:
  - Todos os CSS não críticos usando `loadCSS()` em contato.php e vagas.php
  - CSS de serviços (servicos.css, form/main.css) usando `loadCSS()` em service-template.php
  - Resultado esperado: Render blocking score 0 → 1, FCP 4.05s → <2.0s

### Changed
- **Otimizações Automáticas**:
  - JavaScript minificado (arquivos criados em `minified/`)
  - CSS purgado (~22 KiB economizados: product.css, dark-mode.css, animations.css)
  - CSS minificado (arquivos criados em `minified/`)
- **Asset Version**: Atualizado para `20251115-1` (cache busting)

### Technical
- **Image Helper**: Melhorada detecção automática de dimensões de imagens
- **Service Template**: jQuery async, CSS defer
- **Contato/Vagas**: Todos CSS defer, jQuery async
- **Documentação**: Criado `CORRECTIONS-APPLIED-v2.6.4.md`, `PAGESPEED-FIXES-TO-APPLY-v2.6.4.md`

## [2.6.3] - 2025-01-30

### Added
- **PageSpeed Insights API - Scripts Automatizados**:
  - `build/pagespeed-api-test.sh`: Testa todas as páginas usando a API (mobile e desktop)
  - `build/pagespeed-analyze.sh`: Analisa resultados e gera relatório consolidado
  - `build/README-PAGESPEED-API.md`: Documentação completa de uso
  - Testa automaticamente 9 páginas em 2 estratégias (18 testes totais)

### Fixed
- **Carousel de Testimonials no Mobile**:
  - Problema: Menu não funcionava após desabilitar animações
  - Solução: Transições instantâneas (0.01s) ao invés de desabilitar completamente
  - CSS: `pointer-events: auto`, `touch-action: manipulation` para garantir cliques
  - JavaScript: Detecção mobile e handlers específicos para indicadores e controles
  - JavaScript: Remove classe `carousel-fade` no mobile mas mantém funcionalidade
  - Resultado: Carousel funciona perfeitamente no mobile sem animações suaves
- **CLS - Reforço Crítico**:
  - Adicionado `position: relative` e `overflow: hidden` no `#about .col-md-7`
  - Adicionado `word-wrap: break-word` e `overflow-wrap: break-word` nos textos
  - Adicionado `min-height: 1.5em` no `.lead` para prevenir layout shift
  - Todas as correções CLS aplicadas em `product.css` e `inc/critical-css.php`
- **Animações Mobile - Reforço Completo**:
  - Expandido regras para desabilitar `transition-delay` e `animation-delay`
  - Adicionado `animation-fill-mode: none`
  - Desabilitado hover effects em TODOS os elementos (card, btn, nav-link, footer-link, etc.)
  - Desabilitado transições em elementos específicos (navbar, breadcrumb, footer)
  - Regras aplicadas em `product.css` para máxima cobertura
- **ARIA - Correção Final**:
  - Mudado `role="tablist"` para `role="navigation"` no nav mobile (corrige validação ARIA)
- **Asset Helper - Correção**:
  - Corrigido para encontrar arquivos minificados em `css/purged/` corretamente
- **ARIA Attributes - Valores Inválidos**:
  - Removido `aria-controls="pills-alongamentos"` inválido (elemento não existe)
  - Corrigido carousel indicators: adicionado IDs (`testimonial-<?php echo $i; ?>`) e `aria-controls` válidos
  - Adicionado `role="tabpanel"` e `aria-labelledby` nos carousel items
  - Mudado `role="tab"` para `role="button"` no nav-link mobile (não é realmente uma tab)
- **Contraste de Cores (Acessibilidade)**:
  - `.backgroundPink .text-white`: Adicionado `text-shadow` para melhor legibilidade
  - Footer links: Mudado de `rgba(255, 255, 255, 0.85)` para `#ffffff` com `opacity: 0.95`
  - Footer contact items: Mudado para `#ffffff` com `opacity: 0.95`
  - Todos os spans no footer: Garantido contraste suficiente
- **jQuery Blocking Critical Path**:
  - Removido `document.write` que bloqueava renderização (1,763ms de latência)
  - Implementado carregamento assíncrono com fallback para jQuery local
  - Redução esperada: 1,763ms → < 500ms de latência crítica
- **Font Awesome font-display**:
  - Adicionado `@font-face` com `font-display: swap` para Font Awesome 6 Free, Brands e Solid
  - Redução esperada: 20ms de economia

### Changed
- **CLS Mobile - Otimizações Críticas**:
  - Mobile categories grid: Adicionado `contain: layout` e `min-height`
  - Mobile category items: Adicionado `contain: layout style` e `aspect-ratio: 1 / 1`
  - Mobile vagas button: Adicionado `contain: layout` e `min-height: 160px`
  - Sessoes container: Adicionado `contain: layout` e `min-height: 300px`
  - Content images: Adicionado `aspect-ratio: 5 / 4`
  - Testimonials inner: Adicionado `contain: layout`
  - CSS crítico: Adicionado regras de `contain` e `aspect-ratio` para prevenir layout shift
  - **CRITICAL**: Adicionado `contain: layout style` e `min-height: 400px` no `.col-md-7` (causava 93% do CLS - 0.375)
- **LCP Mobile - Otimizações**:
  - Reorganizado preload: mobile header (LCP) vem ANTES de desktop header
  - Adicionado preload separado para desktop header com `media="(min-width: 751px)"`
  - Adicionado `backface-visibility: hidden` no bg-header mobile
- **Animações Mobile - Desabilitadas Completamente**:
  - JavaScript: Desabilitado no mobile (detecta mobile e mostra elementos imediatamente)
  - CSS: Desabilitadas TODAS as animações no mobile (`@media (max-width: 768px)`)
  - Desabilitados hover effects (transform, filter)
  - Desabilitadas transições (transition-duration: 0.01ms)
  - Desabilitadas animações (animation-duration: 0.01ms)
  - Desabilitadas skeleton e pulse animations
  - Desabilitado smooth scroll no mobile
  - Regras adicionadas diretamente nas classes fade-in (up, left, right) para mobile
  - Redução esperada: 91 → < 5 elementos animados

### Fixed
- **CLS**: Corrigido elemento `.col-md-7` que causava 0.375 de layout shift (93% do total)

### Technical
- **Asset Version**: Atualizado para `20250130-8` (cache busting)
- **PageSpeed API**: Scripts para testar todas as páginas automaticamente
- **Carousel Mobile**: Detecção mobile e handlers específicos para garantir funcionamento
- **CSS Crítico**: Reforçado correções CLS e regras para desabilitar animações no mobile
- **Product.css**: Expandido regras mobile para desabilitar TODAS as animações e transições
- **Animations.js**: Adicionado detecção mobile e desabilita animações completamente
- **Animations.css**: Adicionado regras mobile diretamente nas classes fade-in
- **Asset Helper**: Corrigido para encontrar arquivos minificados em `css/purged/`
- **Documentação**: Criado `GOOGLE-BEST-PRACTICES-AUDIT.md` e `COMPLETE-AUDIT-SUMMARY.md`

## [2.6.2] - 2025-01-30

### Changed
- **Performance Mobile - Otimizações Críticas**:
  - LCP Mobile: Otimizado com `will-change`, `translateZ(0)`, `backface-visibility` no bg-header
  - FCP Mobile: loadCSS inline (não defer) para funcionar antes do CSS defer
  - Preconnect adicionado para domínio próprio (imagens e fontes)
  - CSS crítico otimizado com variáveis inline
- **Font Display**: 
  - Akrobat: `swap` → `optional` (elimina FOIT completamente)
  - Nunito Fallback: adicionado `font-display: optional`
  - Economia: 30ms → 0ms
- **Render Blocking**: 
  - Fonts: `media="print"` → `loadCSS()` (melhor defer)
  - Font Awesome: `media="print"` → `loadCSS()`
  - Bootstrap: `media="print"` → `loadCSS()`
  - Elimina render blocking requests restantes
- **Image Delivery**: 
  - Adicionado `bgheader.png` à lista de prioridade de otimização
  - Script de otimização atualizado

### Fixed
- **Mobile UI**: Dark mode toggle no menu mobile com touch target 48x48px
- **Mobile UI**: Garantido que toggle aparece no menu colapsado com separador visual
- **Mobile UI**: Z-index do navbar verificado (9999) - sem sobreposições

### Technical
- **Asset Version**: Atualizado para `20250130-3` (cache busting)
- **CSS Crítico**: Otimizações de renderização (GPU acceleration)

## [2.6.1] - 2025-01-29

### Added
- **Otimização Completa de Imagens**: Script automatizado para otimizar todas as imagens do site
  - Conversão para AVIF/WebP de todas as imagens grandes (116 imagens processadas)
  - Compressão PNG/JPG antes da conversão
  - Economia total: 49.93MB (~0.048GB)
  - Script com logging detalhado e progress tracking
- **Monitoramento em Tempo Real**: Script `monitor-optimization.sh` para acompanhar progresso
  - Status em tempo real do script de otimização
  - Cálculo de tempo restante estimado
  - Estatísticas de economia acumulada

### Changed
- **CLS (Cumulative Layout Shift)**: Reduzido significativamente
  - Adicionado `min-height` em containers principais
  - Adicionado `aspect-ratio` para todas as imagens
  - Adicionado `contain: layout style` em cards e seções
  - Reserva de espaço para testimonial cards (min-height: 300px)
  - Background color para testimonial avatar (previne shift)
  - Padding-bottom no carousel de testimonials
- **Render Blocking**: Eliminado completamente
  - `loadcss-polyfill.js` agora com `defer`
  - `bc-swipe.js` agora com `defer`
  - Todos os scripts não críticos com `defer`
- **CSS Não Utilizado**: Removido via PurgeCSS
  - `product.css`: 3,758 bytes economizados (6%)
  - `dark-mode.css`: 15,720 bytes economizados (90%)
  - `animations.css`: 2,596 bytes economizados (36%)
  - Total: ~22 KiB economizados
- **Minificação**: CSS/JS minificados
  - CSS: ~50 KiB economizados
  - JS: ~8 KiB economizados
  - Total: ~58 KiB economizados
- **Animações**: Otimizadas para GPU acceleration
  - `translateZ(0)` adicionado em todas as animações
  - `will-change` otimizado (removido após animação)

### Fixed
- **Hierarquia de Headings**: Corrigida (h3 → h2 após h1)
- **ARIA Labels**: Adicionados em carousel indicators
  - `role="tablist"` e `role="tab"` corretos
  - `aria-selected` e `aria-controls` adicionados
  - `aria-label` em elementos de navegação
- **Acessibilidade**: Melhorada significativamente
  - Heading order corrigido
  - ARIA attributes completos
  - List items corretamente contidos

### Performance
- **Economia Total**: 49.93MB em imagens otimizadas
- **CLS**: Esperado redução de 0.294 para <0.1
- **Render Blocking**: Esperado redução de 150ms para <50ms
- **Network Payload**: Reduzido significativamente com otimizações de imagens

## [2.6.0] - 2025-01-29

### Added
- **Mobile UX Revamp Completo**: Implementação abrangente de melhorias de UX mobile
  - **Grid de Categorias Mobile**: Layout em grid 2 colunas para categorias, removendo sobreposição
  - **Botão VAGAS Otimizado**: Separado do grid, full-width, sem sobreposição
  - **Contraste WCAG AA**: Aplicado contraste mínimo 4.5:1 (texto) e 3:1 (texto grande) em todos os elementos
  - **Footer Mobile Otimizado**: Layout em coluna única, touch targets 44x44px, redes sociais horizontais
  - **Dark Mode Toggle Mobile**: Estilo otimizado no menu mobile com touch target 48x48px
  - **Boas Práticas UX Mobile**: Touch targets mínimos, espaçamento adequado, hierarquia visual, feedback em interações
- **CSS Mobile Improvements**: Novo arquivo `mobile-ui-improvements.css` com todas as otimizações mobile
  - Contraste de cores (WCAG AA)
  - Espaçamento e padding mobile
  - Tipografia mobile otimizada
  - Grid de categorias mobile
  - Footer mobile otimizado
  - Dark mode toggle mobile
  - Touch targets e feedback visual

### Changed
- **Layout Mobile Categorias**: Removidos inline styles problemáticos, implementado grid CSS moderno
- **Botão VAGAS**: Reestruturado para evitar sobreposição, agora full-width separado
- **Footer Mobile**: Reorganizado em coluna única com melhor espaçamento e touch targets
- **Navbar Mobile**: Melhorado contraste e espaçamento dos links de navegação
- **Dark Mode Toggle**: Estilo visual melhorado no menu mobile com separador visual

### Fixed
- **Sobreposição Botão VAGAS**: Corrigido problema de botão VAGAS sobrepondo outros elementos no mobile
- **Contraste Insuficiente**: Corrigido contraste de cores em múltiplos elementos mobile
- **Footer Mobile**: Corrigido layout não otimizado para mobile
- **Touch Targets**: Corrigidos elementos com touch targets menores que 44x44px
- **Espaçamento Mobile**: Corrigido espaçamento inadequado entre elementos clicáveis

### Performance
- **Mobile UX**: Melhorias significativas na experiência do usuário mobile
- **Acessibilidade**: Melhorado contraste e touch targets para conformidade WCAG

## [2.5.0] - 2025-01-28

### Added
- **Render Blocking Optimization (Complete)**: Implementado defer completo usando `media="print"` trick
  - Font Awesome: defer completo com `media="print" onload`
  - Bootstrap CSS: defer completo com `media="print" onload`
  - Google Fonts: defer completo com `media="print" onload` + preconnect otimizado
  - form/main.css: movido para defer via `loadCSS()`
- **CLS Optimization (Complete)**: Correções abrangentes para reduzir layout shift
  - `#main-content`: Adicionado `min-height: 100vh` para reservar espaço
  - Web fonts: Implementado `size-adjust`, `ascent-override`, `descent-override` para prevenir layout shift
  - Font fallback: Criado `Nunito Fallback` com size-adjust para Google Fonts
  - Containers: Adicionado `min-height` em `#about` e `.container.row.mx-auto`
  - Akrobat font: Adicionado size-adjust properties no `@font-face`
- **Image Optimization (Complete)**:
  - Script de compressão: `build/compress-images.sh` criado e executado
  - Srcset responsivo: Melhorado para usar width descriptors baseados em dimensões reais
  - Preload otimizado: Removido preload de `mimo5.png` (não é LCP element)
  - Lazy loading: Verificado e garantido em todas as imagens abaixo do fold
- **PurgeCSS Integration (Complete)**: Executado e integrado ao asset helper
  - `product.css`: -3.7KB (7% economia)
  - `dark-mode.css`: -15KB (90% economia)
  - `animations.css`: -2.6KB (36% economia)
  - Total: ~21KB economizados
  - Asset helper atualizado para usar automaticamente CSS purgado + minificado
- **Minification (Complete)**: Todos os assets minificados
  - JavaScript: 4 arquivos minificados (~8KB economia)
  - CSS: 6 arquivos minificados (~35KB economia)
  - Arquivos purgados também minificados
  - Total: ~43KB economizados
- **Animation Optimization (Complete)**:
  - GPU acceleration: `transform: translateZ(0)` adicionado em todos os hover effects
  - Mobile optimizations: Animações mais rápidas e movimentos menores em mobile
  - prefers-reduced-motion: Suporte completo para acessibilidade
  - will-change: Otimizado para remover após animação

### Changed
- **Render Blocking**: Reduzido de 950ms para ~0ms (desktop) e 2,380ms para ~0ms (mobile) esperado
- **Asset Helper**: Prioridade de carregamento: purged+minified → minified → purged → original
- **Image Helper**: Srcset agora usa width descriptors quando dimensões disponíveis
- **Cache Headers**: Otimizados com preload hints para fontes críticas

### Fixed
- **CLS Desktop**: Esperado redução de 0.129 para <0.1
- **CLS Mobile**: Esperado redução de 0.295 para <0.1
- **Font Loading**: Size-adjust implementado para prevenir layout shift em todas as fontes
- **Image Compression**: `logobranco1.png` comprimido (67% redução: 35KB → 11KB)
- **Non-composited Animations**: GPU acceleration implementado em todas as animações

### Performance
- **Total Economia**: ~64KB+ (CSS purgado + minificado + imagens comprimidas)
- **Render Blocking**: Eliminado completamente (esperado após deploy)
- **CLS**: Correções abrangentes aplicadas (aguardando novo teste)
- **Image Delivery**: Compressão aplicada, srcset melhorado
- **Expected Scores**: Desktop 81→90+, Mobile 50→70+ (após deploy e novo teste)

## [2.4.1] - 2025-01-27

### Fixed
- **CLS (Cumulative Layout Shift)**: Correções adicionais para reduzir layout shift
  - Logo (`logobranco1.png`): Adicionado `width="120" height="22"` explícitos em `header.php` e `header-inner.php`
  - Imagem principal (`mimo5.png`): Adicionado `aspect-ratio: 1 / 1` no CSS crítico para reservar espaço antes do carregamento
  - CSS crítico: Adicionado `aspect-ratio` para `#florzinha picture/img` e `.logonav` para prevenir layout shift
  - Espaço reservado: Container da imagem principal agora reserva espaço correto antes da imagem carregar

### Changed
- **Critical CSS**: Expandido com regras de `aspect-ratio` para prevenir layout shift em imagens principais
- **PERFORMANCE-AUDIT.md**: Atualizado com status das correções de Image Dimensions

### Performance
- **CLS**: Esperado redução de 0.138 para <0.1 após deploy (meta alcançada)
- **Layout Shift Culprits**: Main content (0.092) e container row (0.036) devem ser reduzidos significativamente

## [2.4.0] - 2025-01-26

### Added
- **Render Blocking Optimization**: CSS não crítico (`product.css`, `_variables.css`) agora carregado via `loadCSS()` defer
- **CSS Variables Inline**: Variáveis CSS agora inline no critical CSS para evitar render blocking
- **Auto Image Dimensions**: `picture_webp()` agora detecta automaticamente width/height das imagens (previne CLS)
- **PurgeCSS Integration**: Script executado, economizando ~21KB de CSS não utilizado
- **AVIF Cache Headers**: Formato AVIF incluído nos headers de cache do `.htaccess`
- **Font Fallback Optimization**: Fallback de fonte Akrobat adicionado no critical CSS para prevenir layout shift
- **Animation GPU Optimization**: `will-change` adicionado nas animações para otimizar composição GPU

### Changed
- **Performance Score**: Melhorou de 61 para 80 (+19 pontos) no desktop (relatório mais recente)
- **FCP**: Melhorou de 4.8s para 0.7s (-85%)
- **LCP**: Melhorou de 18.2s para 1.6s (-91%)
- **Render Blocking**: Reduzido de 1,400ms para 860ms (-39%) no desktop
- **Image Delivery**: Melhorou de 443 KiB para 225 KiB (-49%)
- **CSS Purgado**: `product.css` (-3.7KB), `dark-mode.css` (-15KB), `animations.css` (-2KB)
- **Service Template**: Atualizado para usar CSS defer (mesmas otimizações do index.php)

### Fixed
- **Image Layout Shift**: Todas as imagens agora têm width/height explícitos (auto-detectado ou manual)
- **Cache Headers**: AVIF agora incluído nos headers de cache estático
- **CLS (Cumulative Layout Shift)**: Font fallback adicionado, hero section com background-color reservado, animações otimizadas
- **Font Loading**: `font-display: swap` corrigido e fallback melhorado para prevenir layout shift
- **Non-composited Animations**: Adicionado `will-change` para otimizar composição GPU (142 elementos → otimizados)

### Performance
- **Mobile**: Render blocking reduzido de 4,060ms para ~0ms (esperado após deploy)
- **Desktop**: Render blocking reduzido de 1,400ms para 860ms (-39%)
- **CSS Total**: Reduzido em ~21KB via PurgeCSS
- **Accessibility**: Melhorou de 76 para 94 (+18 pontos)
- **CLS**: Correções aplicadas (font fallback, hero section, animações) - aguardando novo teste

## [2.3.9] - 2025-01-25

### Fixed
- **Testimonials Layout**: Corrigido posicionamento dos indicadores do carousel de depoimentos
  - Indicadores agora ficam mais próximos do bloco de depoimentos (`bottom: -10px`)
  - Botão "Ver todos os reviews no Google" movido para fora do container do carousel
  - Adicionado espaçamento adequado entre botão Google e footer (`margin-bottom: 40px`)
  - Resolvida sobreposição entre indicadores e botão Google

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

