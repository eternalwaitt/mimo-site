# Changelog

Todas as mudanças notáveis neste projeto serão documentadas neste arquivo.

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/),
e este projeto adere ao [Semantic Versioning](https://semver.org/lang/pt-BR/).

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

