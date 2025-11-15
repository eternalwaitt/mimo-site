# Análise PageSpeed Insights v2.6.3 - Regressão Detectada

**Data**: 2025-11-15  
**Link**: https://pagespeed.web.dev/analysis/https-minhamimo-com-br/j7xpdzt3z0?form_factor=mobile  
**Versão**: 2.6.3

## 📊 Resultados Atuais

### Scores
- **Performance**: 47 (⚠️ REGRESSÃO: 50 → 47)
- **Accessibility**: 91 (✅ Mantido)
- **Best Practices**: 96 (✅ Mantido)
- **SEO**: 100 (✅ Mantido)

### Core Web Vitals (Mobile)
- **FCP**: 4.1s (+2)
- **LCP**: 4.5s (+9)
- **TBT**: 0ms (+30) ✅
- **CLS**: 0.531 (+4) ⚠️ **AINDA ALTO** (aumentou de 0.401)
- **SI**: 8.4s (+2)

## 🚨 Problemas Críticos

### 1. CLS - Layout Shift (0.531)
**Status**: ⚠️ **REGRESSÃO** (0.401 → 0.531)

**Problemas identificados**:
- "Layout shift culprits" ainda presente
- Nossas correções não foram aplicadas ou não funcionaram completamente
- Possível cache não atualizado

**Ações necessárias**:
- Verificar se `contain: layout style` está sendo aplicado
- Verificar se `min-height` está reservando espaço corretamente
- Verificar cache/CDN

### 2. Animações Não Compositadas (126 elementos)
**Status**: ⚠️ **REGRESSÃO** (91 → 126)

**Problema**: Nossas correções para desabilitar animações no mobile não foram aplicadas.

**Ações necessárias**:
- Verificar se `js/animations.js` está detectando mobile corretamente
- Verificar se CSS mobile (`@media (max-width: 768px)`) está sendo aplicado
- Verificar cache/CDN

### 3. Image Delivery (2,755 KiB)
**Status**: ⚠️ **Não resolvido**

**Ações necessárias**:
- Executar script de otimização de imagens
- Converter mais imagens para AVIF/WebP
- Implementar lazy loading adequado

### 4. Font Display (30ms)
**Status**: ⚠️ **Não resolvido**

**Ações necessárias**:
- Verificar se `font-display: swap` está sendo aplicado corretamente
- Verificar se Font Awesome está usando font-display

### 5. ARIA Attributes
**Status**: ⚠️ **Novo problema**

**Problema**: "Elements with an ARIA [role] that require children to contain a specific [role] are missing some or all of those required children."

**Ações necessárias**:
- Verificar elementos com `role="tablist"` e seus children
- Garantir que todos os children tenham `role="tab"` ou `role="tabpanel"`

### 6. Contraste de Cores
**Status**: ⚠️ **Ainda presente**

**Problema**: "Background and foreground colors do not have a sufficient contrast ratio."

**Ações necessárias**:
- Verificar se nossas correções de contraste foram aplicadas
- Verificar cache/CDN

## 🔍 Possíveis Causas da Regressão

1. **Cache não atualizado**: CDN/Varnish pode estar servindo versão antiga
2. **Mudanças não aplicadas**: Arquivos podem não ter sido deployados corretamente
3. **Novos elementos**: Novos elementos podem estar causando layout shifts
4. **CSS não carregado**: CSS mobile pode não estar sendo carregado

## 📋 Próximos Passos

1. **Verificar cache/CDN**: Limpar cache e verificar se versão correta está sendo servida
2. **Verificar deploy**: Confirmar que todos os arquivos foram atualizados
3. **Re-aplicar correções CLS**: Verificar e reforçar correções de layout shift
4. **Re-aplicar correções animações**: Verificar e reforçar desabilitação de animações mobile
5. **Corrigir ARIA**: Verificar e corrigir elementos com role que requerem children
6. **Re-analisar**: Executar nova análise após correções

## 📝 Notas

- Performance score regrediu de 50 para 47
- CLS aumentou de 0.401 para 0.531
- Animações aumentaram de 91 para 126 elementos
- Isso sugere que nossas correções não foram aplicadas ou não funcionaram

**Ação imediata**: Verificar cache e re-aplicar correções críticas.

