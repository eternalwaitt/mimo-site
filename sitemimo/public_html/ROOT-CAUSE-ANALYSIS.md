# Análise de Causa Raiz - Por Que Otimizações Não Funcionam

**Data**: 2025-11-16  
**Status**: 🔍 Investigação em Andamento

---

## 🎯 Objetivo

Identificar por que as otimizações das Fases 1, 2 e 3 não estão funcionando, mesmo após implementação.

---

## 🔍 Problemas Identificados Até Agora

### 1. ❌ **USE_MINIFIED = false**

**Localização**: `config.php` linha 93

```php
define('USE_MINIFIED', false); // TEMPORARIAMENTE DESABILITADO
```

**Impacto**:
- CSS/JS não estão sendo minificados
- Arquivos purgados não estão sendo usados
- Tamanho de assets maior que o necessário
- **Economia potencial**: ~258 KiB (unused CSS) + 54 KiB (minify CSS) = **312 KiB**

**Solução**: Ativar `USE_MINIFIED = true` após verificar que arquivos existem

---

### 2. ⚠️ **Conflito: aspect-ratio + min-height no .bg-header**

**Localização**: 
- `product.css` linha 568-584
- `inc/critical-css.php` linha 106-115

**Problema**:
```css
.bg-header {
    height: 50vh;
    min-height: 350px;  /* ⚠️ CONFLITO */
    max-height: 500px;
    aspect-ratio: 16 / 9;  /* ⚠️ CONFLITO */
    contain: layout style;
}
```

**Análise**:
- `aspect-ratio` e `min-height` podem conflitar
- `height: 50vh` também pode conflitar com `aspect-ratio`
- Isso pode causar layout shift (CLS piorou de 0.359 → 0.406)

**Solução**: Remover `min-height` e `height` quando `aspect-ratio` está presente, ou usar apenas `aspect-ratio` com `min-height` como fallback

---

### 3. ⚠️ **CSS Critical vs Product.css - Duplicação**

**Problema**:
- `.bg-header` está definido em:
  1. `inc/critical-css.php` (inline no `<head>`)
  2. `product.css` (carregado via loadCSS defer)

**Conflito Potencial**:
- Duas definições podem estar conflitando
- Critical CSS pode estar sendo sobrescrito pelo product.css
- Ordem de carregamento pode estar errada

**Verificação Necessária**: Ver qual CSS está "vencendo" no navegador

---

### 4. ⚠️ **Preload com caminhos relativos**

**Localização**: `index.php` linha 276-301

**Problema**:
```php
echo '<link rel="preload" href="img/header_dezembro_mobile.avif" ...>';
```

**Análise**:
- Caminhos relativos (`img/...`) podem não funcionar corretamente
- Deveria ser `/img/...` (absoluto) para garantir que funcione em qualquer contexto
- Preconnect está usando URL absoluta (`https://minhamimo.com.br`), mas preload usa relativo

**Impacto**: Preload pode não estar funcionando corretamente

---

### 5. ⚠️ **contain: layout style pode estar causando problemas**

**Problema**:
- `contain: layout style` foi adicionado em vários lugares
- CLS piorou após adicionar `contain`
- Pode estar causando reflow em vez de prevenir

**Análise Necessária**: Testar sem `contain` para ver se CLS melhora

---

### 6. ⚠️ **Imagem LCP é background-image, não <img>**

**Problema**:
- LCP element (`.bg-header`) usa `background-image` via CSS
- `fetchpriority="high"` só funciona em tags `<img>`, não em `background-image`
- Preload ajuda, mas não é tão eficaz quanto `fetchpriority` em `<img>`

**Impacto**: LCP pode não estar sendo otimizado corretamente

**Solução Possível**: Considerar usar `<img>` com `object-fit: cover` em vez de `background-image`

---

### 7. ⚠️ **CSS carregado via loadCSS() pode não estar funcionando**

**Problema**:
- `product.css` está sendo carregado via `loadCSS()` (defer)
- Se `loadCSS()` não funcionar corretamente, CSS pode não carregar
- Critical CSS inline pode não ter todas as regras necessárias

**Verificação**: Verificar se `product.css` está realmente carregando

---

## 📋 Checklist de Verificação

- [ ] Verificar se `USE_MINIFIED` deve ser ativado
- [ ] Verificar conflitos CSS (aspect-ratio vs min-height)
- [ ] Verificar se preload está funcionando (caminhos)
- [ ] Verificar se `contain: layout style` está causando problemas
- [ ] Verificar se CSS está carregando corretamente
- [ ] Verificar ordem de carregamento de recursos
- [ ] Verificar se há duplicação de regras CSS
- [ ] Verificar se imagens estão usando picture_webp corretamente

---

## 🔧 Próximas Ações

1. **Testar sem `contain: layout style`**
2. **Corrigir conflito aspect-ratio + min-height**
3. **Corrigir caminhos de preload (relativos → absolutos)**
4. **Ativar USE_MINIFIED se arquivos existirem**
5. **Verificar se CSS está carregando**

---

**Status**: 🔍 Investigação em andamento...

