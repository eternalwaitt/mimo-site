# Plano de Ação Completo - Todos os Problemas do Google

**Data**: 2025-11-15  
**Versão**: 2.6.6  
**Total de Problemas Identificados**: 15

## 🔴 CRÍTICOS (Alto Impacto - Economia de Bytes)

### 1. Reduce Unused CSS - 86 KiB
**Status**: ⚠️ Ainda presente  
**Causa Possível**: Arquivos purgados não estão sendo servidos em produção  
**Ação Imediata**:
- [ ] Verificar se `css/purged/product.min.css` existe em produção
- [ ] Verificar se `css/purged/dark-mode.min.css` existe em produção
- [ ] Verificar se `css/purged/animations.min.css` existe em produção
- [ ] Verificar se `get_css_asset()` está retornando caminhos corretos
- [ ] Verificar se `USE_MINIFIED=true` está ativo em produção
- [ ] Testar se arquivos estão acessíveis via URL direta

**Impacto Esperado**: +3-5 pontos

### 2. Minify CSS - 23 KiB
**Status**: ⚠️ Ainda presente  
**Causa Possível**: Arquivos minificados não estão sendo servidos  
**Ação Imediata**:
- [ ] Verificar se `minified/css-modules-mobile-ui-improvements.min.css` existe
- [ ] Verificar se `minified/css-modules-accessibility-fixes.min.css` existe
- [ ] Verificar se `minified/product.min.css` existe
- [ ] Verificar se asset helper está usando arquivos minificados
- [ ] Testar se arquivos estão acessíveis via URL direta

**Impacto Esperado**: +1-2 pontos

### 3. Reduce Unused JavaScript - 33 KiB
**Status**: ⚠️ Ainda presente  
**Causa**: Bootstrap JS carrega módulos não usados (tooltip, modal, dropdown, collapse, scrollspy)  
**Ação**:
- [ ] Criar build customizado do Bootstrap apenas com Carousel e Tab
- [ ] Ou usar lazy loading para Bootstrap JS (quando necessário)
- [ ] Verificar se há outros JS não usado

**Impacto Esperado**: +2-3 pontos

### 4. Minify JavaScript - 7 KiB
**Status**: ⚠️ Ainda presente  
**Causa Possível**: Arquivos JS não estão minificados  
**Ação**:
- [ ] Verificar se `minified/main.min.js` existe
- [ ] Verificar se `minified/dark-mode.min.js` existe
- [ ] Verificar se `minified/form-main.min.js` existe
- [ ] Executar `build/minify-js.sh` se necessário
- [ ] Verificar se asset helper está usando arquivos minificados

**Impacto Esperado**: +1 ponto

## 🟡 MÉDIOS (Médio Impacto - Economia de Tempo)

### 5. Font Display - 40ms
**Status**: ⚠️ Ainda presente  
**Causa Possível**: Fontes podem não estar usando `font-display: optional/swap` em produção  
**Ação**:
- [ ] Verificar se Google Fonts está usando `display=optional` ou `display=swap`
- [ ] Verificar se `@font-face` em CSS está usando `font-display: optional`
- [ ] Verificar se fontes locais (Akrobat) estão usando `font-display: optional`

**Impacto Esperado**: +1 ponto

### 6. Use Efficient Cache Lifetimes - 38 KiB
**Status**: ⚠️ Ainda presente  
**Ação**:
- [ ] Configurar cache headers adequados no servidor
- [ ] CSS/JS: Cache por 1 ano com versioning
- [ ] Imagens: Cache por 1 ano
- [ ] HTML: Cache por 1 hora ou sem cache

**Impacto Esperado**: +1 ponto

### 7. Document Request Latency - 64 KiB
**Status**: ⚠️ Ainda presente  
**Ação**:
- [ ] Otimizar tempo de resposta do servidor
- [ ] Verificar se há queries lentas no PHP
- [ ] Verificar se há processamento pesado no servidor
- [ ] Considerar CDN se necessário

**Impacto Esperado**: +1-2 pontos

## ⚠️ MÉTRICAS ALTAS (Core Web Vitals)

### 8. First Contentful Paint (FCP) - 4.1s
**Meta**: <1.8s  
**Gap**: -2.3s  
**Causas Possíveis**:
- Render-blocking CSS
- Render-blocking JavaScript
- Tempo de resposta do servidor
- CSS crítico não expandido o suficiente

