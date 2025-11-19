# PageSpeed Insights Results v2.6.3

**Data**: 2025-11-15 11:10 AM GMT-3  
**URL**: https://minhamimo.com.br/  
**Report**: https://pagespeed.web.dev/analysis/https-minhamimo-com-br/nhraug5hgf?form_factor=mobile  
**Versão**: 2.6.3

## 📊 Scores Gerais (Mobile)

| Categoria | Score | Status | Mudança |
|-----------|-------|--------|---------|
| **Performance** | **50** | 🟡 Médio (50-89) | ✅ +6 (de 44) |
| **Accessibility** | **91** | 🟢 Bom (90-100) | ✅ Mantido |
| **Best Practices** | **96** | 🟢 Excelente (90-100) | ✅ Mantido |
| **SEO** | **100** | 🟢 Perfeito (100) | ✅ Mantido |

## 🎯 Core Web Vitals (Mobile)

| Métrica | Valor | Meta | Status | Mudança |
|---------|-------|------|--------|---------|
| **FCP** (First Contentful Paint) | **4.1s** | < 1.8s | 🔴 Ruim (+2.3s) | ⚠️ Sem mudança |
| **LCP** (Largest Contentful Paint) | **4.2s** | < 2.5s | 🔴 Ruim (+1.7s) | ✅ **-4.1s** (de 8.3s) |
| **TBT** (Total Blocking Time) | **0ms** | < 200ms | 🟢 Excelente | ✅ Mantido |
| **CLS** (Cumulative Layout Shift) | **0.401** | < 0.1 | 🔴 Ruim (+0.301) | ⚠️ **-0.077** (de 0.478) |
| **SI** (Speed Index) | **10.8s** | < 3.4s | 🔴 Ruim (+7.4s) | ⚠️ Sem mudança |

## 📈 Melhorias Observadas

### ✅ Sucessos
1. **Performance Score**: 44 → 50 (+6 pontos, +13.6%)
2. **LCP**: 8.3s → 4.2s (-4.1s, -49.4%) - **MELHORIA SIGNIFICATIVA**
3. **CLS**: 0.478 → 0.401 (-0.077, -16.1%) - Melhoria parcial
4. **Animações**: 94 → 91 elementos (-3, -3.2%) - Melhoria parcial

### ⚠️ Ainda Precisa Melhorar
1. **CLS**: 0.401 ainda muito alto (meta: < 0.1)
   - **Culprit principal**: `<div class="col-md-7 mx-auto my-5 overflow-hidden">` com score **0.375** (93% do CLS total)
   - **Culprit secundário**: `<div class="container row mx-auto">` com score **0.026**
2. **FCP**: 4.1s ainda alto (meta: < 1.8s)
3. **SI**: 10.8s ainda alto (meta: < 3.4s)
4. **Animações**: 91 elementos ainda muito alto (meta: < 2)
5. **Network Payload**: 4,074 KiB ainda alto

## 🔍 Layout Shift Culprits (Detalhado)

| Elemento | Layout Shift Score | % do Total |
|----------|-------------------|------------|
| **Total** | **0.401** | 100% |
| `<div class="col-md-7 mx-auto my-5 overflow-hidden">` | **0.375** | 93.5% |
| `<div class="container row mx-auto">` | **0.026** | 6.5% |
| `<div class="container">` (navbar) | **0.000** | 0% |

### Análise do Culprit Principal
- **Elemento**: `main#main-content > div#about > div.container > div.col-md-7`
- **Problema**: Este é o container do texto "BELEZA SEM PADRÃO" na seção #about
- **Causa provável**: 
  - Texto carregando sem dimensões reservadas
  - Fontes carregando e causando reflow
  - Imagem ao lado (`#florzinha`) causando shift quando carrega

## 🎯 Oportunidades de Otimização

### 🔴 Alta Prioridade

#### 1. Layout Shift Culprits - CLS 0.401
**Economia estimada**: Reduzir CLS para < 0.1  
**Impacto**: 🔴 Crítico - Afeta CLS diretamente

