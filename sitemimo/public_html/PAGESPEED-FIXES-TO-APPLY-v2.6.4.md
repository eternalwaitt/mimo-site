# Correções a Aplicar - v2.6.4

**Data**: 2025-11-15  
**Baseado em**: Análise completa de 28 testes PageSpeed Insights

## ✅ Otimizações Automáticas Executadas

1. ✅ **JavaScript Minificado**: Arquivos criados em `minified/`
2. ✅ **CSS Purgado**: ~22 KiB economizados (product.css, dark-mode.css, animations.css)
3. ✅ **CSS Minificado**: Arquivos criados em `minified/`

## 🔴 Correções Críticas Pendentes

### 1. CLS - Imagens sem width/height explícitos

**Problema**: Score 0.5 em "unsized-images" em várias páginas

**Ações**:
- [ ] Verificar se `picture_webp()` está detectando width/height corretamente
- [ ] Adicionar width/height explícitos em todas as chamadas de `picture_webp()`
- [ ] Verificar imagens em páginas de serviço (cilios, esmalteria, esteticafacial)
- [ ] Garantir que imagens de testimonials têm width/height

**Arquivos a Modificar**:
- `cilios/index.php`: Linha 32 - imagem sem width/height
- Verificar outras páginas de serviço
- Verificar `index.php` para todas as imagens

### 2. CLS - Layout Shift Culprits

**Problema**: Score 0 em "cls-culprits-insight" e "layout-shifts" em várias páginas

**Páginas Críticas**:
- Cilios: CLS 0.77 (desktop), 0.55-0.69 (mobile) ❌
- Esmalteria: CLS 0.92 (desktop) ❌
- Esteticafacial: CLS 0.74 (desktop), 0.27-0.32 (mobile) ❌

**Ações**:
- [ ] Reforçar `contain: layout style` em containers problemáticos
- [ ] Adicionar `min-height` mais específico
- [ ] Verificar font loading (FOIT/FOUT)
- [ ] Adicionar `aspect-ratio` em todos os containers de imagem

### 3. LCP Discovery

**Problema**: Score 0 em "lcp-discovery-insight" em várias páginas

**Ações**:
- [ ] Verificar se preload está configurado corretamente
- [ ] Adicionar `fetchpriority="high"` nas imagens LCP
- [ ] Verificar se LCP images não têm lazy loading
- [ ] Otimizar LCP breakdown (tempo de resposta do servidor)

### 4. Image Delivery

**Problema**: Score 0-0.5 em várias páginas

**Ações**:
- [ ] Converter TODAS as imagens para AVIF/WebP (não apenas prioritárias)
- [ ] Adicionar `srcset` responsivo em todas as imagens
- [ ] Comprimir imagens grandes
- [ ] Verificar imagens em páginas de serviço

### 5. Render Blocking

**Problema**: Score 0 em várias páginas

**Ações**:
- [ ] Verificar se CSS não crítico está usando `loadCSS()`
- [ ] Mover mais CSS para defer
- [ ] Verificar se scripts estão com `defer` ou `async`

### 6. Network Dependency Tree

**Problema**: Score 0 em várias páginas

**Ações**:
- [ ] Otimizar ordem de carregamento de recursos
- [ ] Reduzir dependências críticas
- [ ] Preconnect para recursos externos

## 🟡 Correções de Média Prioridade

### 7. Unminified CSS/JS
- [ ] Verificar se arquivos minificados estão sendo usados
- [ ] Garantir que `USE_MINIFIED=true` está ativo

### 8. Unused CSS/JS
- [ ] Verificar se PurgeCSS está sendo aplicado
- [ ] Analisar e remover JavaScript não utilizado

### 9. Font Display
- [ ] Verificar se todas as fontes têm `font-display: swap` ou `optional`

## 📋 Ordem de Implementação

1. **CLS - Imagens** (maior impacto)
2. **CLS - Layout Shifts** (maior impacto)
3. **LCP Discovery** (alto impacto)
4. **Image Delivery** (alto impacto)
5. **Render Blocking** (médio impacto)
6. **Network Dependency Tree** (médio impacto)
7. **Unminified/Unused** (baixo impacto)

