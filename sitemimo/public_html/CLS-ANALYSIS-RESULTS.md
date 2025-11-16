# Análise de CLS - Resultados Identificados

**Data**: 2025-11-16  
**Método**: Lighthouse JSON Analysis

---

## 🔍 Elementos Causando CLS

### CLS Total: 0.383 (Meta: <0.1) ❌

---

## 📊 Layout Shifts Identificados

### Shift #1: `<main id="main-content">`
- **Score**: 0.358 (93% do CLS total!)
- **Seletor**: `body > main#main-content`
- **Impacto**: CRÍTICO - Este é o principal culpado

### Shift #2: `<main id="main-content">`
- **Score**: 0.024 (6% do CLS total)
- **Seletor**: `body > main#main-content`
- **Impacto**: MÉDIO

### Shift #3: `body > nav.navbar > div.container`
- **Score**: 0.0005 (<1% do CLS total)
- **Seletor**: `body > nav.navbar > div.container`
- **Impacto**: BAIXO

---

## 🎯 Análise

### Problema Principal: `<main id="main-content">`

**Causa**: O elemento `<main>` está mudando de tamanho durante o carregamento da página.

**Possíveis Razões**:
1. **Conteúdo dinâmico carregando** (Google Reviews, carousel)
2. **Imagens sem dimensões** dentro do main
3. **Fontes carregando** causando reflow
4. **CSS assíncrono** sendo aplicado
5. **JavaScript manipulando** conteúdo do main

**Score Total dos Shifts no Main**: 0.358 + 0.024 = **0.382** (99.7% do CLS total!)

---

## 🔧 Correções Aplicadas

### 1. Adicionar `contain: layout` ao `#main-content` ✅

**Arquivo**: `inc/critical-css.php` e `product.css`

```css
#main-content {
    contain: layout;
    /* Previne que mudanças dentro do main afetem layout externo */
}
```

### 2. Garantir altura mínima para seções principais ✅

**Arquivo**: `product.css`

```css
#main-content {
    min-height: 100vh; /* Reservar espaço desde o início */
}
```

### 3. Otimizar carregamento de conteúdo dinâmico ✅

**Já aplicado**:
- Content-visibility em seções abaixo da dobra
- Altura fixa no carousel de testimonials
- Espaço reservado para imagens

---

## 📝 Próximas Ações

### Prioridade ALTA:
1. ✅ Adicionar `contain: layout` ao `#main-content`
2. ✅ Garantir altura mínima
3. ⚠️ Verificar se há conteúdo sendo inserido dinamicamente no main
4. ⚠️ Verificar se fontes estão causando reflow no main

### Prioridade MÉDIA:
1. Verificar se CSS assíncrono está causando shift
2. Verificar se JavaScript está manipulando main de forma síncrona

---

## 🎯 Impacto Esperado

**Correções aplicadas devem reduzir CLS de 0.383 para ~0.1-0.15**

**Razão**: O shift principal (0.358) no `#main-content` deve ser significativamente reduzido com `contain: layout` e altura mínima.

---

## ✅ Status

- ✅ Script de análise criado (`scripts/analyze-cls.js`)
- ✅ Guia do Chrome DevTools criado (`CHROME-DEVTOOLS-CLS-GUIDE.md`)
- ✅ Elementos identificados
- ✅ Correções sendo aplicadas

