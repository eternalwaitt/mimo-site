# Investigação de Problemas - Teste Local

**Data**: 2025-11-15 23:07  
**Teste**: Lighthouse Mobile Local  
**Performance**: 66 (meta: 90+)  
**CLS**: 0.383 (meta: <0.1) ❌  
**LCP**: 4.20s (meta: <2.5s) ❌  
**FCP**: 1.99s (meta: <1.8s) ❌

---

## 🔴 Problemas Críticos Identificados

### 1. CLS Alto (0.383) - Conflitos CSS

#### Problema 1.1: `.bg-header` mobile com height no critical-css
**Localização**: `inc/critical-css.php` linha 180  
**Problema**: Ainda tem `height: 40vh` mesmo com `aspect-ratio: 16/9`  
**Conflito**: `height` + `aspect-ratio` + `min-height` causando layout shift

**Correção**:
```css
/* REMOVER height quando aspect-ratio está presente */
/* height: 40vh; - REMOVIDO: conflito com aspect-ratio */
```

#### Problema 1.2: `.sessoes.container` com height + aspect-ratio
**Localização**: `product.css` linha 880-900  
**Problema**: Tem `height: 300px` E `aspect-ratio: 5/4` ao mesmo tempo  
**Conflito**: `height` fixo + `aspect-ratio` causando layout shift

**Correção**:
```css
.sessoes.container {
    /* REMOVER height quando aspect-ratio está presente */
    /* height: 300px; - REMOVIDO: conflito com aspect-ratio */
    min-height: 300px;
    aspect-ratio: 5 / 4;
    contain: layout; /* Remover 'style' se estiver causando reflow */
}
```

#### Problema 1.3: `.sessoes.container .content` com contain: layout style
**Localização**: `product.css` linha 952-965  
**Problema**: `contain: layout style` pode estar causando reflow  
**Correção**: Testar apenas `contain: layout`

#### Problema 1.4: Carousel de testimonials sem dimensões fixas
**Localização**: `index.php` linha 619-1000  
**Problema**: Carousel pode estar mudando de tamanho durante carregamento  
**Correção**: Adicionar `min-height` fixo e `contain: layout` no container do carousel

---

### 2. LCP Alto (4.20s) - Background Image

#### Problema 2.1: LCP é background-image, não <img>
**Localização**: `.bg-header` usa `background-image`  
**Problema**: `fetchpriority="high"` em `<img>` não funciona para `background-image`  
**Impacto**: Preload pode não estar priorizando corretamente

**Correção Opcional** (mudança maior):
- Considerar usar `<img>` com `object-fit: cover` em vez de `background-image`
- Isso permitiria `fetchpriority="high"` funcionar corretamente

**Correção Imediata**:
- Garantir que preload está configurado corretamente
- Verificar se imagem LCP está sendo carregada com prioridade máxima

#### Problema 2.2: LCP Breakdown (tempo de resposta)
**Problema**: Tempo de resposta do servidor pode estar alto  
**Correção**: Verificar cache headers e otimização do servidor

---

### 3. FCP Alto (1.99s) - Render Blocking

#### Problema 3.1: CSS não minificado detectado
**Localização**: Lighthouse detecta CSS não minificado  
**Problema**: CSS inline (critical-css) não pode ser minificado da mesma forma  
**Impacto**: Pequeno, mas pode melhorar

**Correção**:
- Minificar CSS inline crítico manualmente (remover espaços desnecessários)
- Verificar se outros CSS estão sendo minificados corretamente

---

## 🟡 Problemas Menores

### 4. Imagens sem dimensões explícitas
**Status**: Maioria já tem width/height via `picture_webp()`  
**Verificar**: Imagens de testimonials (Google Reviews) podem não ter dimensões

### 5. Font Loading
**Status**: Já otimizado com `font-display: optional/swap`  
**Verificar**: Se ainda está causando algum layout shift

---

## 📋 Plano de Correção Imediata

### Prioridade 1: Fix CLS (Crítico)
1. ✅ Remover `height: 40vh` de `.bg-header` mobile no critical-css.php
2. ✅ Remover `height: 300px` de `.sessoes.container` quando aspect-ratio presente
3. ✅ Mudar `contain: layout style` para `contain: layout` em alguns elementos
4. ✅ Adicionar `min-height` fixo no carousel de testimonials

### Prioridade 2: Melhorar LCP
1. Verificar se preload está funcionando corretamente
2. Considerar otimizar ainda mais imagens LCP
3. Verificar tempo de resposta do servidor

### Prioridade 3: Melhorar FCP
1. Minificar CSS crítico inline
2. Verificar se todos os CSS não críticos estão sendo deferidos

---

## 🎯 Resultados Esperados

Após correções:
- **CLS**: 0.383 → <0.1 (meta)
- **LCP**: 4.20s → <2.5s (meta)
- **FCP**: 1.99s → <1.8s (meta)
- **Performance**: 66 → 80+ (melhoria significativa)

