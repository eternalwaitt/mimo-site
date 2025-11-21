# Lighthouse Baseline - Diagnóstico Atual

**Data**: 2025-11-21  
**URL**: https://mimo-site.vercel.app/  
**Test Method**: PageSpeed Insights API (mobile strategy)  
**Build**: Production (Vercel)

---

## 📊 Métricas Atuais (Mobile)

### Performance Scores
- **Performance**: 82/100 ❌ (target: ≥95)
- **Accessibility**: 96/100 ✅
- **Best Practices**: 100/100 ✅
- **SEO**: 100/100 ✅

### Core Web Vitals
- **LCP (Largest Contentful Paint)**: 4.13s ❌ (target: <2.5s)
- **FCP (First Contentful Paint)**: 1.22s ✅ (target: <1.8s)
- **CLS (Cumulative Layout Shift)**: 0.000 ✅ (target: <0.1)
- **TBT (Total Blocking Time)**: 0.16s ✅ (target: <200ms)
- **TTI (Time to Interactive)**: 4.14s ⚠️

### Comparação com Baseline Anterior

| Métrica | Baseline (18:18) | Atual (18:55) | Mudança |
|---------|------------------|---------------|---------|
| Performance | 89/100 | 82/100 | **-7 pontos** ❌ |
| LCP | 2.52s | 4.13s | **+1.61s** ❌ |
| FCP | 0.92s | 1.22s | **+0.30s** ⚠️ |
| TBT | 0.18s | 0.16s | **-0.02s** ✅ |
| CLS | 0.000 | 0.000 | **0** ✅ |
| Unused JS | 62 KiB | 59 KiB | **-3 KiB** (marginal) |

**Conclusão**: LCP piorou significativamente (+1.61s), causando queda de 7 pontos no Performance score.

---

## 🎯 Elemento de LCP

**Elemento**: `hero-bg-mobile.webp` (imagem de fundo do hero)  
**Localização**: `components/sections/hero-manifesto.tsx`  
**Tamanho do arquivo**: 28 KB (WebP)  
**Implementação atual**:
- Usa `ImageWithFallback` (client component)
- `priority={true}`, `fetchPriority="high"`
- `sizes="(max-width: 768px) 100vw, 1920px"`
- Preload configurado em `app/layout.tsx` com media query

**Problema identificado**: LCP está 1.61s mais lento que o baseline anterior, indicando possível regressão na ordem de carregamento ou bloqueio por JavaScript.

---

## 🔍 Top 3 Oportunidades do Lighthouse

### 1. Reduce Unused JavaScript (CRÍTICO)
- **Economia estimada**: 59 KiB
- **Score**: 0/100
- **Impacto**: Alto - principal causa da baixa performance
- **Status**: Não resolvido

### 2. LCP Discovery (implícito)
- **Problema**: LCP element não está sendo descoberto/priorizado corretamente
- **Evidência**: LCP piorou de 2.5s para 4.1s após mudanças recentes
- **Status**: Regressão identificada

### 3. Render Blocking Resources (implícito)
- **Problema**: JavaScript pode estar bloqueando renderização do LCP
- **Evidência**: 102 kB de JS compartilhado + componentes client no bundle inicial
- **Status**: Requer investigação

---

## 📦 Análise do JavaScript Não Utilizado (~60 KiB)

### Bundle Atual (First Load JS)

```
Route (app)                                 Size  First Load JS
┌ ○ /                                    2.72 kB         125 kB
+ First Load JS shared by all             102 kB
  ├ chunks/255-cf2e1d3491ac955b.js       45.8 kB
  ├ chunks/4bd1b696-c023c6e3521b1417.js  54.2 kB
  └ other shared chunks (total)          1.92 kB
```

**Total First Load JS**: 125 kB (home page)  
**Shared JS**: 102 kB (compartilhado entre todas as páginas)

### Componentes Client no Bundle Inicial

Componentes marcados com `'use client'` que são carregados na home:

1. **Header** (`components/layout/header.tsx`)
   - **Tamanho estimado**: ~15-20 kB
   - **Uso**: Acima da dobra (fixo no topo)
   - **Status**: Crítico, mas pode ser otimizado
   - **Problemas**:
     - Usa `useState`, `useEffect` para scroll detection
     - Menu mobile com estado
     - Analytics tracking (trackCTAClick, trackNavigationClick)