**Ações**:
- [ ] Expandir CSS crítico ainda mais
- [ ] Remover render-blocking resources
- [ ] Otimizar tempo de resposta do servidor
- [ ] Preload recursos críticos

**Impacto Esperado**: +5-10 pontos

### 9. Largest Contentful Paint (LCP) - 6.3s
**Meta**: <2.5s  
**Gap**: -3.8s  
**Causas Possíveis**:
- Imagem LCP não otimizada
- Tempo de resposta do servidor
- Render-blocking resources
- Imagem LCP não está sendo servida como AVIF/WebP

**Ações**:
- [ ] Verificar se imagem LCP está sendo servida como AVIF/WebP
- [ ] Adicionar `fetchpriority="high"` na imagem LCP
- [ ] Preload imagem LCP
- [ ] Otimizar tempo de resposta do servidor

**Impacto Esperado**: +5-10 pontos

### 10. Speed Index (SI) - 5.2s
**Meta**: <3.4s  
**Gap**: -1.8s  
**Causas Possíveis**:
- Render-blocking resources
- CSS crítico não expandido
- JavaScript bloqueando renderização

**Ações**:
- [ ] Expandir CSS crítico
- [ ] Remover render-blocking resources
- [ ] Otimizar carregamento de recursos

**Impacto Esperado**: +3-5 pontos

### 11. Time to Interactive (TTI) - 5.1s
**Causas Possíveis**:
- JavaScript pesado
- Render-blocking resources
- Tempo de resposta do servidor

**Ações**:
- [ ] Defer/async em todos scripts não críticos
- [ ] Reduzir JavaScript não usado
- [ ] Otimizar tempo de resposta

**Impacto Esperado**: +2-3 pontos

## ⚠️ OUTROS PROBLEMAS

### 12. Improve Image Delivery
**Status**: ⚠️ Ainda presente (mas não quantificado)  
**Ação**:
- [ ] Verificar se todas imagens grandes têm AVIF/WebP
- [ ] Verificar se `picture_webp()` está servindo AVIF/WebP corretamente
- [ ] Verificar se browser está recebendo formatos otimizados

### 13. Avoid Large Layout Shifts
**Status**: ⚠️ Pode estar presente  
**Ação**:
- [ ] Verificar se todas imagens têm width/height explícitos
- [ ] Verificar se há elementos dinâmicos causando shifts
- [ ] Usar `contain: layout` onde necessário

### 14. Forced Reflow
**Status**: ⚠️ Pode estar presente  
**Ação**:
- [ ] Verificar JavaScript que causa reflows
- [ ] Otimizar manipulação de DOM
- [ ] Usar `requestAnimationFrame` para animações

### 15. Layout Shift Culprits
**Status**: ✅ Resolvido (CLS: 0.000)  
**Nota**: Não é mais um problema

## 📊 Priorização

### Fase 1: Verificação Imediata (Hoje)
1. ✅ Verificar se arquivos purgados/minificados estão em produção
2. ✅ Verificar se asset helper está usando arquivos corretos
3. ✅ Verificar se imagens AVIF/WebP estão sendo servidas

### Fase 2: Correções CSS/JS (Esta Semana)
1. ⚠️ Garantir que arquivos purgados/minificados estão sendo servidos
2. ⚠️ Minificar JavaScript restante
3. ⚠️ Verificar font-display em produção

### Fase 3: Otimizações Render (Próxima Semana)
1. ⚠️ Expandir CSS crítico
2. ⚠️ Otimizar imagem LCP
3. ⚠️ Melhorar tempo de resposta do servidor

### Fase 4: Best Practices (Futuro)
1. ⚠️ Criar build customizado do Bootstrap
2. ⚠️ Implementar CSP, HSTS, COOP
3. ⚠️ Otimizar animações (GPU acceleration)

## 🎯 Impacto Total Esperado

| Fase | Economia | Impacto Esperado |
|------|----------|-----------------|
| Fase 1 (Verificação) | - | +0-5 pontos |
| Fase 2 (CSS/JS) | ~149 KiB | +7-11 pontos |
| Fase 3 (Render) | - | +10-20 pontos |
| Fase 4 (Best Practices) | ~33 KiB | +2-3 pontos |
| **Total** | **~182 KiB** | **+19-39 pontos** |

**Meta Final**: Performance 66 → **85-105** (com todas correções)

