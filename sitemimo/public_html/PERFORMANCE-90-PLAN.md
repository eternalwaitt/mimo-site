# Plano para Performance Mobile 50 → 90

**Status Atual**: Performance 49-50  
**Meta**: Performance 90+  
**Gap**: ~40 pontos

## 📊 Análise Atual - Homepage Mobile

### Core Web Vitals (Atual vs Meta)
- **FCP**: 4.1s (meta: <1.8s) - **-2.3s necessário**
- **LCP**: 5.1s (meta: <2.5s) - **-2.6s necessário**
- **CLS**: 0.452 (meta: <0.1) - **-0.352 necessário**
- **TBT**: 0ms ✅ (já está bom)
- **SI**: 5.3s (meta: <3.4s) - **-1.9s necessário**

### Problemas Críticos Identificados

1. **Image Delivery** (Score 0) - 🔴 **CRÍTICO**
   - Economia possível: **2,761 KiB** (2.7 MB!)
   - Impacto: Alto no LCP e Network Payload

2. **Network Payload** (Score 0.5) - 🔴 **CRÍTICO**
   - Total: **3,879 KiB** (3.79 MB)
   - Meta: < 1,600 KiB

3. **FCP** (Score 0.22) - 🔴 **CRÍTICO**
   - Atual: 4.1s
   - Meta: < 1.8s
   - Gap: -2.3s

4. **LCP** (Score 0.25) - 🔴 **CRÍTICO**
   - Atual: 5.1s
   - Meta: < 2.5s
   - Gap: -2.6s

5. **CLS** (Score 0.20) - 🔴 **CRÍTICO**
   - Atual: 0.452
   - Meta: < 0.1
   - Gap: -0.352

6. **Unused CSS** (Score 0) - 🟡 **ALTO**
   - Economia: 72 KiB

7. **Unused JavaScript** (Score 0.5) - 🟡 **MÉDIO**
   - Economia: 33 KiB

8. **Minify CSS** (Score 0.5) - 🟡 **MÉDIO**
   - Economia: 22 KiB

9. **Minify JavaScript** (Score 0.5) - 🟡 **MÉDIO**
   - Economia: 7 KiB

10. **Font Display** (Score 0) - 🟡 **MÉDIO**
    - Economia: 40ms

## 🎯 Plano de Ação Prioritizado

### FASE 1: Otimizações de Imagem (Impacto: 🔴 CRÍTICO)
**Economia Esperada**: ~2.7 MB  
**Impacto em Performance**: +15-20 pontos

#### 1.1. Converter TODAS as imagens para AVIF/WebP
- [ ] Executar script de otimização em TODAS as imagens (não apenas prioritárias)
- [ ] Verificar imagens grandes (>100KB) e otimizar
- [ ] Garantir que imagens LCP estejam otimizadas

#### 1.2. Comprimir imagens existentes
- [ ] Reduzir qualidade de imagens não críticas
- [ ] Usar compressão agressiva onde apropriado
- [ ] Remover metadados de imagens

#### 1.3. Lazy loading agressivo
- [ ] Aplicar lazy loading em TODAS as imagens abaixo da dobra
- [ ] Usar `loading="lazy"` em todas as imagens não críticas
- [ ] Verificar se imagens LCP NÃO têm lazy loading

**Arquivos a Modificar**:
- `build/optimize-remaining-images.sh`: Expandir para todas as imagens
- Verificar todas as chamadas de `picture_webp()` para garantir lazy loading

### FASE 2: Reduzir Network Payload (Impacto: 🔴 CRÍTICO)
**Economia Esperada**: ~150 KiB  
**Impacto em Performance**: +5-10 pontos

#### 2.1. Remover Unused CSS (72 KiB)
- [ ] Executar PurgeCSS mais agressivo
- [ ] Verificar se arquivos purgados estão sendo usados
- [ ] Remover CSS não utilizado manualmente se necessário

#### 2.2. Remover Unused JavaScript (33 KiB)
- [ ] Analisar quais scripts não são usados
- [ ] Remover ou condicionar carregamento de scripts não críticos
- [ ] Verificar se todos os scripts são necessários

#### 2.3. Minificar CSS/JS (29 KiB)
- [ ] Garantir que arquivos minificados estão sendo usados
- [ ] Verificar se `USE_MINIFIED=true` está ativo
- [ ] Re-executar scripts de minificação se necessário

### FASE 3: Otimizar FCP (Impacto: 🔴 CRÍTICO)
**Redução Esperada**: 4.1s → <1.8s  
**Impacto em Performance**: +10-15 pontos

#### 3.1. Expandir CSS Crítico
- [ ] Mover mais CSS acima da dobra para inline
- [ ] Incluir estilos essenciais do Bootstrap no CSS crítico
- [ ] Reduzir CSS crítico ao mínimo necessário

