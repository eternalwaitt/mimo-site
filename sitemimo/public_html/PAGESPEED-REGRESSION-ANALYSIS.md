# Análise de Regressão - PageSpeed Insights

**Data da Análise**: Nov 15, 2025, 2:03:21 AM  
**URL**: https://minhamimo.com.br/  
**Status**: ⚠️ **REGRESSÃO DETECTADA**

## 🚨 Problema Crítico

### Mobile - Regressão Significativa

| Métrica | Anterior (1:57 AM) | Atual (2:03 AM) | Mudança | Status |
|---------|-------------------|-----------------|---------|--------|
| **Performance** | **75** | **54** | **-21 pontos** 🔴 | **REGRESSÃO CRÍTICA** |
| **Accessibility** | 91 | 91 | 0 | ✅ Mantido |
| **FCP** | 4.1s | **2.9s** | **-29%** 🎉 | ✅ Melhorou |
| **LCP** | **4.4s** | **6.2s** | **+41%** 🔴 | 🔴 **PIOROU MUITO** |
| **CLS** | **0** | **0.294** | **+0.294** 🔴 | 🔴 **PIOROU MUITO** |
| **SI** | 4.2s | 5.2s | +24% | 🔴 Piorou |
| **TBT** | 0ms | 0ms | 0 | ✅ Mantido |
| **Animações** | **2 elementos** | **38 elementos** | **+1800%** 🔴 | 🔴 **PIOROU MUITO** |

### Problemas Identificados

1. **CLS: 0 → 0.294** 🔴
   - **Impacto**: Layout shift crítico detectado
   - **Causa provável**: Cache não limpo ou mudanças que quebraram layout
   - **Prioridade**: 🔴 CRÍTICA

2. **LCP: 4.4s → 6.2s** 🔴
   - **Impacto**: +41% mais lento
   - **Causa provável**: Imagens não carregando corretamente ou preload quebrado
   - **Prioridade**: 🔴 CRÍTICA

3. **Animações: 2 → 38 elementos** 🔴
   - **Impacto**: Performance degradada
   - **Causa provável**: CSS de animações não está sendo aplicado corretamente
   - **Prioridade**: 🟡 ALTA

4. **Performance Score: 75 → 54** 🔴
   - **Impacto**: -21 pontos (28% de regressão)
   - **Causa**: Combinação dos problemas acima
   - **Prioridade**: 🔴 CRÍTICA

## 🔍 Análise de Causas

### Possíveis Causas

1. **Cache não limpo**
   - ASSET_VERSION atualizado, mas cache do servidor/CDN não foi limpo
   - Navegador ainda usando versão antiga

2. **Mudanças quebradas**
   - Correção do ARIA pode ter afetado layout
   - CSS não está sendo carregado corretamente

3. **Imagens não otimizadas**
   - Preload pode estar quebrado
   - AVIF/WebP não estão sendo servidos

4. **CSS de animações**
   - `translateZ(0)` não está sendo aplicado
   - Animações voltaram a ser não-composadas

## 🎯 Ações Imediatas Necessárias

### Prioridade Crítica (Fazer Agora)

1. **Verificar CLS**
   - Verificar se `aspect-ratio` está sendo aplicado
   - Verificar se `min-height` está sendo aplicado
   - Verificar se imagens têm `width` e `height`

2. **Verificar LCP**
   - Verificar se preload está funcionando
   - Verificar se AVIF/WebP estão sendo servidos
   - Verificar se `fetchpriority="high"` está presente

3. **Verificar Animações**
   - Verificar se `translateZ(0)` está sendo aplicado
   - Verificar se CSS de animações está carregando
   - Verificar se `will-change` está sendo usado

4. **Limpar Cache**
   - Limpar cache do servidor/CDN
   - Verificar se ASSET_VERSION está sendo usado
   - Forçar reload completo

### Prioridade Alta

5. **Verificar CSS Crítico**
   - Verificar se `critical-css.php` está sendo carregado
   - Verificar se `accessibility-fixes.css` está sendo carregado

6. **Verificar JavaScript**
   - Verificar se scripts estão sendo carregados corretamente
   - Verificar se `defer` está funcionando

## 📊 Comparação com Análise Anterior

### Análise Anterior (1:57 AM)
- Performance: 75
- LCP: 4.4s
- CLS: 0
- Animações: 2 elementos

### Análise Atual (2:03 AM)
- Performance: 54 (-21)
- LCP: 6.2s (+41%)
- CLS: 0.294 (+0.294)
- Animações: 38 elementos (+1800%)

## 📊 Desktop - Melhoria

| Métrica | Anterior (1:57 AM) | Atual (2:03 AM) | Mudança | Status |
|---------|-------------------|-----------------|---------|--------|
| **Performance** | 94 | **97** | **+3 pontos** 🎉 | ✅ Melhorou |
| **Accessibility** | 91 | 91 | 0 | ✅ Mantido |
| **FCP** | 0.8s | 0.8s | 0 | ✅ Mantido |
| **LCP** | 1.3s | **1.1s** | **-15%** 🎉 | ✅ Melhorou |
| **CLS** | 0.01 | **0.009** | **-10%** 🎉 | ✅ Melhorou |
| **SI** | 1.7s | **1.3s** | **-24%** 🎉 | ✅ Melhorou |
| **Animações** | 1 elemento | 1 elemento | 0 | ✅ Mantido |

**Desktop está excelente!** (97/100 performance)

## ⚠️ Conclusão

**REGRESSÃO CRÍTICA NO MOBILE DETECTADA**

As mudanças recentes causaram uma regressão significativa no performance score mobile, mas **melhoraram o desktop**. O problema mais crítico é o **CLS que voltou para 0.294 no mobile**, o que indica que o layout está mudando durante o carregamento.

**Possíveis causas**:
1. **Cache não limpo no mobile** - ASSET_VERSION atualizado mas cache do servidor/CDN não foi limpo
2. **CSS crítico não carregando no mobile** - Pode estar faltando no mobile
3. **Preload quebrado no mobile** - Imagens LCP não estão sendo preloadadas
4. **Animações não otimizadas no mobile** - `translateZ(0)` não está sendo aplicado

**Ações imediatas necessárias**:
1. Verificar se cache foi limpo (servidor/CDN)
2. Verificar se CSS crítico está sendo carregado no mobile
3. Verificar se preload está funcionando no mobile
4. Verificar se `translateZ(0)` está sendo aplicado nas animações mobile
5. Verificar se `aspect-ratio` e `min-height` estão sendo aplicados no mobile

