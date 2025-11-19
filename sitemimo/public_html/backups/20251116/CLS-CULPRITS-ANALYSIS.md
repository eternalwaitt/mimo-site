# Análise de Culpados de CLS
**Data**: 2025-11-16 21:40:00

## Resultados PageSpeed Insights

### Homepage (/) - Mobile
- **CLS**: 0.774 🔴 (meta: <0.1)
- **Performance**: 64

### Homepage (/) - Desktop
- **CLS**: 0.180 🔴 (meta: <0.1)
- **Performance**: 89

## Problemas Identificados

### 1. Imagens sem dimensões explícitas
**Status**: ✅ Já corrigido parcialmente
- `picture_webp()` já detecta dimensões automaticamente
- Imagens hero já têm width/height explícitos (1920x1080, 750x422)
- Imagens de categoria já têm width/height (150x150)
- Imagens de serviço já têm width/height (500x400, 600x400)

**Ação necessária**: Verificar se há imagens em outras páginas sem dimensões

### 2. Conflitos aspect-ratio + height
**Status**: ✅ Parcialmente corrigido
- `.sessoes.container`: height removido, apenas aspect-ratio + min-height ✅
- `.bg-header` mobile: height removido, apenas aspect-ratio + min-height ✅
- Verificar outros elementos com aspect-ratio

**Ação necessária**: Verificar se há mais conflitos

### 3. Containers dinâmicos sem min-height
**Status**: ⚠️ Parcialmente implementado
- `#main-content`: min-height: 100vh ✅
- `.testimonials-carousel`: min-height: 500px ✅
- `.testimonial-content`: min-height: 400px ✅
- `.service-card`: min-height: 300px ✅
- `.sessoes.container`: min-height: 300px ✅

**Ação necessária**: Verificar se há containers que ainda não têm min-height

### 4. Fontes sem font-display: swap
**Status**: ❓ Não verificado
- Verificar se fontes usam `font-display: swap` para prevenir layout shift durante carregamento

### 5. Conteúdo inserido dinamicamente
**Status**: ❓ Não verificado
- Verificar se há conteúdo inserido via JavaScript que pode causar layout shift
- Testimonials carregados via API podem causar shift

## Próximos Passos

1. ✅ Verificar todas as imagens têm width/height
2. ✅ Remover conflitos height + aspect-ratio
3. ✅ Adicionar min-height em containers dinâmicos
4. ⏳ Verificar font-display: swap
5. ⏳ Verificar conteúdo dinâmico (testimonials, etc.)

## Referências
- Plano: `css-layout-fixes.plan.md`
- Resultados: `pagespeed-results/api-results-20251116-212541.md`

