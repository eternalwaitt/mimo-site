# CLS Diagnosis - Layout Shift Culprits
**Data**: 2025-11-16
**Fonte**: PageSpeed Insights Production Report (bm7cuzovxw)

## Problema Crítico
- **CLS Mobile**: 0.846 (meta: <0.1) 🔴
- **CLS Desktop**: 0.177 (meta: <0.1) ⚠️

## Elementos Identificados (PageSpeed Insights)

### Mobile - Layout Shift Culprits
1. **Imagens sem dimensões explícitas**
   - Todas as imagens que não têm `width` e `height` atributos
   - Imagens que usam apenas CSS `aspect-ratio` sem dimensões HTML

2. **Containers dinâmicos**
   - Testimonials carousel (muda de tamanho durante carregamento)
   - Service cards (conteúdo aparece progressivamente)
   - Hero section (imagem de fundo carrega)

3. **Conflitos CSS**
   - `.sessoes.container`: `height` + `aspect-ratio` conflitando
   - `.bg-header` mobile: `height` + `aspect-ratio` conflitando

### Desktop - Layout Shift Culprits
1. **Forced reflow identificado**
   - JavaScript causando reflow durante scroll
   - Animações não compositadas

2. **Containers sem min-height**
   - Containers que mudam de tamanho quando conteúdo carrega

## Priorização de Correções

### Prioridade 1 (Crítico - CLS Mobile)
1. ✅ Adicionar `width` e `height` explícitos em todas as imagens
2. ✅ Remover conflitos `height` + `aspect-ratio`
3. ✅ Adicionar `min-height` em containers dinâmicos

### Prioridade 2 (Importante - CLS Desktop)
1. ✅ Otimizar animações para GPU-accelerated
2. ✅ Reduzir forced reflows no JavaScript

### Prioridade 3 (Manutenção)
1. ✅ Validar que todas as imagens têm dimensões
2. ✅ Testar CLS após cada correção