#### 3.2. Otimizar Font Loading
- [ ] Usar `font-display: optional` para fontes não críticas
- [ ] Preload apenas fontes críticas
- [ ] Usar fontes do sistema como fallback

#### 3.3. Reduzir Render Blocking
- [ ] Verificar se ainda há CSS bloqueante
- [ ] Mover mais CSS para defer
- [ ] Otimizar ordem de carregamento

### FASE 4: Otimizar LCP (Impacto: 🔴 CRÍTICO)
**Redução Esperada**: 5.1s → <2.5s  
**Impacto em Performance**: +10-15 pontos

#### 4.1. Otimizar Imagem LCP
- [ ] Garantir que imagem LCP está em AVIF/WebP
- [ ] Comprimir imagem LCP agressivamente
- [ ] Adicionar `fetchpriority="high"` (já feito via preload)
- [ ] Verificar se preload está funcionando corretamente

#### 4.2. Otimizar LCP Discovery
- [ ] Verificar se preload está no lugar certo
- [ ] Adicionar preconnect para recursos LCP
- [ ] Otimizar tempo de resposta do servidor (se possível)

#### 4.3. Reduzir Tamanho da Imagem LCP
- [ ] Redimensionar imagem LCP para tamanho necessário
- [ ] Usar srcset responsivo
- [ ] Comprimir agressivamente

### FASE 5: Reduzir CLS (Impacto: 🔴 CRÍTICO)
**Redução Esperada**: 0.452 → <0.1  
**Impacto em Performance**: +5-10 pontos

#### 5.1. Identificar Layout Shift Culprits
- [ ] Analisar quais elementos causam layout shift
- [ ] Adicionar width/height em TODAS as imagens
- [ ] Reservar espaço para elementos dinâmicos

#### 5.2. Otimizar Font Loading para CLS
- [ ] Usar `font-display: optional` para prevenir FOIT
- [ ] Adicionar font metric overrides
- [ ] Reservar espaço para texto durante carregamento de fontes

#### 5.3. Reforçar Contain e Aspect-Ratio
- [ ] Adicionar `contain: layout style` em mais containers
- [ ] Adicionar `aspect-ratio` em mais elementos
- [ ] Reservar espaço com `min-height` onde necessário

### FASE 6: Otimizações Avançadas (Impacto: 🟡 MÉDIO)
**Impacto em Performance**: +5-10 pontos

#### 6.1. Otimizar Font Display
- [ ] Garantir `font-display: swap` ou `optional` em todas as fontes
- [ ] Economia: 40ms

#### 6.2. Cache Lifetimes
- [ ] Configurar headers de cache adequados
- [ ] Economia: 38 KiB

#### 6.3. Document Request Latency
- [ ] Otimizar servidor/CDN (se possível)
- [ ] Economia: 62 KiB

#### 6.4. Forced Reflow
- [ ] Identificar e corrigir forced reflows
- [ ] Otimizar JavaScript que causa reflows

## 📈 Impacto Esperado por Fase

### Fase 1: Image Delivery
- **Economia**: ~2.7 MB
- **Impacto**: +15-20 pontos
- **Performance Esperada**: 50 → 65-70

### Fase 2: Network Payload
- **Economia**: ~150 KiB
- **Impacto**: +5-10 pontos
- **Performance Esperada**: 65-70 → 70-80

### Fase 3: FCP
- **Redução**: 4.1s → <1.8s
- **Impacto**: +10-15 pontos
- **Performance Esperada**: 70-80 → 80-90

### Fase 4: LCP
- **Redução**: 5.1s → <2.5s
- **Impacto**: +10-15 pontos
- **Performance Esperada**: 80-90 → 90-95

### Fase 5: CLS
- **Redução**: 0.452 → <0.1
- **Impacto**: +5-10 pontos
- **Performance Esperada**: 90-95 → 95+

### Fase 6: Otimizações Avançadas
- **Impacto**: +5-10 pontos
- **Performance Esperada**: 95+ → 95-100

## 🎯 Meta Final

**Performance Mobile**: 50 → **90+**  
**FCP**: 4.1s → **<1.0s**  
**LCP**: 5.1s → **<2.0s**  
**CLS**: 0.452 → **<0.05**  
**SI**: 5.3s → **<2.5s**

## 📋 Ordem de Implementação

1. **Fase 1** (Image Delivery) - Maior impacto
2. **Fase 2** (Network Payload) - Rápido de implementar
3. **Fase 3** (FCP) - Crítico para primeira impressão
4. **Fase 4** (LCP) - Crítico para experiência
5. **Fase 5** (CLS) - Crítico para estabilidade
6. **Fase 6** (Avançadas) - Polimento final

