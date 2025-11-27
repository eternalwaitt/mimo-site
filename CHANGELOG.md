# Changelog

Todas as mudanças notáveis neste projeto serão documentadas neste arquivo.

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/),
e este projeto adere ao [Semantic Versioning](https://semver.org/lang/pt-BR/).

## [1.5.2] - 2025-01-30

### Changed
- Migração de Google Analytics 4 e Microsoft Clarity para Plausible Analytics
  - Motivos: melhor performance (script mais leve, não bloqueia renderização), privacidade (não requer banner de consentimento LGPD), simplicidade
  - Variável de ambiente atualizada: `NEXT_PUBLIC_PLAUSIBLE_DOMAIN` (substitui `NEXT_PUBLIC_GA_MEASUREMENT_ID` e `NEXT_PUBLIC_CLARITY_PROJECT_ID`)
  - API de eventos mantida compatível (`trackEvent`, `trackCTAClick`, etc)
  - Script carregado com `strategy="lazyOnload"` para não bloquear FCP/LCP
  - Documentação atualizada em `docs/guides/ANALYTICS-SETUP.md`
  - Removido `docs/guides/GA4-TROUBLESHOOTING.md` (não mais necessário)

### Fixed
- Problemas de contraste WCAG em botões e textos - corrigidos todos os elementos que não atendiam ao mínimo de 4.5:1 (WCAG AA)
  - Filtros da galeria: `text-mimo-blue` → `text-mimo-brown` em fundo bege claro
  - Links "Coming Soon": removida opacidade de 50%, aumentada para 70% e ícone mudado para marrom
  - Texto "Em breve": `text-mimo-gold` → `text-mimo-brown` (contraste de ~1.8:1 para ~8.59:1)
  - Badges dourados: `bg-mimo-gold text-mimo-brown` → `bg-mimo-brown text-white` (contraste de ~3.8:1 para ~8.59:1)
  - Bullet points dourados: `text-mimo-gold` → `text-mimo-brown` em listas
  - Checkmarks dourados: `bg-mimo-gold/20 text-mimo-gold` → `bg-mimo-brown/10 text-mimo-brown`

### Changed
- `app/galeria/page.tsx` - Filtros agora usam `text-mimo-brown` para melhor contraste
- `components/layout/header.tsx` - Links "Coming Soon" com opacidade 70% e ícone marrom
- `components/layout/header-client.tsx` - Texto "Em breve" mudado para marrom
- `app/trabalhe-aqui/[slug]/page.tsx` - Badge e bullet points mudados para marrom
- `components/ui/job-card.tsx` - Badge e bullet points mudados para marrom, hover do link ajustado
- `app/servicos/[slug]/service-content.tsx` - Checkmarks mudados para marrom com fundo marrom claro

### Technical
- Todos os elementos de texto agora atendem WCAG 2.1 AA (mínimo 4.5:1 para texto normal)
- Contraste melhorado de ~1.8-4.2:1 para ~8.59:1 em todos os elementos corrigidos
- Build e lint passaram sem erros

## [1.5.1] - 2025-01-30

### Fixed
- Menu mobile não abria (timeout ao clicar) - refatorado HeaderClient para injetar controles via DOM
- Imagens quebradas sem fallback adequado - melhorado fallback para usar placeholder.svg
- Texto muito pequeno em mobile (< 14px) - adicionado CSS para garantir mínimo de 14px
- Área de toque pequena em botões mobile (< 44x44px) - adicionado CSS para garantir mínimo de 44x44px
- Favicon 404 - criado favicon.ico e adicionado links no layout

### Changed
- `components/layout/header-client.tsx` - Refatorado para injetar controles mobile via DOM (sem portal)
- `components/ui/image-with-fallback.tsx` - Fallback melhorado usando placeholder.svg
- `app/globals.css` - Adicionadas regras de acessibilidade mobile (texto min 14px, touch targets min 44x44px)
- `app/layout.tsx` - Favicon adicionado e preloads otimizados com media queries
- `docs/UX-TEST-REPORT.md` - Relatório completo de testes e correções

### Technical
- Menu mobile agora funciona corretamente com z-index adequado e event listeners corretos
- Acessibilidade melhorada conforme WCAG 2.1 (tamanho de texto e área de toque)
- Preloads otimizados com media queries para mobile/desktop separados

## [1.5.0] - 2025-01-29

### Added
- Google Analytics 4 (GA4) integrado com tracking automático de pageviews
- Microsoft Clarity para heatmaps e session recordings
- **Nota**: Migrado para Plausible Analytics na versão 1.5.2 (ver changelog da 1.5.2)
- Sistema de analytics com eventos customizados:
  - `cta_click` - tracking de cliques em CTAs (WhatsApp, agendamento)
  - `service_view` - tracking de visualização de páginas de serviço
  - `navigation_click` - tracking de cliques em navegação
  - `scroll_depth` - tracking automático de profundidade de scroll (25%, 50%, 75%, 100%)
  - `time_on_page` - tracking automático de tempo na página (30s, 1min, 2min+)
- Utilitários de analytics type-safe em `lib/analytics.ts`
- `AnalyticsProvider` component para inicialização do GA4
- `AnalyticsPageTracker` component para tracking de scroll/time em páginas
- Documentação completa em `docs/ANALYTICS-SETUP.md`
- Guia de troubleshooting em `docs/GA4-TROUBLESHOOTING.md`

### Changed
- Layout atualizado para incluir scripts do Google Analytics e Microsoft Clarity
- Componentes atualizados com tracking de eventos (Header, CTA, Service pages)

### Technical
- API de analytics projetada para ser provider-agnostic (facilita migração futura para Plausible)
- Tracking de pageviews atualiza automaticamente em mudanças de rota (Next.js App Router)

## [1.4.0] - 2025-01-20

### Added
- Menu mobile completo com drawer animado no Header
- ErrorBoundary component para capturar erros em componentes filhos
- Skeleton loaders para imagens durante carregamento
- Cache em memória para thumbnails de reels (evita múltiplas chamadas durante SSR)
- Rate limiting na API route de thumbnails do Instagram
- Metadata dinâmica rica para páginas de serviço (Open Graph, Twitter Cards, keywords)
- Structured data (Service schema.org) para páginas de serviço
- Constantes de UI centralizadas (`lib/ui-constants.ts`) para aspect ratios, delays de animação, etc
- Helper function `getCelebrityImage` para lógica de fallback de imagens tipada

### Changed
- `CelebrityCard` otimizado para usar `fill` ao invés de dimensões fixas (melhor responsividade)
- `ServicesGrid` com `content-visibility: auto` para melhor performance
- `ServiceCard` com max-height aumentado (500px) para não cortar conteúdo no hover
- `constants.ts` dividido em módulos menores:
  - `lib/constants/contact.ts` - informações de contato e empresa
  - `lib/constants/home.ts` - copy da home
  - `lib/constants/services.ts` - serviços
  - `lib/constants/celebrities.ts` - celebridades/influencers
  - `lib/constants/jobs.ts` - vagas e benefícios
  - `lib/constants/index.ts` - re-exporta tudo (mantém compatibilidade)
- Lógica de fallback de imagens extraída para função helper reutilizável
- API route de thumbnails com cache headers (24h) e rate limiting (10 req/min)
- Animações usando constantes centralizadas ao invés de magic numbers

### Fixed
- Imagem de Esmalteria agora preenche container corretamente (usando `fill`)
- ServiceCard não corta mais conteúdo no hover (max-h aumentado)
- Links externos auditados - todos já tinham `rel="noopener noreferrer"` corretamente
- Acessibilidade melhorada com aria-labels em ServiceCard

### Performance
- Otimização de imagens com skeleton loaders (melhor UX durante carregamento)
- Cache em memória reduz chamadas duplicadas durante SSR
- Content-visibility em seções abaixo do fold para melhor performance de scroll
- Documentado code-split automático do framer-motion pelo Next.js

## [1.3.0] - 2025-01-19

### Added
- Suporte para thumbnails de posts do Instagram (além de reels)
- Thumbnails locais para influencers:
  - Bruna Huli (DBACXKPOvd0.webp)
  - Karol Queiroz (C9h0HUXxDth.webp)
  - Let Vasconcelos (DElH799vrV5.webp)
- Sistema de cache local de thumbnails (`lib/reel-thumbnail-cache.ts`)
- Script de download de thumbnails (`scripts/download-reel-thumbnails.js`)

### Changed
- `extractReelId` agora suporta tanto `/reel/` quanto `/p/` (posts)
- Lista de celebridades atualizada: removidos clientes, mantidos apenas influencers
- `CelebrityCard` prioriza cache local antes de tentar API oEmbed

### Fixed
- Thumbnail da Let Vasconcelos agora funciona corretamente (suporte a posts)

## [1.2.0] - 2025-01-29

### Added
- Documentação completa de otimizações de performance (`docs/PERFORMANCE-OPTIMIZATION-RESULTS.md`)
- Resource hints (dns-prefetch, preconnect) para recursos externos
- Cache headers otimizados para assets estáticos

### Changed
- Otimizações completas de performance implementadas:
  - CLS: Altura fixa (`height: '100vh'`) em containers críticos
  - LCP: Preload melhorado com `type="image/webp"` e `fetchPriority="high"`
  - Cache: Headers configurados para 1 ano em imagens e fontes
  - DNS prefetch para WhatsApp, Instagram, Facebook
- Componentes otimizados:
  - `components/sections/hero-manifesto.tsx` - Container com altura fixa
  - `components/ui/image-with-fallback.tsx` - Suporte para aspectRatio
  - `components/sections/cta-agendamento.tsx` - Background fixo
  - `app/layout.tsx` - Resource hints e preload otimizado
  - `next.config.ts` - Cache headers e swcMinify

### Fixed
- Layout shift durante carregamento de imagens (CLS)
- Performance de carregamento inicial (LCP)
- Cache de recursos estáticos

## [1.1.0] - 2025-01-29

### Added
- Script de teste de performance local (`scripts/pagespeed-local.js`)
- Documentação de análise de performance local (`docs/PERFORMANCE-LOCAL-ANALYSIS.md`)
- Relatório final de testes (`docs/FINAL-TEST-REPORT.md`)

### Changed
- Otimizações de performance implementadas:
  - CLS (Cumulative Layout Shift) reduzido de 0.725 para 0.000 na Home page
  - Backgrounds fixos em containers de imagens para evitar layout shift
  - Dimensões mínimas em containers críticos
  - `fetchPriority="high"` no preload da hero image
- Componentes otimizados:
  - `components/sections/hero-manifesto.tsx`
  - `components/sections/cta-agendamento.tsx`
  - `components/ui/service-card.tsx`
  - `components/ui/celebrity-card.tsx`
  - `app/layout.tsx`

### Fixed
- Layout shift durante carregamento de imagens
- CLS crítico na Home page (0.725 → 0.000)

## [1.0.0] - 2025-01-29

### Added
- Sistema de versionamento SemVer implementado
- Documentação de versionamento (VERSIONING.md)
- Script de type-check no package.json
- Constantes de versão em lib/version.ts
- Meta tag generator no layout com versão
- Documentação completa em `docs/`:
  - Revisão de código e qualidade
  - Estratégia de imagens (100% local)
  - Pesquisa UX/UI mobile
  - Comparação de frameworks e tecnologias
- Script de PageSpeed Insights API (`scripts/pagespeed-test.js`)
- Script de otimização de imagens (`scripts/optimize-images.js`)
- Comentários JSDoc padronizados em todos os componentes
- Sistema de tracking de progresso

### Changed
- Versão inicial atualizada de 0.1.0 para 1.0.0
- Comentários JSDoc melhorados e padronizados
- Type annotations melhoradas (retorno explícito em `cn()`)

### Fixed
- Cleanup de event listeners no Header (useEffect)

[1.2.0]: https://github.com/eternalwaitt/mimo-site/releases/tag/v1.2.0
[1.1.0]: https://github.com/eternalwaitt/mimo-site/releases/tag/v1.1.0
[1.0.0]: https://github.com/eternalwaitt/mimo-site/releases/tag/v1.0.0

