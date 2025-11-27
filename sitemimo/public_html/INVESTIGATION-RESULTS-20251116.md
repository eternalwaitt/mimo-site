# Investigação PageSpeed Insights - 2025-11-16

## Problemas Identificados

### 1. 🔴 Conflito aspect-ratio + height (11 elementos)
**Impacto**: CLS alto (0.760 mobile, 0.180 desktop)

**Elementos afetados**:
- 10 imagens de categorias (testimonials avatars) com `aspect-ratio: 1 / 1` mas `height: 74px` definido
- 1 imagem de categoria (service card) com `aspect-ratio: 1 / 1` mas `height: 313.875px` definido

**Causa**: CSS define `height` fixo mas também `aspect-ratio`, causando conflito e layout shift quando a imagem carrega.

**Localização**:
- `.img-cat` (linha 2226): `height: 100px` + `aspect-ratio: 1 / 1` (mobile)
- `.testimonial-avatar img` (linha 184): `height: 80px !important` + `aspect-ratio: 1 / 1`
- Imagens de categorias com `aspect-ratio` inline mas `height` calculado pelo browser

**Solução**: Remover `height` fixo quando `aspect-ratio` está presente, usar apenas `min-height` como fallback.

---

### 2. ⚠️ LCP Image usando JPG ao invés de AVIF
**Impacto**: LCP alto (3.98s mobile, 2.56s desktop)

**Problema**: Browser está carregando `bgheader.jpg` (247KB) ao invés de `bgheader.avif` (151KB) - **39% maior**.

**Causa possível**:
- Browser não suporta AVIF (improvável - Chrome/Firefox suportam)
- Problema no código `<picture>` - fallback JPG sendo usado
- Preload está correto, mas `<picture>` pode ter problema

**Verificação**:
- ✅ Arquivo `bgheader.avif` existe (151KB)
- ✅ Arquivo `bgheader.webp` existe (135KB)
- ✅ Preload está configurado corretamente
- ⚠️ `<picture>` tag pode ter problema na ordem dos `<source>`

**Solução**: Verificar se `<picture>` está funcionando corretamente e garantir que AVIF é priorizado.

---

### 3. ⚠️ CSS ainda detectado como render-blocking
**Impacto**: Performance regrediu (89 → 75 desktop)

**Problema**: Apesar de usar `preload` + `onload`, PageSpeed ainda detecta CSS como render-blocking.

**Causa possível**:
- Polyfill `onload` pode não estar funcionando em todos os browsers
- Timing de carregamento pode estar causando FOUC
- PageSpeed pode estar testando antes do `onload` executar

**Verificação local**:
- ✅ CSS está marcado como `non-blocking` pelo browser
- ✅ Preload + onload está funcionando
- ⚠️ PageSpeed pode estar testando em condições diferentes

**Solução**: Considerar usar `loadCSS()` para todos os CSS não críticos, não apenas alguns.

---

### 4. ⚠️ Regressão Performance Desktop (89 → 75)
**Impacto**: -14 pontos de performance

**Possíveis causas**:
1. Mudanças não deployadas em produção
2. Variação natural do PageSpeed Insights (±5-10 pontos)
3. Mudanças no carregamento de CSS (preload + onload pode ter impacto negativo em alguns casos)
4. Outros fatores externos (servidor, rede, etc.)

**Ação**: Verificar se mudanças foram deployadas e comparar com versão anterior.

---

## Correções Necessárias

### Prioridade 1 (Crítico - CLS)
1. **Remover `height` de `.img-cat`** quando `aspect-ratio` está presente
2. **Remover `height` de `.testimonial-avatar img`** quando `aspect-ratio` está presente
3. **Garantir que imagens com `aspect-ratio` não tenham `height` fixo**

### Prioridade 2 (Alto - LCP)
4. **Verificar `<picture>` tag** para garantir que AVIF é carregado
5. **Adicionar fallback mais robusto** para browsers que não suportam AVIF

### Prioridade 3 (Médio - Performance)
6. **Investigar regressão desktop** - comparar com versão anterior
7. **Considerar usar `loadCSS()` para todos os CSS não críticos**

---

## Próximos Passos

1. ✅ Corrigir conflitos aspect-ratio + height
2. ✅ Verificar e corrigir LCP image
3. ⏳ Investigar regressão desktop
4. ⏳ Testar em produção após deploy