**Soluções**:
- [ ] Adicionar `min-height` no `.col-md-7` para reservar espaço
- [ ] Adicionar `contain: layout` no `.col-md-7`
- [ ] Adicionar `aspect-ratio` ou dimensões fixas no container #about
- [ ] Garantir que fontes tenham `size-adjust` para prevenir reflow
- [ ] Adicionar `min-height` no container `#about .container.row.mx-auto`

#### 2. Improve Image Delivery
**Economia estimada**: 2,759 KiB  
**Impacto**: 🔴 Crítico - Afeta LCP e Network Payload

**Soluções**:
- [ ] Comprimir mais imagens
- [ ] Implementar srcset com múltiplos tamanhos
- [ ] Lazy load de imagens abaixo do fold
- [ ] Otimizar tamanho da imagem LCP mobile

#### 3. Avoid Non-Composited Animations
**91 animated elements found**  
**Impacto**: 🟡 Médio - Afeta performance

**Soluções**:
- [ ] Verificar se animações mobile foram realmente desabilitadas
- [ ] Adicionar `will-change: auto` após animação
- [ ] Usar apenas `transform` e `opacity` (já implementado)
- [ ] Desabilitar animações em mais elementos no mobile

### 🟡 Média Prioridade

#### 4. Reduce Unused CSS
**Economia estimada**: 72 KiB  
**Impacto**: 🟡 Médio

**Soluções**:
- [ ] Executar PurgeCSS novamente
- [ ] Remover CSS não utilizado manualmente
- [ ] Verificar se CSS minificado está sendo usado

#### 5. Reduce Unused JavaScript
**Economia estimada**: 83 KiB  
**Impacto**: 🟡 Médio

**Soluções**:
- [ ] Remover scripts não utilizados
- [ ] Tree-shaking para JavaScript customizado
- [ ] Verificar se jQuery completo é necessário

#### 6. Avoid Enormous Network Payloads
**Total**: 4,074 KiB  
**Impacto**: 🟡 Médio

**Soluções**:
- [ ] Comprimir todas as imagens (já em progresso)
- [ ] Remover código não utilizado (CSS/JS)
- [ ] Lazy load de conteúdo abaixo do fold

## 📝 Próximos Passos Prioritários

### Sprint 1 (Impacto Imediato - 1 dia)
1. **Corrigir CLS no `.col-md-7`**:
   - Adicionar `min-height` baseado no conteúdo esperado
   - Adicionar `contain: layout`
   - Garantir que fontes não causem reflow
2. **Verificar animações mobile**:
   - Confirmar que regras CSS mobile estão sendo aplicadas
   - Adicionar mais regras para desabilitar animações

### Sprint 2 (Alto Impacto - 2-3 dias)
1. **Otimizar imagens**:
   - Comprimir imagens restantes
   - Implementar srcset responsivo
2. **Remover CSS/JS não utilizado**:
   - Executar PurgeCSS
   - Remover scripts não utilizados

## 🎉 Conquistas

- ✅ **LCP melhorou 49%** (8.3s → 4.2s) - **SUCESSO MAIOR**
- ✅ **Performance score melhorou 13.6%** (44 → 50)
- ✅ **CLS melhorou 16%** (0.478 → 0.401) - Melhoria parcial
- ✅ **Animações reduziram 3%** (94 → 91) - Melhoria parcial

## ⚠️ Observações

1. **CLS ainda alto**: O elemento `.col-md-7` está causando 93% do layout shift. Esta é a prioridade #1.
2. **Animações**: Ainda há 91 elementos animados. As regras CSS mobile podem não estar sendo aplicadas corretamente, ou há animações que não foram cobertas.
3. **FCP e SI**: Não melhoraram significativamente. Podem estar relacionados ao tamanho do CSS/JS e ao carregamento de recursos.

## 🔗 Referências

- [PageSpeed Insights Report](https://pagespeed.web.dev/analysis/https-minhamimo-com-br/nhraug5hgf?form_factor=mobile)
- [CLS Culprit Investigation](https://developer.chrome.com/docs/performance/insights/cls-culprit?utm_source=lighthouse&utm_medium=lr)
- [Performance Score Calculator](https://googlechrome.github.io/lighthouse/scorecalc/#FCP=4068&LCP=4218&TBT=0&CLS=0.4&SI=10831&TTI=4218&device=mobile&version=13.0.1)

