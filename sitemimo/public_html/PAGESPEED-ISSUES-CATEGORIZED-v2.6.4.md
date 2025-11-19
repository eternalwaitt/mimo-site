# PageSpeed Insights - Problemas Categorizados e Priorizados v2.6.4

**Data**: 2025-11-15  
**Baseado em**: Análise completa de 28 testes (9 páginas × mobile/desktop)

## 🔴 CRÍTICO - Core Web Vitals

### 1. CLS (Cumulative Layout Shift)
**Impacto**: 🔴 Crítico - Afeta diretamente Performance Score  
**Páginas Afetadas**: Todas (especialmente Cilios, Esmalteria, Esteticafacial)

**Problemas Identificados**:
- Imagens sem `width` e `height` explícitos (score 0.5 em várias páginas)
- Layout shift culprits (score 0 em várias páginas)
- Font loading causando reflow

**Ações Imediatas**:
1. Adicionar `width` e `height` em TODAS as imagens
2. Reforçar `contain: layout` e `min-height` em containers problemáticos
3. Otimizar font loading para prevenir FOIT/FOUT

### 2. LCP (Largest Contentful Paint)
**Impacto**: 🔴 Crítico - Afeta diretamente Performance Score  
**Páginas Afetadas**: Todas (especialmente mobile)

**Problemas Identificados**:
- LCP muito alto (4.5s-20s em mobile vs meta <2.5s)
- LCP discovery score 0 (preload não configurado)
- LCP breakdown score 0 (tempo de resposta do servidor)
- Image delivery score 0 (imagens não otimizadas)

**Ações Imediatas**:
1. Preload imagens LCP com `fetchpriority="high"`
2. Otimizar imagens LCP (AVIF/WebP, compressão)
3. Verificar se LCP images não têm lazy loading
4. Otimizar tempo de resposta do servidor

### 3. FCP (First Contentful Paint)
**Impacto**: 🔴 Crítico - Afeta diretamente Performance Score  
**Páginas Afetadas**: Homepage, Contato, Vagas (mobile)

**Problemas Identificados**:
- FCP alto em mobile (4.05s-4.80s vs meta <1.8s)
- Render blocking requests (score 0)
- Network dependency tree (score 0)

**Ações Imediatas**:
1. Expandir CSS crítico
2. Remover render-blocking CSS/JS
3. Otimizar ordem de carregamento de recursos

## 🟡 ALTA PRIORIDADE - Otimizações de Tamanho

### 4. Image Delivery
**Impacto**: 🟡 Alto - Afeta LCP e Network Payload  
**Status**: Score 0 em várias páginas

**Ações**:
1. Executar `build/optimize-remaining-images.sh`
2. Converter TODAS as imagens para AVIF/WebP
3. Adicionar `srcset` responsivo
4. Comprimir imagens grandes

### 5. Unminified CSS/JS
**Impacto**: 🟡 Médio - Reduz tamanho de download  
**Status**: Score 0.5 em várias páginas

**Ações**:
1. Executar `build/minify-css.sh`
2. Executar `build/minify-js.sh`
3. Verificar se `USE_MINIFIED=true` está ativo

### 6. Unused CSS/JS
**Impacto**: 🟡 Médio - Reduz tamanho de download  
**Status**: Score 0 em várias páginas

**Ações**:
1. Executar `build/purge-css.sh`
2. Analisar e remover JavaScript não utilizado
3. Verificar se PurgeCSS está sendo aplicado

### 7. Network Payloads
**Impacto**: 🟡 Médio - Afeta tempo de carregamento  
**Status**: Score 0.5 em várias páginas

**Ações**:
1. Reduzir tamanho total de recursos
2. Lazy load recursos não críticos
3. Comprimir todos os assets

## 🟢 MÉDIA PRIORIDADE - Otimizações Avançadas

### 8. Render Blocking
**Impacto**: 🟢 Médio - Afeta FCP  
**Status**: Score 0 em várias páginas

**Ações**:
1. Verificar se CSS não crítico está usando `loadCSS()`
2. Mover mais CSS para defer
3. Verificar se scripts estão com `defer` ou `async`

### 9. Network Dependency Tree
**Impacto**: 🟢 Médio - Afeta ordem de carregamento  
**Status**: Score 0 em várias páginas

**Ações**:
1. Otimizar ordem de carregamento de recursos
2. Reduzir dependências críticas
3. Preconnect para recursos externos

### 10. Font Display
**Impacto**: 🟢 Baixo - Afeta FCP levemente  
**Status**: Score 0-0.5

**Ações**:
1. Verificar se todas as fontes têm `font-display: swap` ou `optional`
2. Otimizar carregamento de fontes

### 11. Cache Lifetimes
**Impacto**: 🟢 Baixo - Afeta repeat visits  
**Status**: Score 0.5

**Ações**:
1. Verificar headers de cache
2. Garantir cache longo para assets estáticos

### 12. Document Request Latency
**Impacto**: 🟢 Baixo - Afeta tempo de resposta  
**Status**: Score 0.5

**Ações**:
1. Otimizar servidor/CDN
2. Reduzir latência de rede

## 📊 Priorização por Impacto Esperado

### Fase 1: Correções Críticas (Impacto Alto)
1. ✅ CLS: Adicionar width/height em imagens
2. ✅ LCP: Preload e otimizar imagens LCP
3. ✅ FCP: Remover render-blocking
4. ✅ Image Delivery: Converter para AVIF/WebP

**Impacto Esperado**: Performance Mobile 51-67 → 70-80

### Fase 2: Otimizações de Tamanho (Impacto Médio)
5. ✅ Minify CSS/JS
6. ✅ Unused CSS/JS
7. ✅ Network Payloads

**Impacto Esperado**: Performance Mobile 70-80 → 75-85

### Fase 3: Otimizações Avançadas (Impacto Baixo-Médio)
8. ✅ Network Dependency Tree
9. ✅ LCP Discovery
10. ✅ Font Display

**Impacto Esperado**: Performance Mobile 75-85 → 80-90

## 🎯 Metas Finais

### Mobile
- **Performance**: 51-67 → **80+**
- **FCP**: 4.05s → **<1.8s**
- **LCP**: 4.5-20s → **<2.5s**
- **CLS**: 0.4-0.9 → **<0.1**

### Desktop
- **Performance**: 54-95 → **95+** (manter ou melhorar)
- **FCP**: 0.3-1.1s → **<1.0s** (manter)
- **LCP**: 1.0-3.6s → **<2.5s**
- **CLS**: 0.004-0.92 → **<0.1**