2. **ImageWithFallback** (`components/ui/image-with-fallback.tsx`)
   - **Tamanho estimado**: ~5-8 kB
   - **Uso**: Hero image (LCP element)
   - **Status**: **CRÍTICO** - usado no LCP
   - **Problemas**:
     - Client component desnecessário para LCP
     - Adiciona overhead de JS para fallback que raramente é usado
     - Pode estar bloqueando renderização do LCP

3. **AnalyticsProvider** (`components/analytics-provider.tsx`)
   - **Tamanho estimado**: ~3-5 kB
   - **Uso**: Global (layout)
   - **Status**: Pode ser otimizado
   - **Problemas**: Carregado mesmo quando GA não está configurado

4. **AnalyticsPageTracker** (`components/analytics-page-tracker.tsx`)
   - **Tamanho estimado**: ~2-3 kB
   - **Uso**: Lazy loaded (dynamic import)
   - **Status**: OK (já otimizado)

5. **ErrorBoundary** (`components/error-boundary.tsx`)
   - **Tamanho estimado**: ~5-8 kB
   - **Uso**: Wrapper de seções (dynamic import)
   - **Status**: OK (já otimizado)

6. **Button** (`components/ui/button.tsx`)
   - **Tamanho estimado**: ~2-3 kB
   - **Uso**: CTAs no hero
   - **Status**: Crítico (acima da dobra)

### Análise dos Chunks

#### Chunk 255 (45.8 kB)
- Provavelmente: React runtime + Next.js runtime + utilitários compartilhados
- **Status**: Necessário, mas pode ser otimizado com tree-shaking

#### Chunk 4bd1b696 (54.2 kB)
- Provavelmente: Componentes client + dependências (clsx, tailwind-merge, etc)
- **Status**: Pode conter código não utilizado

### Classificação dos Módulos

| Módulo | Tamanho Est. | Uso | Classificação | Ação Recomendada |
|--------|--------------|-----|---------------|------------------|
| Header | 15-20 kB | Acima da dobra | Crítico | Otimizar (server component parcial) |
| ImageWithFallback | 5-8 kB | LCP element | **CRÍTICO** | **Remover do LCP** (usar Next/Image direto) |
| AnalyticsProvider | 3-5 kB | Global | Pode otimizar | Conditional loading |
| Button | 2-3 kB | Acima da dobra | Crítico | Manter (necessário) |
| React/Next runtime | ~45 kB | Base | Necessário | Tree-shaking |
| Utilitários (clsx, etc) | ~10-15 kB | Compartilhado | Pode otimizar | Verificar uso real |

---

## 🔴 Por Que o LCP Piorou?

### Comparação: Baseline vs Atual

**Baseline (18:18)**:
- LCP: 2.52s
- Performance: 89/100
- FCP: 0.92s

**Atual (18:55)**:
- LCP: 4.13s (+1.61s)
- Performance: 82/100 (-7 pontos)
- FCP: 1.22s (+0.30s)

### Mudanças Recentes que Podem Ter Causado Regressão

1. **Preload com Media Queries**
   - Adicionado preload condicional para mobile/desktop
   - **Hipótese**: Browser pode estar esperando resolver media query antes de carregar
   - **Evidência**: LCP piorou após essa mudança

2. **ImageWithFallback no LCP**
   - Hero image usa `ImageWithFallback` (client component)
   - **Hipótese**: JS precisa hidratar antes de imagem renderizar
   - **Evidência**: Client component no LCP element é anti-pattern

3. **Header como Client Component**
   - Header carregado no bundle inicial
   - **Hipótese**: Pode estar competindo por recursos com LCP
   - **Evidência**: 15-20 kB de JS carregado antes do LCP

### Diagnóstico do LCP

**O que está atrasando o LCP agora?**

1. **JavaScript bloqueando renderização** (mais provável)
   - `ImageWithFallback` é client component
   - Requer hidratação antes de imagem aparecer
   - Header também carrega JS no bundle inicial

2. **Ordem de carregamento de recursos**
   - Preload pode não estar funcionando corretamente
   - Media queries podem estar causando delay

3. **Tamanho da imagem**
   - 28 KB é razoável, mas pode ser otimizado
   - Não é o principal problema (LCP piorou sem mudar imagem)

**Conclusão**: O problema principal é **JavaScript bloqueando renderização do LCP**. `ImageWithFallback` sendo client component no LCP element é o maior culpado.

---

## 📋 Próximos Passos

Ver `docs/performance-plan.md` para plano detalhado de otimização.
